<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * Class StationService
 * @package App\Services
 */
class StationService
{
    public function getStationsByAddress($lat, $lon)
    {
        $client = new Client(['base_uri' => 'http://express.heartrails.com']);
        $options = [
            'query' => [
                'method' => 'getStations',
                'x' => $lat,
                'y' => $lon,
            ]
        ];

        $ret = $client->get('/api/json', $options);
        $body = $ret->getBody();
        $body = json_decode($body, true);

        $stations = $body['response']['station'];

        return collect($stations);
    }
}
