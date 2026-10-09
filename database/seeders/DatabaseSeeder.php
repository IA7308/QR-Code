<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shelter; // (1) Impor Shelter
use App\Models\Sheep;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT AKUN ADMIN (Sesuai permintaan Anda)
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'], // Kunci pencarian agar tidak duplikat
            [
                'name' => 'Admin',
                'password' => Hash::make('password'), // password default adalah 'password'
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Kantor Pusat JAS Farm',
            ]
        );

        // 2. BUAT AKUN MITRA (PARTNER) - Untuk tes fitur Mitra
        $mitra = User::firstOrCreate(
            ['email' => 'mitra@example.com'],
            [
                'name' => 'Budi (Mitra Plasma)',
                'password' => Hash::make('password'),
                'role' => 'mitra',
                'phone' => '089876543210',
                'address' => 'Desa Suka Maju, Blok C',
            ]
        );

        // 3. BUAT AKUN STAF (PEGAWAI) - Untuk tes fitur Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Ujang (Anak Kandang)',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '081122334455',
                'address' => 'Mess Karyawan',
            ]
        );

        // Akun dummy siap dipakai tanpa alur verifikasi email.
        foreach ([$admin, $mitra, $staff] as $user) {
            if (!$user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
        }

        // 4. BUAT DATA KANDANG DUMMY (Milik Admin)
        // Kita buat kandang spesifik agar namanya jelas
        Shelter::firstOrCreate(
            ['name' => 'Kandang Utama A'],
            ['user_id' => $admin->id, 'capacity' => 50, 'description' => 'Kandang pembibitan utama']
        );

        Shelter::firstOrCreate(
            ['name' => 'Kandang Isolasi B'],
            ['user_id' => $admin->id, 'capacity' => 10, 'description' => 'Untuk domba sakit atau karantina']
        );

        // Buat 3 kandang tambahan acak menggunakan Factory
        Shelter::factory()->create([
            'user_id' => $admin->id
        ]);
        Shelter::factory()->create([
            'user_id' => $mitra->id
        ]);
        Shelter::factory()->create([
            'user_id' => $staff->id
        ]);

        // Data domba contoh yang aman dijalankan ulang tanpa membuat duplikat.
        $shelterUtama = Shelter::where('name', 'Kandang Utama A')->firstOrFail();
        $dummySheep = [
            ['tag_number' => 'DMB-DEMO-001', 'gender' => 'Jantan', 'date_of_birth' => now()->subMonths(18)->toDateString(), 'birth_weight' => 4.20, 'category' => 'Pedaging', 'type' => 'Garut', 'purchase_price' => 2500000, 'is_pedigree' => true, 'special_characteristics' => 'Tubuh besar, kondisi sehat'],
            ['tag_number' => 'DMB-DEMO-002', 'gender' => 'Betina', 'date_of_birth' => now()->subMonths(15)->toDateString(), 'birth_weight' => 3.80, 'category' => 'Indukan', 'type' => 'Garut', 'purchase_price' => 2200000, 'is_pedigree' => true, 'special_characteristics' => 'Indukan produktif'],
            ['tag_number' => 'DMB-DEMO-003', 'gender' => 'Jantan', 'date_of_birth' => now()->subMonths(10)->toDateString(), 'birth_weight' => 3.50, 'category' => 'Pedaging', 'type' => 'Texel', 'purchase_price' => 1800000, 'is_pedigree' => false, 'special_characteristics' => 'Pertumbuhan normal'],
            ['tag_number' => 'DMB-DEMO-004', 'gender' => 'Betina', 'date_of_birth' => now()->subMonths(8)->toDateString(), 'birth_weight' => 3.20, 'category' => 'Indukan', 'type' => 'Garut', 'purchase_price' => 1700000, 'is_pedigree' => false, 'special_characteristics' => 'Bulu putih, aktif'],
            ['tag_number' => 'DMB-DEMO-005', 'gender' => 'Jantan', 'date_of_birth' => now()->subMonths(5)->toDateString(), 'birth_weight' => 2.90, 'category' => 'Pedaging', 'type' => 'Lokal', 'purchase_price' => 1300000, 'is_pedigree' => false, 'special_characteristics' => 'Anak domba sehat'],
        ];

        foreach ($dummySheep as $sheep) {
            Sheep::firstOrCreate(
                ['tag_number' => $sheep['tag_number']],
                array_merge($sheep, [
                    'user_id' => $admin->id,
                    'shelter_id' => $shelterUtama->id,
                    'placement_status' => 'Internal',
                    'description' => 'Data contoh untuk demonstrasi aplikasi.',
                ])
            );
        }

    }
}
