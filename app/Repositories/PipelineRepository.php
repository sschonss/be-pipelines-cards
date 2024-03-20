<?php

namespace App\Repositories;

use App\Models\Pipeline;
use Illuminate\Support\Facades\Auth;

class PipelineRepository
{
    public function all()
    {
        return Pipeline::where('user_id', Auth::id())->get();
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        $data['pipeline_last_id'] = $this->getLastPipelineId();
        return Pipeline::create($data);
    }

    public function find(int $id)
    {
        return Pipeline::where('user_id', Auth::id())->findOrFail($id) ?? null;
    }

    public function update(array $data, Pipeline $pipeline)
    {
        $this->authorizePipelineOwnership($pipeline);
        $pipeline->update($data);
        return $pipeline;
    }

    public function delete(Pipeline $pipeline): void
    {
        $this->authorizePipelineOwnership($pipeline);
        $pipeline->delete();
    }

    private function getLastPipelineId(): ?int
    {
        return Pipeline::orderByDesc('id')->value('id');
    }

    private function authorizePipelineOwnership(Pipeline $pipeline): void
    {
        if ($pipeline->user_id !== Auth::id()) {
            throw new \InvalidArgumentException('User does not own this pipeline', 403);
        }
    }
}
