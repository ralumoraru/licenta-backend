<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run()
    {
        // Citește fișierul CSV
        $csv = Reader::createFromPath(storage_path('app\public\csv\optd_por_public.csv'), 'r');
        $csv->setDelimiter('^');  // Setează delimiterul pentru fișierul CSV
        $csv->setHeaderOffset(0);  // Sari peste header-ul CSV-ului

        foreach ($csv as $row) {
            $iataCode = $row['iata_code'];
            $cityName = $row['name'];
        
            // Verifică dacă iata_code și city_name nu sunt goale
            if (!DB::table('cities')->where('iata_code', $iataCode)->exists()) {
                // Adaugă datele în tabelul cities
                DB::table('cities')->insert([
                    'iata_code' => $iataCode,
                    'city_name' => $cityName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }        
}

