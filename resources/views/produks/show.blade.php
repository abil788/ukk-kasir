@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Detail Produk</h1>
            <a href="{{ route('produks.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-sm font-medium text-gray-700">Kode Produk</h2>
                <p class="text-lg font-semibold text-gray-900">{{ $produk->kode_produk }}</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Nama Produk</h2>
                <p class="text-lg font-semibold text-gray-900">{{ $produk->nama }}</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Harga</h2>
                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Stok</h2>
                <p class="text-lg font-semibold {{ $produk->stok < 5 ? 'text-red-600' : 'text-green-600' }}">
                    {{ $produk->stok }}
                </p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Kategori</h2>
                <p class="text-lg font-semibold text-gray-900">{{ $produk->kategori ?? '-' }}</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Deskripsi</h2>
                <p class="text-gray-900">{{ $produk->deskripsi ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-6 flex space-x-4">
            <a href="{{ route('produks.edit', $produk->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('produks.destroy', $produk->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
@endsection