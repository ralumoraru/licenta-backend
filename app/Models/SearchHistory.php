<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SearchHistory extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'departure', 'destination', 'departure_date','arrival_departure_date', 'return_date', 'arrival_return_date'];

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
{
    return $this->hasMany(Favorite::class, 'search_id');
}

// SearchHistory.php
public function prices()
{
    return $this->hasMany(SearchPrice::class, 'search_history_id');
}






public function isFavoritedBy($userId)
{
    return $this->favorites()->where('user_id', $userId)->exists();
}


}

