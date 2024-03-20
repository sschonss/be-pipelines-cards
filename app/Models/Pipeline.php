<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pipeline extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'pipeline_last_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIDLastPipeline(): ?int
    {
        return $this->orderBy('id', 'desc')->first()->id ?? null;
    }

    /**
     * @throws \Exception
     */
    public function getIDFirstPipeline(): int
    {
        try {
          return  $this->orderBy('id', 'asc')->first()->id;
        } catch (\Exception $e) {
            throw new \Exception('Error getting first pipeline');
        }
    }
}
