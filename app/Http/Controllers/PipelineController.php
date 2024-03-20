<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePipelineRequest;
use App\Http\Requests\UpdatePipelineRequest;
use App\Repositories\PipelineRepository;
use Illuminate\Http\JsonResponse;

class PipelineController extends Controller
{
    private $pipelineRepository;

    public function __construct(PipelineRepository $pipelineRepository)
    {
        $this->pipelineRepository = $pipelineRepository;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->pipelineRepository->all());
    }

    public function store(StorePipelineRequest $request): JsonResponse
    {
        $this->pipelineRepository->create($request->validated());
        return response()->json(['message' => 'Pipeline created successfully'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $pipeline = $this->pipelineRepository->find($id);
        return response()->json($pipeline);
    }

    public function update(UpdatePipelineRequest $request, int $id): JsonResponse
    {
        $this->pipelineRepository->update($request->validated(), $id);
        return response()->json(['message' => 'Pipeline updated successfully'], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->pipelineRepository->delete($id);
        return response()->json(['message' => 'Pipeline deleted successfully'], 200);
    }
}
