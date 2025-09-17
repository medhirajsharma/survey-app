<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\Vidhansabha;
use Illuminate\Http\Request;

class SurveyResponseController extends Controller
{
    public function create(Survey $survey)
    {
        $vidhansabhas = Vidhansabha::all();
        return view('survey-responses.create', compact('survey', 'vidhansabhas'));
    }

    public function store(Request $request, Survey $survey)
    {
        $cookieName = 'survey_submitted_' . $survey->id;

        if ($request->cookie($cookieName)) {
            return redirect()->route('public.surveys.take', $survey)
                ->with('error', 'You have already submitted this survey.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|digits:10',
            'vidhansabha_id' => 'required|exists:vidhansabhas,id',
            'answers' => 'required|array',
            'answers.*' => 'required|exists:options,id',
        ]);

        $surveyResponse = $survey->surveyResponses()->create($request->only('name', 'mobile_no', 'vidhansabha_id'));

        foreach ($request->answers as $questionId => $optionId) {
            $surveyResponse->answers()->create([
                'question_id' => $questionId,
                'option_id' => $optionId,
            ]);
        }

        return redirect()->route('public.surveys.results', [$survey, $surveyResponse])
            ->withCookie(cookie($cookieName, true, 60 * 24 * 365)) // Set cookie for 1 year
            ->with('success', 'Thank you for taking the survey!');
    }

    public function showResults(Survey $survey, SurveyResponse $surveyResponse)
    {
        // Ensure the survey response belongs to the survey
        if ($surveyResponse->survey_id !== $survey->id) {
            abort(404);
        }

        // Load questions with their options and answers for this survey response
        $survey->load(['questions.options', 'questions.answers' => function ($query) use ($surveyResponse) {
            $query->where('survey_response_id', $surveyResponse->id);
        }]);

        // Aggregate results for all public people
        $totalResponses = $survey->surveyResponses()->count();
        $questionResults = [];

        foreach ($survey->questions as $question) {
            $optionCounts = [];
            foreach ($question->options as $option) {
                $optionCounts[$option->id] = 0;
            }

            $answersForQuestion = Answer::where('question_id', $question->id)->get();

            foreach ($answersForQuestion as $answer) {
                if (isset($optionCounts[$answer->option_id])) {
                    $optionCounts[$answer->option_id]++;
                }
            }

            $questionResults[$question->id] = [
                'question' => $question,
                'options' => [],
            ];

            foreach ($question->options as $option) {
                $count = $optionCounts[$option->id] ?? 0;
                $percentage = ($totalResponses > 0) ? round(($count / $totalResponses) * 100, 2) : 0;
                $questionResults[$question->id]['options'][] = [
                    'option' => $option,
                    'count' => $count,
                    'percentage' => $percentage,
                ];
            }
        }

        return view('survey-responses.results', compact('survey', 'surveyResponse', 'questionResults', 'totalResponses'));
    }
}
