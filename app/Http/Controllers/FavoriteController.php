<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'anime_id' => 'required',
            'title' => 'nullable|string',
            'poster_url' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        $user = Auth::user();
        $favorite = $user->favorites()->where('anime_id', $request->anime_id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->create([
                'anime_id' => $request->anime_id,
                'title' => $request->title,
                'poster_url' => $request->poster_url,
                'type' => $request->type,
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
