<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'finished_at',
        'pipeline_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function nextStage(Card $card): ?int
    {
        $actualPipeline = $card->pipeline_id;
        $nextPipeline = Pipeline::where('pipeline_last_id', $actualPipeline)->first();
        if($nextPipeline){
             $card->pipeline_id = $nextPipeline->id;
             $card->save();
             return $nextPipeline->id;
        }else{
            $card->finished_at = now();
            $card->save();
            return null;
        }
    }


}
