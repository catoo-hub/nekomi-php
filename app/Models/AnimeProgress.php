<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeProgress extends Model
{
    protected $table = 'anime_progress';

    protected $fillable = [
        'user_id',
        'anime_id',
        'episode_id',
        'episode_number',
        'time_watched',
        'duration',
        'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
