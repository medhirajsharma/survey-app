<?php
namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Vidhansabha;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyReportController extends Controller
{
    public function show(Survey $survey, Request $request)
    {
        $survey->load('questions.options.answers');

        $query = $survey->surveyResponses()->with('answers.option.question', 'vidhansabha');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

        if ($request->filled('vidhansabha_id')) {
            $query->where('vidhansabha_id', $request->vidhansabha_id);
        }

        if ($request->filled('sort') && in_array($request->sort, ['name', 'mobile_no', 'vidhansabha_id'])) {
            $query->orderBy($request->sort, $request->direction ?? 'asc');
        }

        $surveyResponses = $query->paginate(10)->withQueryString();
        $vidhansabhas    = Vidhansabha::all();

        return view('survey-reports.show', compact('survey', 'surveyResponses', 'vidhansabhas'));
    }

    public function publicReport(Survey $survey)
    {
        $survey->load('questions.options.answers');

        return view('survey-reports.public-show', compact('survey'));
    }

    public function export(Survey $survey)
    {
        $survey->load('questions.options', 'surveyResponses.answers.option', 'surveyResponses.vidhansabha');

        $csvFileName = 'survey_report_' . Str::slug($survey->title) . '.csv';
        $headers     = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://temp', 'r+');
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Add CSV header
        $headerRow = ['Respondent Name', 'Mobile No', 'Vidhansabha'];
        foreach ($survey->questions as $question) {
            $headerRow[] = $question->text;
        }
        fputcsv($handle, $headerRow);

        // Add CSV data
        foreach ($survey->surveyResponses as $response) {
            $dataRow = [
                $response->name,
                $response->mobile_no,
                $response->vidhansabha->constituency_name ?? 'N/A',
                $response->caste ?? 'N/A',
            ];
            foreach ($survey->questions as $question) {
                $answer    = $response->answers->firstWhere('option.question_id', $question->id);
                $dataRow[] = $answer ? $answer->option->text : 'N/A';
            }
            fputcsv($handle, $dataRow);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200, $headers);
    }
}
