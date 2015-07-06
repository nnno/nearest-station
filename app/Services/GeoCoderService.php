<?php

namespace App\Services;

use GuzzleHttp\Client;


/**
 * Class GeoCoderService
 * @package App\Services
 */
class GeoCoderService
{
    private $app_id = null;

    public function __construct()
    {
        $this->app_id = env('YAHOO_JP_APP_ID');
    }

    public function geoCode($query = '')
    {
        $client = new Client(['base_uri' => 'http://geo.search.olp.yahooapis.jp']);
        $options = [
            'headers' => ['User-Agent' => sprintf('Yahoo AppID: %s', $this->app_id),],
            'query' => [
                'output' => 'json',
                'query' => $query,
                'results' => 1,
            ],
        ];

        $ret = $client->get('/OpenLocalPlatform/V1/geoCoder', $options);
        $body = $ret->getBody();
        $body = json_decode($body, true);

        if ($body['ResultInfo']['Count'] < 1) {
            return [];
        }

        $ret = $body['Feature'][0];

        list($lat, $lon) = explode(',', $ret['Geometry']['Coordinates']);

        return [
            'lat' => $lat,
            'lon' => $lon
        ];
    }
}
