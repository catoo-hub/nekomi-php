<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnilibertyService;
use Illuminate\Support\Facades\Auth;

use App\Models\AnimeProgress;

class AnimeController extends Controller
{
    protected $aniliberty;

    public function __construct(AnilibertyService $aniliberty)
    {
        $this->aniliberty = $aniliberty;
    }

    public function show($id)
    {
        $anime = null;
        try {
            $response = $this->aniliberty->getAnime($id);
            if (isset($response['data'])) {
                $anime = $response['data'];
            } elseif (isset($response['id'])) {
                $anime = $response;
            }
        } catch (\Exception $e) {
            // Handle error
        }

        // Mock data fallback
        if (!$anime) {
            $anime = [
                'id' => $id,
                'name' => ['main' => 'Frieren: Beyond Journey\'s End'],
                'description' => 'The Demon King has been defeated, and the victorious hero party returns home before disbanding. Witness the journey that begins after the adventure ends.',
                'poster' => ['url' => 'https://images5.alphacoders.com/133/1337432.jpeg'],
                'rating' => 9.8,
                'episodes' => [
                    ['ordinal' => 1, 'name' => 'The Journey\'s End', 'preview' => ['url' => 'https://via.placeholder.com/300x169']],
                    ['ordinal' => 2, 'name' => 'It Didn\'t Have to Be Magic', 'preview' => ['url' => 'https://via.placeholder.com/300x169']],
                    ['ordinal' => 3, 'name' => 'Killing Magic', 'preview' => ['url' => 'https://via.placeholder.com/300x169']],
                ],
                'players' => [
                    ['name' => 'Kodik', 'url' => 'https://kodik.cc/video/12345/67890/720p'] // Mock Kodik link
                ]
            ];
        }

        $isFavorite = false;
        $lastWatchedEpisodeId = null;

        if (Auth::check()) {
            $isFavorite = Auth::user()->favorites()->where('anime_id', $id)->exists();
            
            // Find last watched episode
            $lastProgress = AnimeProgress::where('user_id', Auth::id())
                ->where('anime_id', $id)
                ->orderBy('updated_at', 'desc')
                ->first();
                
            if ($lastProgress) {
                $lastWatchedEpisodeId = $lastProgress->episode_id;
            }
        }
        
        // If user has history, redirect to that episode
        if ($lastWatchedEpisodeId) {
             return redirect()->route('anime.episode', ['id' => $id, 'episodeId' => $lastWatchedEpisodeId]);
        }
        
        // If no history, but episodes exist, redirect to first episode
        if (isset($anime['episodes']) && count($anime['episodes']) > 0) {
             // Sort episodes by ordinal to find the first one
             $episodes = $anime['episodes'];
             usort($episodes, function($a, $b) {
                 return $a['ordinal'] <=> $b['ordinal'];
             });
             $firstEpisode = $episodes[0];
             return redirect()->route('anime.episode', ['id' => $id, 'episodeId' => $firstEpisode['id']]);
        }

        return view('anime.show', compact('anime', 'isFavorite'));
    }

    public function episode($id, $episodeId)
    {
        $anime = null;
        $currentEpisode = null;

        try {
            // Fetch anime details first to have context (episodes list, etc)
            $response = $this->aniliberty->getAnime($id);
            if (isset($response['data'])) {
                $anime = $response['data'];
            } elseif (isset($response['id'])) {
                $anime = $response;
            }

            // Find the specific episode in the anime's episode list or fetch separately if needed
            // For now, we'll assume we can just pass the ID to the view to construct the iframe URL
            // But we should verify it exists in the anime's episode list
            if ($anime && isset($anime['episodes'])) {
                foreach ($anime['episodes'] as $ep) {
                    if ($ep['id'] == $episodeId) {
                        $currentEpisode = $ep;
                        break;
                    }
                }
            }
            
            // If not found in list (maybe pagination?), try fetching directly if endpoint exists
            if (!$currentEpisode) {
                 $epResponse = $this->aniliberty->getEpisode($episodeId);
                 if (isset($epResponse['data'])) {
                     $currentEpisode = $epResponse['data'];
                 }
            }

        } catch (\Exception $e) {
            // Handle error
        }

        $isFavorite = false;
        $userProgress = null;
        $watchedEpisodes = [];

        if (Auth::check()) {
            $isFavorite = Auth::user()->favorites()->where('anime_id', $id)->exists();

            // Get progress for current episode
            $userProgress = AnimeProgress::where('user_id', Auth::id())
                ->where('anime_id', $id)
                ->where('episode_id', $episodeId)
                ->first();

            // Get list of completed episodes for this anime
            $watchedEpisodes = AnimeProgress::where('user_id', Auth::id())
                ->where('anime_id', $id)
                ->where('is_completed', true)
                ->pluck('episode_id')
                ->toArray();
        }

        return view('anime.show', compact('anime', 'isFavorite', 'currentEpisode', 'userProgress', 'watchedEpisodes'));
    }
}
