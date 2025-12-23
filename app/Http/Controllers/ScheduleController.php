<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnilibertyService;

class ScheduleController extends Controller
{
    protected $aniliberty;

    public function __construct(AnilibertyService $aniliberty)
    {
        $this->aniliberty = $aniliberty;
    }

    public function index()
    {
        $schedule = [];
        try {
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
                        
                        $release = $item['release'];
                        $release['next_episode'] = $item['next_release_episode_number'] ?? null;
                        $groupedSchedule[$dayValue]['releases'][] = $release;
                    }
                }
                
                ksort($groupedSchedule);
                $schedule = array_values($groupedSchedule);
            }
        } catch (\Exception $e) {
            // Log error
        }

        return view('schedule.index', compact('schedule'));
    }
}
