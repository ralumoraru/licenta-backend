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
            // Modifică `country_code` pentru a fi nullable
            $table->string('country_code')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            // Revert la starea inițială, dacă ai vrea să-l faci obligatoriu din nou
            $table->string('country_code')->nullable(false)->change();
        });
    }
};
