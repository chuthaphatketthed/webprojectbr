<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    
    public function run()
    {
        User::create([
            'name' => 'chuthaphat2',
            'email' => 'guydodo123@gmail.com',
            'password' => bcrypt('soxtyguy456'),
            'role' => 'user',
        ]);
    }
}
