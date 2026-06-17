<?php

namespace App\Http\Controllers;

use App\Models\RiwayatStok;
use Illuminate\Http\Request;

class RiwayatStokController extends Controller
{
    // Get riwayat stok by barang_id
    public function index(Request $request)
    {
        $barangId = $request->query('barang_id');

        if ($barangId) {
            $riwayats = RiwayatStok::where('barang_id', $barangId)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $riwayats = RiwayatStok::with('barang')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($riwayats);
    }

    // Store riwayat stok (input stok masuk/keluar)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $riwayat = RiwayatStok::create([
            'barang_id' => $validated['barang_id'],
            'jenis' => $validated['tipe'], // Map tipe -> jenis
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'],
        ]);

        // Update stok di model boot() otomatis
        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui',
            'data' => $riwayat
        ], 201);
    }
}