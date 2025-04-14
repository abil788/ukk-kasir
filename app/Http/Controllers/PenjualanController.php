<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    // Menampilkan daftar semua penjualan, dengan pagination (10 per halaman)
    public function index(Request $request)
    {
        $query = Penjualan::with('pelanggan')->latest();

        if ($request->has('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_invoice', 'like', '%' . $search . '%')
                ->orWhereHas('pelanggan', function ($q2) use ($search) {
                    $q2->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('telepon', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        $penjualans = $query->paginate(10);

        return view('penjualan.index', compact('penjualans'));
    }



     // Menampilkan halaman form untuk membuat pelanggan baru
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $produks = Produk::all();
        return view('penjualan.create', compact('pelanggans', 'produks'));
    }

    // Menyimpan data penjualan baru ke database setelah validasi
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'status_pembayaran' => 'required|string|in:lunas,pending',
            'metode_pembayaran' => 'required|string',
            'uang_pelanggan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $totalHarga = 0;

            // Generate kode invoice yang unik
            do {
                $kodeInvoice = 'INV-' . strtoupper(Str::random(10));
            } while (Penjualan::where('kode_invoice', $kodeInvoice)->exists());

            $produkIds = $request->produk_id;
            $jumlahs = $request->jumlah;

            // Cek stok produk
            foreach ($produkIds as $key => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $jumlah = $jumlahs[$key];

                if ($produk->stok < $jumlah) {
                    return back()->with('error', 'Stok produk ' . $produk->nama . ' tidak cukup.');
                }
            }

            // Buat data penjualan sementara
            $penjualan = Penjualan::create([
                'pelanggan_id' => $request->pelanggan_id,
                'tanggal' => $request->tanggal,
                'total_harga' => 0,
                'status_pembayaran' => $request->status_pembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'kode_invoice' => $kodeInvoice,
            ]);

            // Proses detail produk
            foreach ($produkIds as $key => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $jumlah = $jumlahs[$key];

                $hargaSatuan = $produk->harga;
                $subtotal = $hargaSatuan * $jumlah;
                $totalHarga += $subtotal;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok
                $produk->decrement('stok', $jumlah);
            }

            // Update total harga
            $penjualan->update(['total_harga' => $totalHarga]);

            // Validasi berdasarkan status pembayaran
            if ($request->status_pembayaran === 'lunas' && $request->uang_pelanggan < $totalHarga) {
                DB::rollBack(); // balikin semua proses
                return back()->with('error', 'Uang pelanggan tidak cukup untuk pembayaran lunas. Total harga: Rp' . number_format($totalHarga, 0, ',', '.') . ', uang pelanggan: Rp' . number_format($request->uang_pelanggan, 0, ',', '.'));
            }

            DB::commit();
            return redirect()->route('penjualans.index')->with('success', 'Transaksi berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    // Menampilkan id pelanggan
    public function show($id)
    {
        $penjualan = Penjualan::with('pelanggan', 'detailPenjualan.produk')->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }

    // Menampilkan form edit untuk penjualan
    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $pelanggans = Pelanggan::all();
        $produks = Produk::all();
        return view('penjualan.edit', compact('penjualan', 'pelanggans', 'produks'));
    }

    // Memperbarui data penjualan
    public function update(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'status_pembayaran' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        $penjualan = Penjualan::findOrFail($id);
        $penjualan->update([
            'pelanggan_id' => $request->pelanggan_id,
            'tanggal' => $request->tanggal,
            'status_pembayaran' => $request->status_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        return redirect()->route('penjualans.index')->with('success', 'Data penjualan berhasil diperbarui!');
    }

    //menghapus data penjualan
    public function destroy(Request $request, $id)
    {
        if ($request->kode_keamanan !== '788979') {
            return back()->with('error', 'Kode keamanan salah. Data tidak dihapus.');
        }

        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('penjualans.index')->with('success', 'Data penjualan berhasil dihapus!');
    }

}