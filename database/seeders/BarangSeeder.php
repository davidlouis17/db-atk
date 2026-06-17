<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Barang::create([
            'nama_barang' => 'Kertas A4',
            'kategori' => 'Kertas',
            'stok' => 50,
            'batas_minimum' => 10,
        ]);

        Barang::create([
            'nama_barang' => 'Tinta Ballpoint Biru',
            'kategori' => 'Tinta',
            'stok' => 3,
            'batas_minimum' => 5,
        ]);

        Barang::create([
            'nama_barang' => 'Stapler',
            'kategori' => 'Alat Tulis',
            'stok' => 0,
            'batas_minimum' => 2,
        ]);

        Barang::create([
            'nama_barang' => 'Klip Kertas',
            'kategori' => 'Alat Tulis',
            'stok' => 200,
            'batas_minimum' => 50,
        ]);

        Barang::create([
            'nama_barang' => 'Penggaris Besi',
            'kategori' => 'Alat Tulis',
            'stok' => 2,
            'batas_minimum' => 3,
        ]);
    }
}