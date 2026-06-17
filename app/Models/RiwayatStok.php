<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    protected $fillable = [
        'barang_id',
        'jenis',
        'jumlah',
        'keterangan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($riwayat) {
            $barang = $riwayat->barang;
            if ($riwayat->jenis == 'masuk') {
                $barang->stok += $riwayat->jumlah;
            } elseif ($riwayat->jenis == 'keluar') {
                $barang->stok -= $riwayat->jumlah;
            }
            $barang->save();
        });
    }
}
