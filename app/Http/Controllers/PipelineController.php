<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePipelineRequest;
use App\Http\Requests\UpdatePipelineRequest;
use App\Repositories\PipelineRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PipelineController extends Controller
{
    private $pipelineRepository;

    public function __construct(PipelineRepository $pipelineRepository)
    {
        $this->pipelineRepository = $pipelineRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $pipelines = $this->pipelineRepository->all();
            return response()->json($pipelines);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving pipelines: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StorePipelineRequest $request): JsonResponse
    {
        try {
            $this->pipelineRepository->create($request->validated());
            return response()->json(['message' => 'Pipeline created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating pipeline: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $pipeline = $this->pipelineRepository->find($id);
            if (!$pipeline) {
                return response()->json(['message' => 'Pipeline not found'], Response::HTTP_NOT_FOUND);
            }
            return response()->json($pipeline);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving pipeline: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdatePipelineRequest $request, int $id): JsonResponse
    {
        try {
            $pipeline = $this->pipelineRepository->find($id);
            if (!$pipeline) {
                return response()->json(['message' => 'Pipeline not found'], Response::HTTP_NOT_FOUND);
            }
            $this->pipelineRepository->update($request->validated(), $pipeline);
            return response()->json(['message' => 'Pipeline updated successfully'], Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating pipeline: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $pipeline = $this->pipelineRepository->find($id);
            if (!$pipeline) {
                return response()->json(['message' => 'Pipeline not found'], Response::HTTP_NOT_FOUND);
            }
            $this->pipelineRepository->delete($pipeline);
            return response()->json(['message' => 'Pipeline deleted successfully'], Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting pipeline: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
