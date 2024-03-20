<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Repositories\CardRepository;
use Illuminate\Http\JsonResponse;

class CardController extends Controller
{
    private $cardRepository;

    public function __construct(CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    public function index(): JsonResponse
    {
        $cards = $this->cardRepository->all();
        return response()->json($cards);
    }

    public function store(StoreCardRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $data['pipeline_id'] = $this->cardRepository->getFirstPipelineId();
            $this->cardRepository->create($data);
            return response()->json(['message' => 'Card created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating card'], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        $card = $this->cardRepository->find($id);
        return $card ? response()->json($card) : response()->json(['message' => 'Card not found'], 404);
    }

    public function update(UpdateCardRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        try {
            $this->cardRepository->update($data, $id);
            return response()->json(['message' => 'Card updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating card'], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->cardRepository->delete($id);
            return response()->json(['message' => 'Card deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting card'], 400);
        }
    }

    public function nextStage(int $id): JsonResponse
    {
        $card = $this->cardRepository->find($id);
        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        $stage = $this->cardRepository->moveToNextStage($card);
        $message = $stage ? 'Card moved to next stage' : 'Card finished';
        return response()->json(['message' => $message, 'stage' => $stage], 200);
    }
}
