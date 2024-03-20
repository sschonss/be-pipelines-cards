<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Repositories\CardRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CardController extends Controller
{
    private $cardRepository;

    public function __construct(CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $cards = $this->cardRepository->all();
            return response()->json($cards);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving cards: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreCardRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $data['pipeline_id'] = $this->cardRepository->getFirstPipelineId();
            $this->cardRepository->create($data);
            return response()->json(['message' => 'Card created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating card: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $card = $this->cardRepository->find($id);
            if (!$card) {
                return response()->json(['message' => 'Card not found'], Response::HTTP_NOT_FOUND);
            }
            return response()->json($card);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving card: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCardRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        try {
            $this->cardRepository->update($data, $id);
            return response()->json(['message' => 'Card updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating card: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->cardRepository->delete($id);
            return response()->json(['message' => 'Card deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting card: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function nextStage(int $id): JsonResponse
    {
        try {
            $card = $this->cardRepository->find($id);
            if (!$card) {
                return response()->json(['message' => 'Card not found'], Response::HTTP_NOT_FOUND);
            }

            $stage = $this->cardRepository->moveToNextStage($card);
            $message = $stage ? 'Card moved to next stage' : 'Card finished';
            return response()->json(['message' => $message, 'stage' => $stage], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error moving card to next stage: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
