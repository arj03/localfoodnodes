<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DistanceHelper
{
    /**
     * Get average producer distance description]
     * @param  [type] $nodes [description]
     * @return [type]        [description]
     */
    public function getDistance($fromLocation, $toLocation)
    {
        $distance = 6371 * acos(
            cos(deg2rad($fromLocation['lat']))
            * cos(deg2rad($toLocation['lat']))
            * cos(deg2rad($toLocation['lng']) - deg2rad($fromLocation['lng']))
            + sin(deg2rad($fromLocation['lat']))
            * sin(deg2rad($toLocation['lat']))
        );

        return round($distance, 0);
    }
}
