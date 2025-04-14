@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">🛒 Tambah Penjualan</h1>
    </div>

    <form action="{{ route('penjualans.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Pelanggan (Opsional)</label>
                <select name="pelanggan_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Umum</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <h5 class="text-lg font-semibold text-gray-700 mb-4">🧾 Produk</h5>
            <div id="produk-container" class="space-y-4">
                <div class="flex flex-wrap gap-4 produk-item">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Produk</label>
                        <select name="produk_id[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}" @if($produk->stok == 0) disabled @endif>
                                    {{ $produk->nama }} - Rp{{ number_format($produk->harga, 0, ',', '.') }} 
                                    @if($produk->stok == 0) (Stok Habis) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-28">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah</label>
                        <input type="number" name="jumlah[]" min="1" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="btn-remove-produk text-red-500 hover:text-red-700 hidden">
                            <i class="fas fa-trash-alt text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" id="tambah-produk" class="mt-4 inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow">
                <i class="fas fa-plus mr-2"></i> Tambah Produk
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Status Pembayaran</label>
                <select name="status_pembayaran" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="pending">Pending</option>
                    <option value="lunas">Lunas</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Metode Pembayaran</label>
                <select name="metode_pembayaran" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Total Belanja</label>
                <input type="text" id="total-belanja" readonly class="w-full bg-gray-100 text-gray-700 border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Uang Pelanggan</label>
                <input type="number" name="uang_pelanggan" id="uang-pelanggan" min="0" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
        </div>

        <div class="pt-2">
            <label class="block text-sm font-semibold text-gray-600 mb-1">Kembalian</label>
            <input type="text" id="kembalian" readonly class="w-full bg-gray-100 text-gray-700 border border-gray-300 rounded-lg px-4 py-2">
        </div>

        <div class="pt-8 flex items-center justify-between border-t border-gray-200">
            <a href="{{ route('penjualans.index') }}" class="text-sm text-gray-600 hover:text-blue-600 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow inline-flex items-center">
                <i class="fas fa-save mr-2"></i> Simpan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('tambah-produk').addEventListener('click', function() {
        let container = document.getElementById('produk-container');
        let newRow = container.firstElementChild.cloneNode(true);
        newRow.querySelector('input').value = '';
        newRow.querySelector('.btn-remove-produk').style.display = 'inline-block';
        container.appendChild(newRow);
    });

    document.addEventListener('click', function(event) {
        if (event.target.closest('.btn-remove-produk')) {
            let items = document.querySelectorAll('.produk-item');
            if (items.length > 1) {
                event.target.closest('.produk-item').remove();
                hitungTotalDanKembalian();
            }
        }
    });

    function hitungTotalDanKembalian() {
        let total = 0;
        const produkItems = document.querySelectorAll('.produk-item');
        produkItems.forEach(item => {
            const select = item.querySelector('select');
            const jumlahInput = item.querySelector('input[type=number]');
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption) return;

            const hargaText = selectedOption.textContent.split('Rp')[1]?.replace(/\./g, '') || '0';
            const harga = parseInt(hargaText) || 0;
            const jumlah = parseInt(jumlahInput.value) || 0;
            total += harga * jumlah;
        });

        document.getElementById('total-belanja').value = 'Rp' + total.toLocaleString('id-ID');

        const uangPelanggan = parseInt(document.getElementById('uang-pelanggan').value) || 0;
        const kembalian = uangPelanggan - total;
        document.getElementById('kembalian').value = kembalian >= 0 ? 'Rp' + kembalian.toLocaleString('id-ID') : 'Rp0';
    }

    document.addEventListener('input', function(event) {
        if (event.target.matches('input[type=number]') || event.target.matches('select')) {
            hitungTotalDanKembalian();
        }
    });
</script>
@endsection
