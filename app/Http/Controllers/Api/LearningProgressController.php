<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningProgress;
use Illuminate\Http\Request;

class LearningProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $progresses = LearningProgress::query()
            ->latest('updated_at')
            ->get();

        return response()->json($progresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:not_started,in_progress,completed'],
            'memo' => ['nullable', 'string'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ]);

        $progress = LearningProgress::create($validated);

        return response()->json($progress, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningProgress $learningProgress)
    {
        return response()->json($learningProgress);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LearningProgress $learningProgress)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:not_started,in_progress,completed'],
            'memo' => ['nullable', 'string'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ]);

        $learningProgress->update($validated);

        return response()->json($learningProgress);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningProgress $learningProgress)
    {
        $learningProgress->delete();

        return response()->json([], 204);
    }
}
