<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sistem Kasir') }} - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
                <nav class="bg-grey-50 text-blue-900 shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/bilmart1.png') }}" alt="Logo" class="h-[90px] w-auto">
                        </a>
                    </div>
                    <div class="space-x-6 text-base font-medium">
                        <a href="{{ route('dashboard') }}" class="hover:text-blue-500">Dashboard</a>
                        <a href="{{ route('penjualans.index') }}" class="hover:text-blue-500">Penjualan</a>
                        <a href="{{ route('pelanggans.index') }}" class="hover:text-blue-500">Pelanggan</a>
                        <a href="{{ route('produks.index') }}" class="hover:text-blue-500">Produk</a>
                    </div>
                </div>
            </div>
        </nav>


        <!-- Content -->
        <main class="flex-grow container mx-auto px-4 py-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>


    </div>

    @yield('scripts')
</body>
</html>