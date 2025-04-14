@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold mb-6">Dashboard Kasir bilmart.id</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Penjualan Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Penjualan</h2>
                    <i class="fas fa-shopping-cart text-blue-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Penjualan::count() }}</p>
                <p class="mt-2 text-sm text-gray-600">Total transaksi penjualan</p>
                <div class="mt-4">
                    <a href="{{ route('penjualans.index') }}" class="text-blue-600 hover:underline">Lihat semua</a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('penjualans.create') }}" class="text-blue-600 hover:underline">Tambah baru</a>
                </div>
            </div>

            <!-- Pelanggan Card -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Pelanggan</h2>
                    <i class="fas fa-users text-green-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ \App\Models\Pelanggan::count() }}</p>
                <p class="mt-2 text-sm text-gray-600">Total pelanggan terdaftar</p>
                <div class="mt-4">
                    <a href="{{ route('pelanggans.index') }}" class="text-green-600 hover:underline">Lihat semua</a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('pelanggans.create') }}" class="text-green-600 hover:underline">Tambah baru</a>
                </div>
            </div>

            <!-- Produk Card -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Produk</h2>
                    <i class="fas fa-box text-purple-500 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Produk::count() }}</p>
                <p class="mt-2 text-sm text-gray-600">Total produk tersedia</p>
                <div class="mt-4">
                    <a href="{{ route('produks.index') }}" class="text-purple-600 hover:underline">Lihat semua</a>
                    <span class="mx-2">|</span>
                    <a href="{{ route('produks.create') }}" class="text-purple-600 hover:underline">Tambah baru</a>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Penjualan Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border text-left">Invoice</th>
                            <th class="py-2 px-4 border text-left">Pelanggan</th>
                            <th class="py-2 px-4 border text-left">Tanggal</th>
                            <th class="py-2 px-4 border text-right">Total</th>
                            <th class="py-2 px-4 border text-center">Status</th>
                            <th class="py-2 px-4 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Penjualan::with('pelanggan')->latest()->take(5)->get() as $penjualan)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border">{{ $penjualan->kode_invoice }}</td>
                            <td class="py-2 px-4 border">{{ optional($penjualan->pelanggan)->nama ?? 'umum' }}</td>
                            <td class="py-2 px-4 border">{{ $penjualan->tanggal->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border text-right">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 border text-center">
                                <span class="px-2 py-1 text-xs rounded 
                                    {{ $penjualan->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $penjualan->status_pembayaran }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border text-center">
                                <a href="{{ route('penjualans.show', $penjualan->id) }}" class="text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Produk Stok Menipis</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border text-left">Kode</th>
                            <th class="py-2 px-4 border text-left">Nama Produk</th>
                            <th class="py-2 px-4 border text-right">Harga</th>
                            <th class="py-2 px-4 border text-center">Stok</th>
                            <th class="py-2 px-4 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Produk::where('stok', '<', 10)->take(5)->get() as $produk)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border">{{ $produk->kode_produk }}</td>
                            <td class="py-2 px-4 border">{{ $produk->nama }}</td>
                            <td class="py-2 px-4 border text-right">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 border text-center">
                                <span class="px-2 py-1 text-xs rounded 
                                    {{ $produk->stok < 5 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $produk->stok }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border text-center">
                                <a href="{{ route('produks.edit', $produk->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection