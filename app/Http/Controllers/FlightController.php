<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    public function getIataCode(Request $request)
    {
        $city = $request->query('city');
        $iataCode = $request->query('iata_code');
    
        if ($city) {
            // Căutăm orașul în funcție de numele aeroportului
            $cityData = DB::table('cities')
                    ->whereRaw('LOWER(airport_name) LIKE ?', [strtolower("%$city%")])
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
    
    
    public function getAirportSuggestions(Request $request)
    {
        $query = $request->query('query');
    
        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }
    
        // Căutare aeroporturi care conțin textul introdus de utilizator
        $airports = DB::table('cities')
            ->whereRaw('LOWER(airport_name) LIKE ?', [strtolower("%$query%")])  // Folosește airport_name pentru căutare
            ->limit(10)
            ->get();
    
        if ($airports->isEmpty()) {
            return response()->json([]);
        }
    
        return response()->json($airports);
    }


public function getAirportsForCity(Request $request)
{
    $city = $request->query('city');

    if (!$city) {
        return response()->json(['error' => 'City parameter is required'], 400);
    }

    // Căutăm toate aeroporturile care sunt asociate cu acest oraș
    $airports = DB::table('cities')
        ->whereRaw('LOWER(city_name) LIKE ?', [strtolower("%$city%")])
        ->get(['airport_name']);  // Alegem doar coloana cu numele aeroportului

    if ($airports->isEmpty()) {
        return response()->json(['error' => 'No airports found for the city'], 404);
    }
    Log::info('Airports found:', $airports->toArray());

    return response()->json($airports);
}

    


}
