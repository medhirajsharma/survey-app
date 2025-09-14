<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;

class SurveyResponseController extends Controller
{
    public function create(Survey $survey)
    {
        return view('survey-responses.create', compact('survey'));
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
            'answers' => 'required|array',
            'answers.*' => 'required|exists:options,id',
        ]);

        $surveyResponse = $survey->surveyResponses()->create($request->only('name'));

        foreach ($request->answers as $questionId => $optionId) {
            $surveyResponse->answers()->create([
                'question_id' => $questionId,
                'option_id' => $optionId,
            ]);
        }

        return redirect()->route('public.surveys.take', $survey)
            ->withCookie(cookie($cookieName, true, 60 * 24 * 365)) // Set cookie for 1 year
            ->with('success', 'Thank you for taking the survey!');
    }
}
