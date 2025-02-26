<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SearchHistory extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'departure', 'destination', 'departure_date', 'return_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

