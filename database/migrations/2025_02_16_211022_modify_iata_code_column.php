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
        Schema::table('cities', function (Blueprint $table) {
            $table->string('iata_code', 100)->change();  // Modifică dimensiunea coloanei
        });
    }

    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('iata_code', 3)->change();  // Revine la dimensiunea inițială
        });
    }
};
