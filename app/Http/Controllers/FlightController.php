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

    // Căutare după numele aeroportului, numele orașului sau codul IATA
    $airports = DB::table('cities')
        ->whereRaw('LOWER(airport_name) LIKE ?', [strtolower("%$query%")])
        ->orWhereRaw('LOWER(city_name) LIKE ?', [strtolower("%$query%")])
        ->orWhereRaw('LOWER(iata_code) LIKE ?', [strtolower("%$query%")])
        ->limit(10)
        ->get(['airport_name', 'iata_code', 'city_name']);

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

// Funcție pentru a obține numele aeroportului pe baza codului IATA
    public function getAirportNameByIataCode(Request $request)
    {
        $iataCode = $request->query('iata_code');

        if (!$iataCode) {
            return response()->json(['error' => 'IATA code parameter is required'], 400);
        }

        // Căutăm aeroportul în funcție de codul IATA
        $airport = DB::table('cities')
            ->where('iata_code', strtoupper($iataCode))  // Asigură-te că IATA este în majuscule
            ->first();

        if ($airport) {
            return response()->json([
                'airport_name' => $airport->airport_name,
                'iata_code' => $airport->iata_code,
                'city_name' => $airport->city_name,
            ]);
        } else {
            return response()->json(['error' => 'Airport not found for IATA code'], 404);
        }
    }

    


}
