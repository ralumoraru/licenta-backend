<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CitySeeder extends Seeder
{
    public function run()
    {
         // Șterge toate datele din tabelul 'cities'
         DB::table('cities')->truncate();
        // Citește fișierul CSV
        $csv = Reader::createFromPath(storage_path('app\public\csv\optd_por_public.csv'), 'r');
        $csv->setDelimiter('^');  // Setează delimiterul pentru fișierul CSV
        $csv->setHeaderOffset(0);  // Sari peste header-ul CSV-ului

        foreach ($csv as $row) {
            $iataCode = $row['iata_code'];
            $airportName = $row['name'];
            $cityNameList = $row['city_name_list'];
        
            // Împărțirea city_name_list într-un array
            $cityNames = explode('|', $cityNameList);
            $cityName = !empty($cityNames[0]) ? $cityNames[0] : null;
        
            // Logarea pentru a verifica ce se întâmplă
            Log::info('Processing row', [
                'iataCode' => $iataCode,
                'airportName' => $airportName,
                'cityNames' => $cityNames,
                'cityName' => $cityName
            ]);
        
            // Dacă există aeroport, adaugă-l în tabelul cities
            if (Str::contains(strtolower($airportName), 'airport')) {
                if (!DB::table('cities')->where('iata_code', $iataCode)->exists()) {
                    DB::table('cities')->insert([
                        'iata_code' => $iataCode,
                        'airport_name' => $airportName,
                        'city_name' => $cityName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                   
                }
            }
            Log::info("Seeder completed and data inserted successfully.");
                
        }
    }        
}
