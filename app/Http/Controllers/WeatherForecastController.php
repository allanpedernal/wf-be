<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class WeatherForecastController extends Controller
{
    public function getWeatherForecast(Request $request)
    {
        try
        {
            $request->validate([
                'city' => 'required|string|max:255'
            ]);

            $api_key = env('WEATHERMAP_API');
            $url = 'https://api.openweathermap.org/data/2.5/weather?q='.urlencode($request->city).'&cnt=7&appid='.$api_key;

            $client = new Client;
            $request = $client->get($url);
            $weather_forecast = json_decode($request->getBody()->getContents(), true);
            
            $weather_forecast['dt'] = Carbon::parse($weather_forecast['dt'])->format('m/d/Y');

            return \Response::json([
                'success'=>true,
                'weather_forecast'=>$weather_forecast
            ],200);
        }
        catch(\Exception $e)
        {
            return \Response::json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }
}
