<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // Menampilkan daftar semua pelanggan, dengan pagination (10 per halaman)
    public function index(Request $request)
    {
        // Mencari pelanggan berdasarkan parameter pencarian jika ada
        $query = Pelanggan::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('telepon', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        // Menambahkan pagination
        $pelanggans = $query->paginate(10);

        // Mengembalikan view dengan data pelanggan yang sudah dipaginate
        return view('pelanggans.index', compact('pelanggans'));
    }


    // Menampilkan halaman form untuk membuat pelanggan baru
    public function create()
    {
        return view('pelanggans.create'); 
    }

    // Menyimpan data pelanggan baru ke database setelah validasi
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string', 
            'telepon' => 'nullable|string|max:15', 
            'email' => 'nullable|email|max:255', 
            'tanggal_registrasi' => 'required|date', 
        ]);

        Pelanggan::create($validated); // Menyimpan data yang telah divalidasi ke database

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('pelanggans.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // Menampilkan detail dari satu pelanggan berdasarkan ID
    public function show(Pelanggan $pelanggan)
    {
        return view('pelanggans.show', compact('pelanggan')); 
    }

    // Menampilkan form edit untuk pelanggan tertentu
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggans.edit', compact('pelanggan')); 
    }

    // Memperbarui data pelanggan yang telah ada setelah divalidasi
    public function update(Request $request, Pelanggan $pelanggan)
    {
        // Validasi input yang diterima dari form edit
        $validated = $request->validate([
            'nama' => 'required|string|max:255', 
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15', 
            'email' => 'nullable|email|max:255', 
            'tanggal_registrasi' => 'required|date', 
        ]);

        $pelanggan->update($validated); // Memperbarui data pelanggan

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('pelanggans.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // Menghapus data pelanggan dari database
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete(); 

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('pelanggans.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
