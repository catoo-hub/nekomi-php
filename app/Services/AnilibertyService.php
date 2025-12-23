<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnilibertyService
{
    protected $baseUrl = 'https://aniliberty.top/api/v1';

    public function getCatalog($params = [])
    {
        return Http::get("{$this->baseUrl}/anime/catalog/releases", $params)->json();
    }

    public function getAnime($id)
    {
        return Http::get("{$this->baseUrl}/anime/releases/{$id}")->json();
    }

    public function getEpisodes($id)
    {
        // Based on docs, episodes are included in release details or separate endpoint
        // /anime/releases/{idOrAlias}/episodes/timecodes seems to be for user progress
        // The release object has 'episodes' array.
        return $this->getAnime($id);
    }

    public function getEpisode($episodeId)
    {
        return Http::get("{$this->baseUrl}/anime/releases/episodes/{$episodeId}")->json();
    }
    
    public function search($params = [])
    {
        return Http::get("{$this->baseUrl}/anime/catalog/releases", $params)->json();
    }

    public function getSchedule()
    {
        return Http::get("{$this->baseUrl}/anime/schedule/week")->json();
    }
}
