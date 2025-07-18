<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->dateTime('departure_date')->change();
            $table->dateTime('return_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->date('departure_date')->change();
            $table->date('return_date')->nullable()->change();
        });
    }
};
