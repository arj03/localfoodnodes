<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class GoogleMapsHelper
{
    private $geoCodingApi = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s';

    private $validAddressFields = ['address', 'zip', 'city', 'country'];

    /**
     * Get latitude and longitue from address.
     *
     * @param Array array containg address fields (address, zip, city, country)
     * @return Array array with latitude and longitude
     */
    public function getLatLng(Array $addressFields)
    {
        $addressUrl = implode('+', $addressFields);

        $client = new Client();
        $url = sprintf($this->geoCodingApi, urlencode($addressUrl), config('services.google.maps.geocoding'));
        $res = $client->request('GET', $url);
        $json = json_decode($res->getBody());

        if (isset($json->results[0]) && !empty($json->results[0])) {
            return $json->results[0]->geometry->location;
        } else {
            return ['lat' => 56, 'lng' => 13]; // Fallback
        }
    }

    /**
     * Get latitude and longitude as a string for db inserts.
     *
     * @param Array array containing address fields
     * @return string
     */
    public function getLatLngForDb(Array $addressFields)
    {
        $latLng = $this->getLatLng($addressFields);

        if (is_object($latLng)) {
            return $latLng->lat . ' ' . $latLng->lng;
        } else {
            return '56 13'; // Fallback
        }
    }
}
