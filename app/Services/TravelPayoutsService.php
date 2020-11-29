<?php


namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class TravelPayoutsService
{
    const COUNTRIES_URL = 'http://api.travelpayouts.com/data/en/countries.json?token=';
    const CITIES_URL = 'http://api.travelpayouts.com/data/en/cities.json?token=';
//    const FLIGHT_URL = 'http://api.travelpayouts.com/v2/prices/latest?currency=usd&period_type=day&token=';
    const FLIGHT_URL = 'http://api.travelpayouts.com/v1/prices/calendar';

    public function getCountryList()
    {
        $client = new Client();
        $res = $client->request('GET', self::COUNTRIES_URL . env('TRAVEL_PAYOUTS_API_TOKEN'));
        return json_decode($res->getBody(), true);
    }

    public function getCitiesListByCountry($countryCode)
    {
        $client = new Client();
        $res = $client->request('GET', self::CITIES_URL . env('TRAVEL_PAYOUTS_API_TOKEN'));
        $cities = json_decode($res->getBody(), true);

        $filteredCities = Arr::where($cities, function ($value, $key) use ($countryCode) {
            return $value['country_code'] == $countryCode;
        });

        return $filteredCities;
    }

    public function getFlightPriceList($params)
    {
        $url = self::FLIGHT_URL;
        foreach ($params as $key => $param) {
            if($key == 'depart_date') {
                $url .= '?' . $key . '=' . $param;
            } else {
                $url .= '&' . $key . '=' . $param;
            }
        }
        $url .= '&token=' . env('TRAVEL_PAYOUTS_API_TOKEN');

        $client = new Client();
        $res = $client->request('GET', $url);
        return json_decode($res->getBody(), true);
    }
}
