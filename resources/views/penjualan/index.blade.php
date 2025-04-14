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

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
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
                        <button onclick="showDeleteModal({{ $penjualan->id }})" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
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

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h3 class="text-lg font-semibold mb-2">Konfirmasi Hapus</h3>
        <p class="mb-4">Masukkan kode keamanan untuk menghapus penjualan.</p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="password" name="kode_keamanan" placeholder="Masukkan kode" required class="border p-2 w-full rounded mb-4">
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showDeleteModal(id) {
        const form = document.getElementById('deleteForm');
        form.action = '/penjualans/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
