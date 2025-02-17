<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->string('iata_code')->primary(); // coloana pentru IATA
            $table->string('city_name');           // coloana pentru numele orașului
            $table->timestamps();                  // pentru created_at și updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
