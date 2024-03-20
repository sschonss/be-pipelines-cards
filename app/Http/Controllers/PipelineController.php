<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePipelineRequest;
use App\Http\Requests\UpdatePipelineRequest;
use App\Models\Card;
use App\Models\Pipeline;

class PipelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Pipeline::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePipelineRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated();

        $request->merge(
            [
                'user_id' => $this->getIDUserAuth(),
                'pipeline_last_id' => (new Pipeline())->getIDLastPipeline(),
            ]
        );

        try {
            Pipeline::create($request->all());
            return response()->json(['message' => 'Pipeline created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating pipeline'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        if (!Pipeline::find($id)) {
            return response()->json(['message' => 'Pipeline not found'], 404);
        }

        return response()->json(Pipeline::find($id), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePipelineRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        $request->validated();
        $request->merge(
            [
                'user_id' => $this->getIDUserAuth(),
            ]
        );

        if (!Pipeline::find($id)) {
            return response()->json(['message' => 'Pipeline not found'], 404);
        }

        try {
            Pipeline::find($id)->update($request->all());
            return response()->json(['message' => 'Pipeline updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating pipeline'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        if (Card::where('pipeline_id', $id)->first()) {
            return response()->json(['message' => 'Pipeline is in use'], 400);
        }

        try {
            Pipeline::find($id)->delete();
            return response()->json(['message' => 'Pipeline deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting pipeline'], 400);
        }
    }
}
