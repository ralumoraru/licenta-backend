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
        Schema::table('search_prices', function (Blueprint $table) {
            $table->dropColumn([
                'departure_time',
                'departure_arrival_time',
                'return_time',
                'return_arrival_time',
            ]);
        });
    }

    public function down()
    {
        Schema::table('search_prices', function (Blueprint $table) {
            $table->dateTime('departure_time')->nullable();
            $table->dateTime('departure_arrival_time')->nullable();
            $table->dateTime('return_time')->nullable();
            $table->dateTime('return_arrival_time')->nullable();
        });
    }
};
