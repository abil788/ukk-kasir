@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Produk</h1>
            <a href="{{ route('produks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                <i class="fas fa-plus mr-1"></i> Tambah Produk
            </a>
        </div>

        <!-- Form Pencarian -->
        <div class="mb-4">
            <form action="{{ route('produks.index') }}" method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="border p-2 rounded w-full">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Cari
                </button>
            </form>
        </div>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border">Kode Produk</th>
                    <th class="py-2 px-4 border">Nama</th>
                    <th class="py-2 px-4 border">Harga</th>
                    <th class="py-2 px-4 border">Stok</th>
                    <th class="py-2 px-4 border">Kategori</th>
                    <th class="py-2 px-4 border">Deskripsi</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $produk)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border">{{ $produk->kode_produk }}</td>
                        <td class="py-2 px-4 border">{{ $produk->nama }}</td>
                        <td class="py-2 px-4 border text-right">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 border text-center">
                            <span class="px-2 py-1 text-xs rounded
                                {{ $produk->stok < 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $produk->stok }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border">{{ $produk->kategori ?? '-' }}</td>
                        <td class="py-2 px-4 border">{{ $produk->deskripsi ?? '-' }}</td>
                        <td class="py-2 px-4 border text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('produks.show', $produk->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('produks.edit', $produk->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('produks.destroy', $produk->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 px-4 border text-center text-gray-500">Tidak ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $produks->links() }}
        </div>
    </div>
@endsection