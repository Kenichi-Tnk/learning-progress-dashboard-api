<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLearningProgressRequest;
use App\Http\Requests\UpdateLearningProgressRequest;
use App\Models\LearningProgress;

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
    public function store(StoreLearningProgressRequest $request)
    {
        $validated = $request->validated();

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
    public function update(UpdateLearningProgressRequest $request, LearningProgress $learningProgress)
    {
        $validated = $request->validated();

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
