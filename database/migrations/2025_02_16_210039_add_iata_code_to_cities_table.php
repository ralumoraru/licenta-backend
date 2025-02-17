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
            $table->string('iata_code', 10)->nullable()->after('id'); // Adaugă coloana 'iata_code' după 'id'
        });
    }
    
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('iata_code');
        });
    }
    
};
