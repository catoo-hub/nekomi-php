<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnilibertyService;

class HomeController extends Controller
{
    protected $aniliberty;

    public function __construct(AnilibertyService $aniliberty)
    {
        $this->aniliberty = $aniliberty;
    }

    public function index(Request $request)
    {
        $animeList = [];
        $schedule = [];
        $params = [
            'limit' => 15,
            'page' => $request->get('page', 1),
        ];

        if ($request->has('sort')) {
            $params['f[sorting]'] = $request->get('sort');
        }

        if ($request->has('search')) {
            $params['f[search]'] = $request->get('search');
        }

        if ($request->has('q')) {
            $params['f[search]'] = $request->get('q');
        }
        
        $meta = [];
        try {
            $response = $this->aniliberty->getCatalog($params);
            if (isset($response['data'])) {
                $animeList = $response['data'];
            }
            if (isset($response['meta'])) {
                $meta = $response['meta'];
            }

            // Fetch Schedule
            $scheduleData = $this->aniliberty->getSchedule();
            if (is_array($scheduleData)) {
                $groupedSchedule = [];
                foreach ($scheduleData as $item) {
                    if (isset($item['release']['publish_day'])) {
                        $dayValue = $item['release']['publish_day']['value'];
                        $dayName = $item['release']['publish_day']['description'];
                        
                        if (!isset($groupedSchedule[$dayValue])) {
                            $groupedSchedule[$dayValue] = [
                                'day' => ['name' => $dayName, 'value' => $dayValue],
                                'releases' => []
                            ];
                        }
                        
                        // Add the release to the list, using the release object directly as the view expects
                        $release = $item['release'];
                        $release['next_episode'] = $item['next_release_episode_number'] ?? null;
                        $groupedSchedule[$dayValue]['releases'][] = $release;
                    }
                }
                
                // Sort by day value (1-7)
                ksort($groupedSchedule);
                $schedule = array_values($groupedSchedule);
            }
        } catch (\Exception $e) {
            // Log error if needed
        }

        // Mock data fallback if API fails or returns empty
        if (empty($animeList)) {
            for ($i = 1; $i <= 15; $i++) {
                $animeList[] = [
                    'id' => $i,
                    'name' => ['main' => "Anime Title $i"],
                    'poster' => ['url' => "https://ui-avatars.com/api/?name=Anime+$i&background=random&size=500&length=1&font-size=0.33"],
                    'rating' => rand(70, 99) / 10,
                    'type' => ['label' => 'TV Series'],
                    'episodes' => ['count' => 12] 
                ];
            }
        }

        return view('welcome', compact('animeList', 'meta', 'schedule'));
    }

    public function search(Request $request)
    {
        $animeList = [];
        $schedule = [];
        $params = [
            'limit' => 15,
            'page' => $request->get('page', 1),
        ];

        if ($request->has('sort')) {
            $params['f[sorting]'] = $request->get('sort');
        }

        if ($request->has('q')) {
            $params['f[search]'] = $request->get('q');
        }
        
        $meta = [];
        try {
            $response = $this->aniliberty->search($params);
            if (isset($response['data'])) {
                $animeList = $response['data'];
            }
            if (isset($response['meta'])) {
                $meta = $response['meta'];
            }
        } catch (\Exception $e) {
            // Log error if needed
        }

        return view('welcome', compact('animeList', 'meta', 'schedule'));
    }
}
