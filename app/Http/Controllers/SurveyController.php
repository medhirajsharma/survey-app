<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Vidhansabha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::paginate(10);
        return view('surveys.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vidhansabhas = Vidhansabha::all();
        return view('surveys.create', compact('vidhansabhas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_image'       => 'nullable|image|max:2048', // Max 2MB
            'vidhansabha_id'   => 'nullable|exists:vidhansabhas,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('meta_image')) {
            $data['meta_image'] = $request->file('meta_image')->store('surveys', 'public');
        }

        Survey::create($data);

        return redirect()->route('surveys.index')
            ->with('success', 'Survey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey)
    {
        return view('surveys.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        $vidhansabhas = Vidhansabha::all();
        return view('surveys.edit', compact('survey', 'vidhansabhas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_image'       => 'nullable|image|max:2048', // Max 2MB
            'vidhansabha_id'   => 'nullable|exists:vidhansabhas,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('meta_image')) {
            // Delete old image if exists
            if ($survey->meta_image) {
                Storage::disk('public')->delete($survey->meta_image);
            }
            $data['meta_image'] = $request->file('meta_image')->store('surveys', 'public');
        }

        $survey->update($data);

        return redirect()->route('surveys.index')
            ->with('success', 'Survey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()->route('surveys.index')
            ->with('success', 'Survey deleted successfully.');
    }
}
