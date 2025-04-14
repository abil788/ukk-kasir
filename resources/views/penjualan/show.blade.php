@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl mt-12 px-6">
    <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-8">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-6 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Detail Penjualan</h2>
                <p class="text-sm text-gray-500">Invoice: <span class="font-medium">{{ $penjualan->kode_invoice }}</span></p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <i class="fas fa-calendar-alt text-gray-300"></i>
                {{ $penjualan->created_at->format('d M Y') }}
            </div>
        </div>

        <!-- Info Pelanggan & Barang -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500">Pelanggan</p>
                <p class="text-base text-gray-800 font-medium">
                    {{ $penjualan->pelanggan->nama ?? 'Umum' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Barang</p>
                <p class="text-base text-gray-800 font-medium">
                    {{ $penjualan->detailPenjualan->sum('jumlah') }} item
                </p>
            </div>
        </div>

        <!-- Daftar Barang -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Daftar Barang</h3>
            <div class="divide-y border border-gray-100 rounded-lg">
                @foreach ($penjualan->detailPenjualan as $detail)
                    <div class="flex justify-between items-center px-4 py-3 hover:bg-gray-50 transition">
                        <div>
                            <p class="text-gray-800 font-medium">{{ $detail->produk->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $detail->jumlah }} pcs</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Subtotal</p>
                            <p class="text-gray-800 font-semibold">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Total & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500">Total Harga</p>
                <p class="text-xl text-green-600 font-bold">
                    Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status Pembayaran</p>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                    {{ $penjualan->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($penjualan->status_pembayaran) }}
                </span>
            </div>
        </div>

        <!-- Back Button -->
        <div class="pt-4 border-t">
            <a href="{{ route('penjualans.index') }}" 
               class="inline-flex items-center text-sm text-blue-600 hover:underline hover:text-blue-800 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke daftar penjualan
            </a>
        </div>
    </div>
</div>
@endsection
