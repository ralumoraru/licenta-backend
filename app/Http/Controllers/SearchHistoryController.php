<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Log;

class SearchHistoryController extends Controller
{
    // Salvează căutarea utilizatorului conectat
    public function store(Request $request)
{
    $user = Auth::user();
    Log::info('Authenticated User:', ['user' => $user]);  // ✅ Verificăm user-ul
    Log::info('Request Data:', $request->all());  // ✅ Verificăm ce date ajung

    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $request->validate([
        'departure' => 'required|string',
        'destination' => 'required|string',
        'departure_date' => 'required|date',
        'return_date' => 'nullable|date',
    ]);

    Log::info('Validation Passed!');

    $history = new SearchHistory([
        'user_id' => $user->id,
        'departure' => $request->departure,
        'destination' => $request->destination,
        'departure_date' => $request->departure_date,
        'return_date' => $request->return_date,
    ]);

    Log::info('Data before saving:', ['history' => $history]);

    $history->save();

    Log::info('Search history saved successfully!');

    return response()->json(['message' => 'Search history saved successfully'], 201);
}
    // Obține istoricul căutărilor pentru utilizatorul conectat
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $history = DB::table('search_histories')
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
}
