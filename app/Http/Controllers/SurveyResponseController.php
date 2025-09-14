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

        return redirect()->route('surveys.report', $survey)
            ->with('success', 'Thank you for taking the survey!');
    }
}
