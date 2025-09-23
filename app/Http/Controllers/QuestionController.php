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
            'options' => 'required|array|min:2|max:4',
            'options.0.text' => 'required|string|max:255',
            'options.1.text' => 'required|string|max:255',
            'options.*.text' => 'nullable|string|max:255',
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $question = $survey->questions()->create($request->only('text'));

        foreach ($request->options as $optionData) {
            if (!empty($optionData['text'])) {
                $optionDetails = ['text' => $optionData['text']];

                if (isset($optionData['image'])) {
                    $imagePath = $optionData['image']->store('options', 'public');
                    $optionDetails['image_path'] = $imagePath;
                }

                $question->options()->create($optionDetails);
            }
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
            'options' => 'required|array|min:2|max:4',
            'options.0.text' => 'required|string|max:255',
            'options.1.text' => 'required|string|max:255',
            'options.*.text' => 'nullable|string|max:255',
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $question->update($request->only('text'));

        $existingOptionIds = $question->options->pluck('id')->toArray();
        $submittedOptionIds = [];

        foreach ($request->options as $optionData) {
            $optionId = $optionData['id'] ?? null;

            if (!empty($optionData['text'])) {
                $optionDetails = ['text' => $optionData['text']];

                if (isset($optionData['image'])) {
                    $imagePath = $optionData['image']->store('options', 'public');
                    $optionDetails['image_path'] = $imagePath;
                }

                if ($optionId) {
                    // Update existing option
                    $option = $question->options()->find($optionId);
                    if ($option) {
                        if (isset($optionDetails['image_path']) && $option->image_path) {
                            Storage::disk('public')->delete($option->image_path);
                        }
                        $option->update($optionDetails);
                        $submittedOptionIds[] = $optionId;
                    }
                } else {
                    // Create new option
                    $newOption = $question->options()->create($optionDetails);
                    $submittedOptionIds[] = $newOption->id;
                }
            }
        }

        // Delete options that were removed
        $optionsToDelete = array_diff($existingOptionIds, $submittedOptionIds);
        foreach ($optionsToDelete as $optionId) {
            $option = $question->options()->find($optionId);
            if ($option) {
                if ($option->image_path) {
                    Storage::disk('public')->delete($option->image_path);
                }
                $option->delete();
            }
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
