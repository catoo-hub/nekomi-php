<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favorite;

class CollectionController extends Controller
{
    public function index()
    {
        $users = User::where('is_collection_public', true)
            ->whereHas('favorites')
            ->with(['favorites' => function($query) {
                $query->latest()->take(3);
            }])
            ->paginate(12);

        return view('collections.index', compact('users'));
    }

    public function show(User $user)
    {
        if (!$user->is_collection_public && auth()->id() !== $user->id) {
            abort(404);
        }

        $favorites = $user->favorites()->latest()->get();
        return view('collections.show', compact('user', 'favorites'));
    }

    public function getFavorites(User $user)
    {
        if (!$user->is_collection_public && auth()->id() !== $user->id) {
            return response()->json(['error' => 'Private collection'], 403);
        }

        $favorites = $user->favorites()->latest()->get();
        return response()->json($favorites);
    }
}
