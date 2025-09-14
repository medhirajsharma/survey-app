<?php
namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Support\Str;

class SurveyReportController extends Controller
{
    public function show(Survey $survey)
    {
        $survey->load('questions.options.answers');                                                  // Load questions and their answers
        $surveyResponses = $survey->surveyResponses()->with('answers.option.question')->paginate(2); // Paginate survey responses

        return view('survey-reports.show', compact('survey', 'surveyResponses'));
    }

    public function export(Survey $survey)
    {
        $survey->load('questions.options', 'surveyResponses.answers.option');

        $csvFileName = 'survey_report_' . Str::slug($survey->title) . '.csv';
        $headers     = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://temp', 'r+');

        // Add CSV header
        $headerRow = ['Respondent Name'];
        foreach ($survey->questions as $question) {
            $headerRow[] = $question->text;
        }
        fputcsv($handle, $headerRow);

        // Add CSV data
        foreach ($survey->surveyResponses as $response) {
            $dataRow = [$response->name];
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
