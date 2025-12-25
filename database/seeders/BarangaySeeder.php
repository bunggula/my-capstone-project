<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    public function run()
    {
        $barangays = [
            'Anis',
            'Balligi',
            'Banuar',
            'Batogue',
            'Caaringayan',
            'D alarcio',
            'Cabilaoan',
            'Cabulalaan',
            'Calaoagan',
            'Calmay',
            'Casampagaan',
            'Casanestebanan',
            'Casantiagoan',
            'Inmanduyan',
            'Poblacion',
            'Lebueg',
            'Maraboc',
            'Nanbagatan',
            'Panaga',
            'Talogtog',
            'Turko',
            'Yatyat',
            
        ];

        foreach ($barangays as $name) {
            Barangay::create(['name' => $name]);
        }
    }
}
