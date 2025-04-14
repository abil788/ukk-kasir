@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Detail Pelanggan</h1>
            <a href="{{ route('pelanggans.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-sm font-medium text-gray-700">Nama</h2>
                <p class="text-lg font-semibold text-gray-900">{{ $pelanggan->nama }}</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Nomor Telepon</h2>
                <p class="text-lg font-semibold text-gray-900">{{ $pelanggan->telepon ?? '-' }}</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Email</h2>
                <p class="text-lg font-semibold text-gray-900">{{ $pelanggan->email ?? '-' }}</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-700">Tanggal Registrasi</h2>
                <p class="text-lg font-semibold text-gray-900">{{ $pelanggan->tanggal_registrasi->format('d/m/Y') }}</p>
            </div>

            <div class="col-span-2">
                <h2 class="text-sm font-medium text-gray-700">Alamat</h2>
                <p class="text-lg text-gray-900">{{ $pelanggan->alamat ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-6 flex space-x-4">
            <a href="{{ route('pelanggans.edit', $pelanggan->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('pelanggans.destroy', $pelanggan->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus pelanggan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
@endsection