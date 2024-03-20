<?php

namespace App\Repositories;

use App\Models\Card;
use App\Models\Pipeline;
use Illuminate\Support\Facades\Auth;

class CardRepository
{
    public function all()
    {
        return Card::where('user_id', Auth::id())->get();
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        return Card::create($data);
    }

    public function find(int $id)
    {
        return Card::where('user_id', Auth::id())->findOrFail($id);
    }

    public function update(array $data, Card $card): void
    {
        $this->authorizeCardOwnership($card);
        $card->update($data);
    }

    public function delete(Card $card): void
    {
        $this->authorizeCardOwnership($card);
        $card->delete();
    }

    public function getFirstPipelineId(): ?int
    {
        $pipeline = Pipeline::orderBy('id')->first();
        return $pipeline ? $pipeline->id : null;
    }

    public function moveToNextStage(Card $card): ?int
    {
        $nextPipeline = Pipeline::where('pipeline_last_id', $card->pipeline_id)->first();
        if ($nextPipeline) {
            $card->pipeline_id = $nextPipeline->id;
            $card->save();
            return $nextPipeline->id;
        } else {
            $card->finished_at = now();
            $card->save();
            return null;
        }
    }

    private function authorizeCardOwnership(Card $card): void
    {
        if ($card->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('User does not own this card');
        }
    }
}
