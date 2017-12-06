<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\MapHelper;
use App\Node\Reko;

class MapController extends Controller
{
    /**
     * Get all map contents.
     *
     * Initial load: show x nodes closest to user location
     * On search: show the (one) node closest to searched location
     * On zoom: Load nodes within bountries
     *
     * @param Request $request
     * @return array
     */
    public function getMapContent(Request $request)
    {
        $mapHelper = new MapHelper();
        $nodes = $this->getNodes($request, $mapHelper);
        $reko = $this->getReko();

        return response()->json([
            'nodes' => $nodes,
            'reko' => $reko,
        ]);
    }

    /**
     * Get nodes.
     *
     * @param Request $request
     * @param MapHelper $mapHelper
     * @return Collection
     */
    private function getNodes(Request $request, $mapHelper)
    {
        $nodes = null;

        if ($request->has('bounds')) {
            $bounds = $request->input('bounds');
            $nodes = $mapHelper->getNodesByBounds($bounds);
        }

        $nodes = $nodes->each(function($node) {
            return $node->loadMapData();
        });

        return $nodes;
    }

    /**
     * Get reko.
     *
     * @return Collection
     */
    private function getReko()
    {
        return Reko::all();
    }
}
