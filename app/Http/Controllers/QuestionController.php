<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Survey $survey)
    {
        return view('questions.create', compact('survey'));
    }

    public function store(Request $request, Survey $survey)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'options' => 'required|array|min:4|max:4',
            'options.*.text' => 'required|string|max:255',
        ]);

        $question = $survey->questions()->create($request->only('text'));

        $question->options()->createMany($request->options);

        return redirect()->route('surveys.show', $survey)
            ->with('success', 'Question added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Question $question)
    {
        return view('questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'options' => 'required|array|min:4|max:4',
            'options.*.text' => 'required|string|max:255',
        ]);

        $question->update($request->only('text'));

        foreach ($request->options as $key => $optionData) {
            $question->options[$key]->update($optionData);
        }

        return redirect()->route('surveys.show', $question->survey)
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $survey = $question->survey;
        $question->delete();

        return redirect()->route('surveys.show', $survey)
            ->with('success', 'Question deleted successfully.');
    }
}
