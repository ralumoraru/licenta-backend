<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Șterge tabelul `cities`
        Schema::dropIfExists('cities');
    }

    public function down()
    {
        // Re-crează tabelul `cities` în caz de rollback
        Schema::create('cities', function (Blueprint $table) {
            $table->string('iata_code')->primary();
            $table->string('city_name');
            $table->timestamps();
        });
    }

};
