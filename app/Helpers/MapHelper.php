<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Node\Node;
use App\Event\Event;
use App\Helpers\DistanceHelper;

class MapHelper
{
    /**
     * [getNodesByBounds description]
     * @param  [type] $bounds [description]
     * @return [type]         [description]
     */
    public function getNodesByBounds($bounds)
    {
        $minX = $bounds['sw']['lat'];
        $minY = $bounds['sw']['lng'];
        $maxX = $bounds['ne']['lat'];
        $maxY = $bounds['ne']['lng'];

        $polygon = "POLYGON(($minX $minY, $maxX $minY, $maxX $maxY, $minX $maxY, $minX $minY))";
        $polygonQuery = 'ST_CONTAINS(geomfromtext("' . $polygon . '"), `location`)';

        $nodeIds = DB::table('nodes')
        ->select('id')
        ->whereRaw($polygonQuery)
        ->get();

        // Nodes found inside bounds
        if ($nodeIds->count() > 0) {
            return Node::whereIn('id', $nodeIds->pluck('id'))->get();
        }

        $centerX = ($minX + $maxX) / 2;
        $centerY = ($minY + $maxY) / 2;

        $nodeId = DB::table('nodes')
        ->select(DB::raw(
            'id, (
                6371 * acos(
                  cos(radians(?))
                  * cos(radians(X(location)))
                  * cos(radians(Y(location)) - radians(?))
                  + sin(radians(?))
                  * sin(radians(X(location)))
                )
            ) AS distance'
        ))
        ->orderBy('distance', 'asc')
        ->limit(1)
        ->setBindings([$centerX, $centerY, $centerX])
        ->get();

        // Closest node to center
        if ($nodeId->count() > 0) {
            return Node::whereIn('id', $nodeId->pluck('id'))->get();
        }

        return collect([]);
    }
}
