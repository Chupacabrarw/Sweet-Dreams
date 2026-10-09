<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin saja. Tanpa Test User agar dasbor/laporan tidak terkontaminasi data demo.
        // (Akun admin dibuat oleh AdminSeeder yang tidak diubah.)
        $this->call(AdminSeeder::class);
        $this->call(ProductSeeder::class); // tambahin ini
        $this->call(OrderSeeder::class);
    }
}
