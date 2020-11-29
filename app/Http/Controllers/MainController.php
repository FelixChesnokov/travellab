<?php

namespace App\Http\Controllers;

use App\Services\TravelPayoutsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MainController extends Controller
{
    public function index()
    {
        $travelPayoutsService = new TravelPayoutsService();

        $countries = $travelPayoutsService->getCountryList();

        return view('welcome', [
            'countries' => $countries
        ]);
    }

    public function generateTravel(Request $request)
    {
        $travelPayoutsService = new TravelPayoutsService();

        $countryName = $request->get('country');
        $countries = $travelPayoutsService->getCountryList();
        $country = Arr::where($countries, function ($value, $key) use ($countryName) {
            return $value['name'] == $countryName;
        });


        $cityName = $request->get('city');
        $cities = $travelPayoutsService->getCitiesListByCountry(array_shift($country)['code']);
        $city = Arr::where($cities, function ($value, $key) use ($cityName) {
            return $value['name'] == $cityName;
        });

        $params = [
            'depart_date' => Carbon::parse($request->get('start_date'))->format('Y-m'),
            'origin' => 'IEV',
            'destination' => array_shift($city)['code'],
            'calendar_type' => 'departure_date',
            'return_date' => Carbon::parse($request->get('end_date'))->format('Y-m'),
            'currency' => 'USD'
        ];


        $flight = $travelPayoutsService->getFlightPriceList($params);
//        die(var_dump(array_shift($flight['data'])));
        return view('flight', [
            'flights' => $flight['data']
        ]);
    }






    public function getCitiesData($countryCode)
    {
        $travelPayoutsService = new TravelPayoutsService();
        $cities = $travelPayoutsService->getCitiesListByCountry($countryCode);
        return json_encode([
            'status' => 200,
            'cities' => $cities
        ]);
    }
}
