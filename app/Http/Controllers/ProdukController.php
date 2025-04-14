<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Menampilkan daftar semua produk, dengan pagination (10 per halaman)
    public function index(Request $request)
    {
        $query = Produk::latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                ->orWhere('kode_produk', 'like', '%' . $search . '%')
                ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        $produks = $query->paginate(10);

        return view('produks.index', compact('produks'));
    }

    // Menampilkan halaman form untuk membuat produk baru
    public function create()
    {
        return view('produks.create');
    }

    // Menyimpan data produk baru ke database setelah validasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'kode_produk' => 'required|string|max:50|unique:produks',
        ]);

        Produk::create($validated);

        return redirect()->route('produks.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan produk
    public function show(Produk $produk)
    {
        return view('produks.show', compact('produk'));
    }

    //mengedit produk
    public function edit(Produk $produk)
    {
        return view('produks.edit', compact('produk'));
    }

    //mengupdate produk
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'kode_produk' => 'required|string|max:50|unique:produks,kode_produk,' . $produk->id,
        ]);

        $produk->update($validated);

        return redirect()->route('produks.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    //menghapus produk
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return redirect()->route('produks.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}