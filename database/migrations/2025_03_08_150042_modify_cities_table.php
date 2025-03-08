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
        $table->string('iata_code')->nullable(false)->change();
        $table->string('airport_name')->nullable(false)->change();
        $table->string('city_name')->nullable()->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            
        });
    }
};
