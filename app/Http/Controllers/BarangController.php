<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        
        $query = Barang::query();
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('nama_barang', 'like', "%$q%")
                      ->orWhere('kategori', 'like', "%$q%")
                      ->orWhere('barcode', 'like', "%$q%");
            });
        }
        
        $barangs = $query->get()->map(function ($item) {
            if ($item->stok == 0) {
                $item->status = 'Habis';
            } elseif ($item->stok < $item->batas_minimum) {
                $item->status = 'Menipis';
            } else {
                $item->status = 'Aman';
            }
            return $item;
        });
        return response()->json($barangs);
    }

    public function show($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        if ($barang->stok == 0) {
            $barang->status = 'Habis';
        } elseif ($barang->stok < $barang->batas_minimum) {
            $barang->status = 'Menipis';
        } else {
            $barang->status = 'Aman';
        }

        return response()->json($barang);
    }

    private function generateBarcode($kategori, $namaBarang)
    {
        $kategoriCode = strtoupper(Str::slug(Str::substr($kategori, 0, 3) ?: 'X'));
        $namaCode = strtoupper(Str::slug(Str::substr($namaBarang, 0, 3) ?: 'Y'));
        $random = random_int(100, 999);
        
        return "{$kategoriCode}-{$namaCode}-{$random}";
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'batas_minimum' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:barangs,barcode',
        ]);

        // Auto generate barcode if not provided or blank
        if (blank($validated['barcode'] ?? null)) {
            $barcode = $this->generateBarcode(
                $validated['kategori'], 
                $validated['nama_barang']
            );
            
            // Ensure unique
            while (Barang::where('barcode', $barcode)->exists()) {
                $barcode = $this->generateBarcode(
                    $validated['kategori'], 
                    $validated['nama_barang']
                );
            }
            $validated['barcode'] = $barcode;
        }

        $barang = Barang::create($validated);
        return response()->json(['message' => 'Barang berhasil disimpan', 'data' => $barang]);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama_barang' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string|max:255',
            'stok' => 'sometimes|required|integer|min:0',
            'batas_minimum' => 'sometimes|required|integer|min:0',
            'barcode' => 'sometimes|nullable|string|unique:barangs,barcode,' . $id,
        ]);

        // Auto generate barcode if not provided
        if (blank($validated['barcode'] ?? null) && $barang->barcode === null) {
            $kategori = $validated['kategori'] ?? $barang->kategori;
            $namaBarang = $validated['nama_barang'] ?? $barang->nama_barang;
            $barcode = $this->generateBarcode($kategori, $namaBarang);
            
            while (Barang::where('barcode', $barcode)->where('id', '!=', $id)->exists()) {
                $barcode = $this->generateBarcode($kategori, $namaBarang);
            }
            $validated['barcode'] = $barcode;
        }

        $barang->update($validated);
        return response()->json(['message' => 'Barang berhasil diupdate', 'data' => $barang]);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        $barang->delete();
        return response()->json(['message' => 'Barang berhasil dihapus']);
    }

    public function stats()
    {
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $barangMenipis = Barang::whereColumn('stok', '<', 'batas_minimum')
            ->where('stok', '>', 0)
            ->count();
        $barangHabis = Barang::where('stok', '<=', 0)->count();

        return response()->json([
            'total_barang' => $totalBarang,
            'total_stok' => $totalStok,
            'barang_menipis' => $barangMenipis,
            'barang_habis' => $barangHabis,
        ]);
    }
}
