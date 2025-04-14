@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Daftar Penjualan</h2>
        <a href="{{ route('penjualans.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            <i class="fas fa-plus mr-1"></i> Tambah Penjualan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Pencarian -->
    <div class="mb-4">
            <form action="{{ route('penjualans.index') }}" method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode invoice..." class="border p-2 rounded w-full">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Cari
                </button>
            </form>
        </div>


    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="py-2 px-4 border">Kode Invoice</th>
                    <th class="py-2 px-4 border">Pelanggan</th>
                    <th class="py-2 px-4 border">Tanggal</th>
                    <th class="py-2 px-4 border">Total Harga</th>
                    <th class="py-2 px-4 border">Status Pembayaran</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualans as $penjualan)
                <tr class="hover:bg-gray-100">
                    <td class="py-2 px-4 border">{{ $penjualan->kode_invoice }}</td>
                    <td class="py-2 px-4 border">{{ $penjualan->pelanggan->nama ?? 'Umum' }}</td>
                    <td class="py-2 px-4 border">{{ $penjualan->tanggal }}</td>
                    <td class="py-2 px-4 border">Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                    <td class="py-2 px-4 border">
                        <span class="px-2 py-1 text-sm rounded 
                            {{ $penjualan->status_pembayaran == 'lunas' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                            {{ ucfirst($penjualan->status_pembayaran) }}
                        </span>
                    </td>
                    <td class="py-2 px-4 border flex space-x-2">
                        <a href="{{ route('penjualans.show', $penjualan->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('penjualans.edit', $penjualan->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('penjualans.destroy', $penjualan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
    {{ $penjualans->links() }}
    </div>
</div>
@endsection