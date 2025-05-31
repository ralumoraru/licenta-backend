<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::rename('flight_prices', 'search_prices');
    }
    
    public function down()
    {
        Schema::rename('search_prices', 'flight_prices');
    }
    
};
