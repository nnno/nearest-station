<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeoCoderService;
use App\Services\StationService;

/**
 * Class StationController
 * @package App\Http\Controllers
 */
class StationController extends Controller
{
    public function getStationsByAddress(Request $request)
    {
        $q = $request->get('q', null);

        if (empty($q)) {
            $res = ['errors' => ['Invalid request parameters.']];
            return response()->json($res, 200);
        }

        $srv_geoCoder = new GeoCoderService();
        $srv_station  = new StationService();

        $data = $srv_geoCoder->geoCode($q);
        if (empty($data)) {
            return api_ok([
                'count' => 0,
                'stations' => []
            ]);
        }

        $stations = $srv_station->getStationsByAddress($data['lat'], $data['lon']);

        $data = [];
        foreach($stations->unique('name') as $station) {
            $data[] = [
                'name' => $station['name'],
                'distance' => $station['distance'],
                'line' => $station['line'],
            ];
        }

        return api_ok([
            'count' => count($data),
            'stations' => $data,
        ]);
    }
}

