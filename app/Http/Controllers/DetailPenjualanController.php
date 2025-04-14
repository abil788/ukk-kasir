<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPenjualanController extends Controller
{
    /**
     * memunculkan form untuk detail penjualan
     */
    public function create(Penjualan $penjualan)
    {
        $produks = Produk::where('stok', '>', 0)->get();
        return view('detail_penjualans.create', compact('penjualan', 'produks'));
    }

    /**
     * storage untuk penjualan yang baru dibuat
     */
    public function store(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($request->produk_id);
            
            // Validate stock
            if ($produk->stok < $request->jumlah) {
                throw new \Exception("Stok produk {$produk->nama} tidak mencukupi");
            }
            
            $subtotal = $produk->harga * $request->jumlah;
            
            // membuat detail penjualan
            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'produk_id' => $request->produk_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $produk->harga,
                'subtotal' => $subtotal,
            ]);
            
            // mengupdate stok produk
            $produk->update([
                'stok' => $produk->stok - $request->jumlah
            ]);
            
            // mengupdate harga produk
            $penjualan->update([
                'total_harga' => $penjualan->total_harga + $subtotal
            ]);
            
            DB::commit();
            return redirect()->route('penjualans.show', $penjualan->id)
                ->with('success', 'Item berhasil ditambahkan ke penjualan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * menghapus detail penjualan
     */
    public function destroy(DetailPenjualan $detailPenjualan)
    {
        DB::beginTransaction();
        try {
            $penjualan = $detailPenjualan->penjualan;
            $produk = $detailPenjualan->produk;
            $subtotal = $detailPenjualan->subtotal;
            $jumlah = $detailPenjualan->jumlah;
            
            // menghapus detail penjualan
            $detailPenjualan->delete();
            
            // mengupdate stok produk
            $produk->update([
                'stok' => $produk->stok + $jumlah
            ]);
            
            // mengupdate harga produk
            $penjualan->update([
                'total_harga' => $penjualan->total_harga - $subtotal
            ]);
            
            DB::commit();
            return redirect()->route('penjualans.show', $penjualan->id)
                ->with('success', 'Item berhasil dihapus dari penjualan dan stok telah dikembalikan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    /**
     * mengupdate kuantity detail penjualan
     */
    public function update(Request $request, DetailPenjualan $detailPenjualan)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = $detailPenjualan->penjualan;
            $produk = $detailPenjualan->produk;
            $oldJumlah = $detailPenjualan->jumlah;
            $newJumlah = $request->jumlah;
            $jumlahDiff = $newJumlah - $oldJumlah;
            
            // mengecheck apakah stock mencukupi
            if ($jumlahDiff > 0 && $produk->stok < $jumlahDiff) {
                throw new \Exception("Stok produk {$produk->nama} tidak mencukupi");
            }
            
            // menghitung subtotal
            $newSubtotal = $produk->harga * $newJumlah;
            $subtotalDiff = $newSubtotal - $detailPenjualan->subtotal;
            
            // Update detail penjualan
            $detailPenjualan->update([
                'jumlah' => $newJumlah,
                'subtotal' => $newSubtotal
            ]);
            
            // Update stok produk
            if ($jumlahDiff != 0) {
                $produk->update([
                    'stok' => $produk->stok - $jumlahDiff
                ]);
            }
            
            // Update total harga
            $penjualan->update([
                'total_harga' => $penjualan->total_harga + $subtotalDiff
            ]);
            
            DB::commit();
            return redirect()->route('penjualans.show', $penjualan->id)
                ->with('success', 'Jumlah item berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * memunculkan form detail penjualan
     */
    public function edit(DetailPenjualan $detailPenjualan)
    {
        $penjualan = $detailPenjualan->penjualan;
        return view('detail_penjualans.edit', compact('detailPenjualan', 'penjualan'));
    }
}