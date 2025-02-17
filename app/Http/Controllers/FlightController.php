<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    public function getIataCode(Request $request)
{
    $city = $request->query('city');
    $iataCode = $request->query('iata_code');

    if ($city) {
        $cityData = DB::table('cities')
                ->whereRaw('LOWER(city_name) LIKE ?', [strtolower("%$city%")])
                ->first();


        if ($cityData) {
            return response()->json([
                'iata_code' => $cityData->iata_code
            ]);
        } else {
            return response()->json(['error' => 'City not found'], 404);
        }
    } elseif ($iataCode) {
        return response()->json([
            'iata_code' => $iataCode
        ]);
    }

    return response()->json(['error' => 'No city or iata_code provided'], 400);
}

}
