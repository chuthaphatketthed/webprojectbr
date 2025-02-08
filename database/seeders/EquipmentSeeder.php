<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    public function run()
    {
        // ตัวอย่างข้อมูล Mock
        Equipment::create([
            'name' => 'Wheelchair',
            'quantity' => 5,
            'details' => 'Standard wheelchair for general use',
        ]);

        Equipment::create([
            'name' => 'Oxygen Tank',
            'quantity' => 10,
            'details' => 'Portable oxygen tank for medical emergencies',
        ]);

        Equipment::create([
            'name' => 'Defibrillator',
            'quantity' => 2,
            'details' => 'Used for restoring a normal heartbeat',
        ]);
    }
}
