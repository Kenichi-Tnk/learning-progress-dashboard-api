<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLearningProgressRequest;
use App\Http\Requests\UpdateLearningProgressRequest;
use App\Models\LearningProgress;
use Illuminate\Http\Request;

class LearningProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $progresses = LearningProgress::query()
            ->where('user_id', $request->user()->id)
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
        $validated['user_id'] = $request->user()->id;

        $progress = LearningProgress::create($validated);

        return response()->json($progress, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, LearningProgress $learningProgress)
    {
        abort_unless($learningProgress->user_id === $request->user()->id, 404);

        return response()->json($learningProgress);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLearningProgressRequest $request, LearningProgress $learningProgress)
    {
        abort_unless($learningProgress->user_id === $request->user()->id, 404);

        $validated = $request->validated();

        $learningProgress->update($validated);

        return response()->json($learningProgress);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, LearningProgress $learningProgress)
    {
        abort_unless($learningProgress->user_id === $request->user()->id, 404);

        $learningProgress->delete();

        return response()->json([], 204);
    }
}
