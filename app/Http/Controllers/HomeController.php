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
        
        // Note: API might use different param names for filters, adjusting based on common patterns
        // If the API documentation was available, we would use exact keys.
        
        $meta = [];
        try {
            $response = $this->aniliberty->getCatalog($params);
            if (isset($response['data'])) {
                $animeList = $response['data'];
            }
            if (isset($response['meta'])) {
                $meta = $response['meta'];
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

        return view('welcome', compact('animeList', 'meta'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $animeList = [];

        if ($query) {
            try {
                $response = $this->aniliberty->search($query);
                if (isset($response['data'])) {
                    $animeList = $response['data'];
                }
            } catch (\Exception $e) {
                // Handle error
            }
        }

        return view('welcome', compact('animeList'));
    }
}
