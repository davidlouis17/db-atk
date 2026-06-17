<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'kategori',
        'stok',
        'batas_minimum',
        'barcode',
    ];

    protected $casts = [
        'stok' => 'integer',
        'batas_minimum' => 'integer',
    ];

    public function riwayat_stoks()
    {
        return $this->hasMany(RiwayatStok::class);
    }

    public function getStatusStokAttribute()
    {
        if ($this->stok == 0) {
            return 'Habis';
        } elseif ($this->stok < $this->batas_minimum) {
            return 'Menipis';
        } else {
            return 'Aman';
        }
    }
}
