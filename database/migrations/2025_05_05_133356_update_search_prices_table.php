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
        if (Schema::hasColumn('search_prices', 'arrival_time')) {
            Schema::table('search_prices', function (Blueprint $table) {
                $table->dropColumn('arrival_time');
            });
        }
    
        Schema::table('search_prices', function (Blueprint $table) {
            $table->dateTime('departure_arrival_time')->nullable()->after('departure_time');
            $table->dateTime('return_time')->nullable()->after('departure_arrival_time');
            $table->dateTime('return_arrival_time')->nullable()->after('return_time');
        });
    }
    
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
