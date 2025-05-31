<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'search_history_id',
        'price',
    ];

    // Definește relația inversă cu SearchHistory
    public function searchHistory()
    {
        return $this->belongsTo(SearchHistory::class);
    }
}
