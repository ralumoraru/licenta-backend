<?php

namespace App\Http\Controllers;

use App\Models\SearchPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchPriceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'search_history_id' => 'required|integer|exists:search_histories,id',
            'prices' => 'required|array|min:1',
            'prices.*.price' => 'required|numeric',
        ]);
    
        foreach ($validated['prices'] as $priceData) {
            SearchPrice::create([
                'search_history_id' => $validated['search_history_id'],
                'price' => $priceData['price'],
               
            ]);
        }
    
        Log::info("Prices saved for search_history_id {$validated['search_history_id']}");
    
        return response()->json(['message' => 'Prices saved successfully'], 201);
    }

    
    
    
}
