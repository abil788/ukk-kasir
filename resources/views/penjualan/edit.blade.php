@extends('layouts.app')

@section('title', 'Edit Penjualan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Penjualan</h1>
    </div>

    <form action="{{ route('penjualans.update', $penjualan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                <select name="pelanggan_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Umum</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}" {{ $penjualan->pelanggan_id == $pelanggan->id ? 'selected' : '' }}>{{ $pelanggan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $penjualan->tanggal }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
            <select name="status_pembayaran" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="pending" {{ $penjualan->status_pembayaran == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="lunas" {{ $penjualan->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="tunai" {{ $penjualan->metode_pembayaran == 'tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="transfer" {{ $penjualan->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('penjualans.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection