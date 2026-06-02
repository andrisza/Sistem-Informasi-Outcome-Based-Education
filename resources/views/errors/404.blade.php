<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Halaman Tidak Ditemukan | SI-OBE</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-full flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 rounded-2xl bg-blue-100">
            <svg class="w-10 h-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM13.5 10.5h-6"/>
            </svg>
        </div>
        <p class="text-5xl font-black text-blue-500 mb-2">404</p>
        <h1 class="text-xl font-bold text-gray-900 mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-sm text-gray-600 mb-6">
            Halaman yang Anda cari tidak ada atau sudah dipindahkan.
        </p>
        <div class="flex gap-3 justify-center">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                Kembali
            </a>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                Ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
