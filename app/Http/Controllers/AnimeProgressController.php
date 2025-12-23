<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AnimeProgress;
use Illuminate\Support\Facades\Auth;

class AnimeProgressController extends Controller
{
    public function saveProgress(Request $request)
    {
        $request->validate([
            'anime_id' => 'required',
            'episode_id' => 'required',
            'episode_number' => 'required|numeric',
            'time_watched' => 'required|numeric',
            'duration' => 'required|numeric',
        ]);

        $user = Auth::user();

        $isCompleted = false;
        if ($request->duration > 0) {
            $isCompleted = $request->time_watched >= ($request->duration * 0.9);
        }

        $progress = AnimeProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'episode_id' => $request->episode_id,
            ],
            [
                'anime_id' => $request->anime_id,
                'episode_number' => $request->episode_number,
                'time_watched' => $request->time_watched,
                'duration' => $request->duration,
                'is_completed' => $isCompleted,
            ]
        );

        return response()->json(['status' => 'success', 'progress' => $progress]);
    }
}
