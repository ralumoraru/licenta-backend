<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteNonAirportCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Șterge toate rândurile unde city_name nu conține cuvântul "Airport"
        DB::table('cities')
            ->where('city_name', 'not like', '%Airport%')
            ->delete();
        
        // Opțional: Mesaj de confirmare în consolă
        $this->command->info('Toate orașele care nu conțin „Airport” au fost șterse.');
    }
}
