<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Stock;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => User::ROLE_ADMIN, // Assign 'super admin' role to the user
        ]);

        User::create([
            'name' => 'staff',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => User::ROLE_STAFF, // Assign 'admin' role to the user
        ]);

        User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => User::ROLE_USER, // Assign 'user' role to the user
        ]);

        Stock::create([
            'name' => 'Monitor AOC',
            'balance' => 0,
        ]);

        Stock::create([
            'name' => 'Keyboard Logitech K120',
            'balance' => 0,
        ]);

        Stock::create([
            'name' => 'Thermal Ribbon',
            'balance' => 0,
        ]);

        Stock::create([
            'name' => 'B100 Corded Mouse (Logitech)',
            'balance' => 0,
        ]);

        Stock::create([
            'name' => 'Display Port to HDMI',
            'balance' => 0,
        ]);

        Stock::create([
            'name' => 'MyPassport WD (1TB)',
            'balance' => 0,
        ]);

        Stock::create([
            'name' => 'Desktop Switch 8 Port (TP-Link) Litewave LS1008',
            'balance' => 0,
        ]);

        Stock::create([
            'name' => 'Screen Cleaning Kit',
            'balance' => 0,
        ]);

        Asset::create([
            'name' => 'Laptop (NB001)',
            'model' => 'Acer AS E5 474G',
            'registration_no' => 'KKM/JKN/HKK/0309-01/H/15/267'
        ]);

        Asset::create([
            'name' => 'Laptop (NB002)',
            'model' => 'Acer AS E5 474G',
            'registration_no' => 'KKM/JKN/HKK/0309-01/H/15/268'
        ]);

        Asset::create([
            'name' => 'Laptop (NB003)',
            'model' => 'Acer AS E5 474G',
            'registration_no' => 'KKM/JKN/HKK/0309-01/H/15/269'
        ]);

        Asset::create([
            'name' => 'Laptop (NB006)',
            'model' => 'Acer AS E5 474G',
            'registration_no' => 'KKM/JKN/HKK/0309-01/H/15/260'
        ]);

        Asset::create([
            'name' => 'Laptop HP SPK (IT 03)',
            'model' => 'HP Probook 440 G4',
            'registration_no' => 'KKM/JKN/HSIP/0309-01/H/18/379'
        ]);

        Asset::create([
            'name' => 'Laptop HP SPK (IT 04)',
            'model' => 'HP Probook 440 G4',
            'registration_no' => 'KKM/JKN/HSIP/0309-01/H/18/383'
        ]);

        Asset::create([
            'name' => 'Laptop Sewa (Nissen 01)',
            'model' => 'Lenovo Think Centre M720s',
            'registration_no' => 'NB/KEL/02/001',
        ]);

        Asset::create([
            'name' => 'Laptop Sewa (Nissen 03)',
            'model' => 'Lenovo Think Centre M720s',
            'registration_no' => 'NB/KEL/02/003',
        ]);

        Asset::create([
            'name' => 'Laptop Sewa (Dutarini 02)',
            'model' => 'Acer Veriton X2670G',
            'registration_no' => 'Z2/DTR/KEL/NB/69'
        ]);
    }
}
