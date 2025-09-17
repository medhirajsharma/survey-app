<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $question = $survey->questions()->create($request->only('text'));

        foreach ($request->options as $optionData) {
            $optionDetails = ['text' => $optionData['text']];

            if (isset($optionData['image'])) {
                $imagePath = $optionData['image']->store('options', 'public');
                $optionDetails['image_path'] = $imagePath;
            }

            $question->options()->create($optionDetails);
        }

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
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $question->update($request->only('text'));

        foreach ($request->options as $key => $optionData) {
            $option = $question->options[$key];
            $optionDetails = ['text' => $optionData['text']];

            if (isset($optionData['image'])) {
                // Delete old image
                if ($option->image_path) {
                    Storage::disk('public')->delete($option->image_path);
                }
                $imagePath = $optionData['image']->store('options', 'public');
                $optionDetails['image_path'] = $imagePath;
            }

            $option->update($optionDetails);
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
