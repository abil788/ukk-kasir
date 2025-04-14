@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Pelanggan</h1>
            <a href="{{ route('pelanggans.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                <i class="fas fa-plus mr-1"></i> Tambah Pelanggan
            </a>
        </div>

        <!-- Form Pencarian -->
        <div class="mb-4">
            <form action="{{ route('pelanggans.index') }}" method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." class="border p-2 rounded w-full">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border text-left">ID</th>
                        <th class="py-2 px-4 border text-left">Nama</th>
                        <th class="py-2 px-4 border text-left">Telepon</th>
                        <th class="py-2 px-4 border text-left">Email</th>
                        <th class="py-2 px-4 border text-left">Tanggal Registrasi</th>
                        <th class="py-2 px-4 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $pelanggan)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border">{{ $pelanggan->id }}</td>
                        <td class="py-2 px-4 border">{{ $pelanggan->nama }}</td>
                        <td class="py-2 px-4 border">{{ $pelanggan->telepon }}</td>
                        <td class="py-2 px-4 border">{{ $pelanggan->email }}</td>
                        <td class="py-2 px-4 border">{{ $pelanggan->tanggal_registrasi->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('pelanggans.show', $pelanggan->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pelanggans.edit', $pelanggan->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pelanggans.destroy', $pelanggan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus pelanggan ini?');">
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
                        <td colspan="6" class="py-4 px-4 border text-center text-gray-500">Tidak ada data pelanggan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pelanggans->appends(['search' => request('search')])->links() }}
        </div>
    </div>
@endsection
