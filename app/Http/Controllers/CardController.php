<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use App\Models\Pipeline;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Card::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCardRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated();

        try {
            $request->merge(
                [
                    'user_id' => $this->getIDUserAuth(),
                    'pipeline_id' => (new Pipeline())->getIDFirstPipeline(),
                ]
            );
        }catch (\Exception $e){
            return response()->json(['message' => 'Error getting first pipeline'], 400);
        }


        try {
            Card::create($request->all());
            return response()->json(['message' => 'Card created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating card'], 400);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        if (!Card::find($id)) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        return response()->json(Card::find($id), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        $request->validated();

        $request->merge(
            [
                'user_id' => $this->getIDUserAuth(),
            ]
        );

        $card = Card::find($id);
        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        $card->update($request->all());

        return response()->json(['message' => 'Card updated successfully'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            Card::destroy($id);
            return response()->json(['message' => 'Card deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting card'], 400);
        }
    }

    public function nextStage(int $id): \Illuminate\Http\JsonResponse
    {
        $card = Card::find($id);
        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        $stage = $card->nextStage($card);
        if($stage){
            return response()->json(['message' => 'Card moved to next stage', 'stage' => $stage], 200);
        }else{
            return response()->json(['message' => 'Card finished', 'stage' => null], 200);
        }
    }
}
