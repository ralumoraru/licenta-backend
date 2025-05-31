<?php

namespace App\Http\Controllers;

use App\Models\SearchPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Log;

class SearchHistoryController extends Controller
{
    // Salvează căutarea utilizatorului conectatuse App\Models\SearchPrice;

public function store(Request $request)
{
    $userId = $request->user_id;

    Log::info('User ID:', ['userId' => $userId]);

    if (!$userId) {
        return response()->json(['error' => 'User ID missing'], 400);
    }

    Log::info('Authenticated User:', ['user' => $userId]);
    Log::info('Request Data:', $request->all());

    $request->validate([
        'departure' => 'required|string',
        'destination' => 'required|string',
        'departure_date' => 'required|date',
        'arrival_departure_date' => 'required|date',
        'return_date' => 'nullable|date',
        'arrival_return_date' => 'nullable|date',
        'prices' => 'nullable|array',
        'prices.*.airline' => 'required_with:prices|string',
        'prices.*.price' => 'required_with:prices|numeric',
        'prices.*.departure_time' => 'required_with:prices|date',
        'prices.*.arrival_time' => 'required_with:prices|date',
    ]);

    $exists = SearchHistory::where('user_id', $userId)
        ->where('departure', $request->departure)
        ->where('destination', $request->destination)
        ->where('departure_date', $request->departure_date)
        ->where('arrival_departure_date', $request->arrival_departure_date)
        ->when($request->filled('return_date'), fn($query) => $query->where('return_date', $request->return_date), fn($query) => $query->whereNull('return_date'))
        ->when($request->filled('arrival_return_date'), fn($query) => $query->where('arrival_return_date', $request->arrival_return_date), fn($query) => $query->whereNull('arrival_return_date'))
        ->exists();

    if ($exists) {
        return response()->json(['message' => 'Search already exists'], 409);
    }

    $history = SearchHistory::create([
        'user_id' => $userId,
        'departure' => $request->departure,
        'destination' => $request->destination,
        'departure_date' => $request->departure_date,
        'arrival_departure_date' => $request->arrival_departure_date,
        'return_date' => $request->return_date,
        'arrival_return_date' => $request->arrival_return_date,
    ]);

    // Salvează prețurile dacă există, fără dubluri
if (is_array($request->prices) && !empty($request->prices)) {
    foreach ($request->prices as $priceData) {
        $alreadyExists = SearchPrice::where('search_history_id', $history->id)
            ->where('price', $priceData['price'])
            ->exists();

        if (!$alreadyExists) {
            SearchPrice::create([
                'search_history_id' => $history->id,
                'price' => $priceData['price'],
            ]);
        }
    }
    Log::info('Flight prices saved successfully (no duplicates).');
}


    return response()->json([
        'message' => 'Search history and prices saved successfully',
        'search_history_id' => $history->id
    ], 200);
}

    // Obține istoricul căutărilor pentru utilizatorul conectat
    public function index(Request $request)
    {
        $user = $request->user();
    
        // Obține toate căutările istorice cu prețurile asociate
        $history = SearchHistory::with('prices') // încarcă prețurile cu relația definită
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json($history);
    }
    

    // Șterge o căutare specifică
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $deleted = DB::table('search_histories')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Search deleted successfully']);
        } else {
            return response()->json(['error' => 'Search not found'], 404);
        }
    }

    // Caută în istoricul utilizatorului conectat după parametrii specificați
    public function search(Request $request)
    {
        Log::info('Searching for flights with query:', [
            'userId' => $request->userId,
            'departure' => $request->departure,
            'destination' => $request->destination,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date
        ]); 

        $query = DB::table('search_histories')
            ->where('user_id', $request->userId);
    
        if ($request->filled('departure')) {
            $query->where('departure', 'like', '%' . $request->departure . '%');
        }
    
        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }
    
        if ($request->filled('departure_date')) {
            // Extragem doar partea de dată (fără ora) din 'departure_date' și o comparăm cu data de plecare din query
            $departureDate = \Carbon\Carbon::parse($request->departure_date)->format('Y-m-d');
            $query->whereDate('departure_date', '=', $departureDate);
        }
    
        if ($request->filled('return_date')) {
            // Extragem doar partea de dată (fără ora) din 'return_date' și o comparăm cu return date din query
            $returnDate = \Carbon\Carbon::parse($request->return_date)->format('Y-m-d');
            $query->whereDate('return_date', '=', $returnDate);
        }
    
        $results = $query->get();
    
        return response()->json([
            'exists' => $results->isNotEmpty(),
            'flights' => $results,
        ]);
    }

    // În SearchHistoryController
    public function delete(Request $request)
    {
        // Autentifică utilizatorul
        $userId = $request->user_id; // Este deja ID-ul, nu obiectul User

        Log::info('User ID:', ['userId' => $userId]);
    
        if (!$userId) {
            return response()->json(['error' => 'User ID missing'], 400);
        }
    
        // Validarea cererii
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'departure' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'arrival_departure_date' => 'required|date',
            'return_date' => 'nullable|date',
            'arrival_return_date' => 'nullable|date',
        ]);
    
        // Verificăm dacă intrarea există și o ștergem
        $deleted = DB::table('search_histories')
            ->where('user_id', $validated['user_id'])
            ->where('departure', $validated['departure'])
            ->where('destination', $validated['destination'])
            ->where('departure_date', $validated['departure_date'])
            ->where('arrival_departure_date', $validated['arrival_departure_date'])
            ->when($validated['return_date'], function ($query) use ($validated) {
                $query->where('return_date', $validated['return_date']);
            })
            ->when($validated['arrival_return_date'], function ($query) use ($validated) {
                $query->where('arrival_return_date', $validated['arrival_return_date']);
            })
            ->delete();
    
        // Verificăm dacă ștergerea a avut succes
        if ($deleted) {
            return response()->json(['message' => 'Search deleted successfully']);
        } else {
            return response()->json(['error' => 'Search not found'], 404);
        }
    }
    

    public function getLastPrice($id)
{
    $price = SearchPrice::where('search_history_id', $id)
        ->orderByDesc('created_at')
        ->value('price');

    return response()->json(['price' => $price]);
}

    
}
