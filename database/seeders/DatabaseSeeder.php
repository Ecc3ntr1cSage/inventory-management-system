<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Asset;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $users = [];

        foreach (User::DEMO_ACCOUNTS as $role => $account) {
            $users[$role] = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => User::DEMO_PASSWORD,
                    'role' => $role,
                    'email_verified_at' => $now,
                ],
            );
        }

        $stockDefinitions = [
            ['Monitor AOC', 'Peralatan ICT', 'Bilik Stor ICT', 'MON', [[120, 30, null], [35, null, 10], [4, 12, null]]],
            ['Keyboard Logitech K120', 'Peralatan ICT', 'Bilik Stor ICT', 'KEY', [[120, 80, null], [35, null, 20], [4, 15, null]]],
            ['Thermal Ribbon', 'Bahan Cetak', 'Bilik Percetakan', 'RIB', [[120, 50, null], [35, null, 15], [4, 10, null]]],
            ['B100 Corded Mouse (Logitech)', 'Peralatan ICT', 'Bilik Stor ICT', 'MOU', [[120, 60, null], [35, null, 25], [4, 18, null]]],
            ['Display Port to HDMI', 'Peralatan ICT', 'Bilik Stor ICT', 'DPH', [[120, 35, null], [35, null, 8], [4, 12, null]]],
            ['MyPassport WD (1TB)', 'Media Simpanan', 'Bilik Stor ICT', 'HDD', [[120, 25, null], [35, null, 6], [4, 8, null]]],
            ['Desktop Switch 8 Port (TP-Link) Litewave LS1008', 'Rangkaian', 'Bilik Server', 'SWT', [[120, 15, null], [35, null, 4], [4, 3, null]]],
            ['Screen Cleaning Kit', 'Penyelenggaraan', 'Bilik Stor ICT', 'CLN', [[120, 40, null], [35, null, 12], [4, 8, null]]],
        ];

        foreach ($stockDefinitions as [$name, $group, $location, $code, $movements]) {
            $stock = Stock::create([
                'name' => $name,
                'group' => $group,
                'location' => $location,
                'balance' => 0,
            ]);

            $balance = 0;

            foreach ($movements as $index => [$daysAgo, $inQuantity, $outQuantity]) {
                $balance += ($inQuantity ?? 0) - ($outQuantity ?? 0);

                $stock->entries()->create([
                    'date' => $now->copy()->subDays($daysAgo)->toDateString(),
                    'reference_no' => "STK-{$code}-00" . ($index + 1),
                    'in_quantity' => $inQuantity,
                    'out_quantity' => $outQuantity,
                    'balance' => $balance,
                    'name' => $index === 1 ? $users[User::ROLE_STAFF]->name : $users[User::ROLE_ADMIN]->name,
                ]);
            }

            $stock->update(['balance' => $balance]);
        }

        $assetDefinitions = [
            ['Laptop (NB001)', 'Acer AS E5 474G', 'KKM/JKN/HKK/0309-01/H/15/267'],
            ['Laptop (NB002)', 'Acer AS E5 474G', 'KKM/JKN/HKK/0309-01/H/15/268'],
            ['Laptop (NB003)', 'Acer AS E5 474G', 'KKM/JKN/HKK/0309-01/H/15/269'],
            ['Laptop (NB006)', 'Acer AS E5 474G', 'KKM/JKN/HKK/0309-01/H/15/260'],
            ['Laptop HP SPK (IT 03)', 'HP Probook 440 G4', 'KKM/JKN/HSIP/0309-01/H/18/379'],
            ['Laptop HP SPK (IT 04)', 'HP Probook 440 G4', 'KKM/JKN/HSIP/0309-01/H/18/383'],
            ['Laptop Sewa (Nissen 01)', 'Lenovo Think Centre M720s', 'NB/KEL/02/001'],
            ['Laptop Sewa (Nissen 03)', 'Lenovo Think Centre M720s', 'NB/KEL/02/003'],
            ['Laptop Sewa (Dutarini 02)', 'Acer Veriton X2670G', 'Z2/DTR/KEL/NB/69'],
        ];

        foreach ($assetDefinitions as [$name, $model, $registrationNo]) {
            Asset::create([
                'name' => $name,
                'model' => $model,
                'registration_no' => $registrationNo,
            ]);
        }

        $asset = fn (string $name) => Asset::where('name', $name)->firstOrFail();
        $user = $users[User::ROLE_USER];
        $staff = $users[User::ROLE_STAFF];
        $admin = $users[User::ROLE_ADMIN];

        $applications = [
            [
                'user_id' => $user->id,
                'description' => 'Komputer riba untuk tugasan audit dalaman.',
                'reason' => 'Penyediaan laporan audit ICT.',
                'position' => 'Pegawai Teknologi Maklumat',
                'department' => 'Unit Teknologi Maklumat',
                'location' => 'Pejabat Kesihatan Daerah Klang',
                'application_date' => $now->copy()->subDays(2)->toDateString(),
                'status' => 0,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'guest_name' => 'Nur Aisyah Rahman',
                'guest_email' => 'aisyah@invms.test',
                'description' => 'Komputer riba untuk sesi taklimat.',
                'reason' => 'Taklimat penyelarasan program.',
                'position' => 'Pegawai Tadbir',
                'department' => 'Unit Korporat',
                'location' => 'Bilik Mesyuarat Utama',
                'application_date' => $now->copy()->subDays(7)->toDateString(),
                'status' => 0,
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(7),
            ],
            [
                'user_id' => $user->id,
                'asset_id' => $asset('Laptop (NB001)')->id,
                'description' => 'Komputer riba untuk projek pendigitalan.',
                'reason' => 'Pelaksanaan projek pendigitalan rekod.',
                'position' => 'Penolong Pegawai Teknologi Maklumat',
                'department' => 'Unit Teknologi Maklumat',
                'location' => 'Makmal Komputer',
                'application_date' => $now->copy()->subDays(14)->toDateString(),
                'date_issued' => $now->copy()->subDays(10)->toDateString(),
                'handler' => $staff->name,
                'status' => 1,
                'created_at' => $now->copy()->subDays(14),
                'updated_at' => $now->copy()->subDays(10),
            ],
            [
                'user_id' => $user->id,
                'asset_id' => $asset('Laptop HP SPK (IT 03)')->id,
                'description' => 'Komputer riba untuk lawatan lapangan.',
                'reason' => 'Lawatan pemantauan fasiliti.',
                'position' => 'Pegawai Kesihatan',
                'department' => 'Unit Operasi',
                'location' => 'Daerah Kuala Selangor',
                'application_date' => $now->copy()->subDays(42)->toDateString(),
                'date_issued' => $now->copy()->subDays(35)->toDateString(),
                'handler' => $admin->name,
                'status' => 1,
                'created_at' => $now->copy()->subDays(42),
                'updated_at' => $now->copy()->subDays(35),
            ],
            [
                'user_id' => $user->id,
                'asset_id' => $asset('Laptop (NB002)')->id,
                'description' => 'Komputer riba untuk latihan kakitangan.',
                'reason' => 'Latihan penggunaan sistem baharu.',
                'position' => 'Pembantu Tadbir',
                'department' => 'Unit Pentadbiran',
                'location' => 'Bilik Latihan',
                'application_date' => $now->copy()->subDays(110)->toDateString(),
                'date_issued' => $now->copy()->subDays(90)->toDateString(),
                'date_returned' => $now->copy()->subDays(55)->toDateString(),
                'handler' => $staff->name,
                'receiver' => $staff->name,
                'status' => 3,
                'created_at' => $now->copy()->subDays(110),
                'updated_at' => $now->copy()->subDays(55),
            ],
            [
                'user_id' => $user->id,
                'asset_id' => $asset('Laptop Sewa (Nissen 01)')->id,
                'description' => 'Komputer riba untuk mesyuarat luar.',
                'reason' => 'Mesyuarat penyelarasan bersama agensi.',
                'position' => 'Pegawai Perubatan',
                'department' => 'Unit Kesihatan Awam',
                'location' => 'Pejabat Daerah Petaling',
                'application_date' => $now->copy()->subDays(180)->toDateString(),
                'date_issued' => $now->copy()->subDays(170)->toDateString(),
                'date_returned' => $now->copy()->subDays(150)->toDateString(),
                'handler' => $admin->name,
                'receiver' => $staff->name,
                'status' => 3,
                'created_at' => $now->copy()->subDays(180),
                'updated_at' => $now->copy()->subDays(150),
            ],
        ];

        foreach ($applications as $attributes) {
            Application::forceCreate($attributes);
        }

        foreach (['Laptop (NB001)', 'Laptop HP SPK (IT 03)'] as $name) {
            $issuedAsset = $asset($name);
            $issuedAsset->is_available = false;
            $issuedAsset->save();
        }
    }
}
