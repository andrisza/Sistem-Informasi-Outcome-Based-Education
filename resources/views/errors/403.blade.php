<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Akses Ditolak | SI-OBE</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-full flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        {{-- Icon --}}
        <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 rounded-2xl bg-red-100">
            <svg class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z"/>
            </svg>
        </div>

        {{-- Kode error --}}
        <p class="text-5xl font-black text-red-500 mb-2">403</p>

        {{-- Judul --}}
        <h1 class="text-xl font-bold text-gray-900 mb-2">Akses Ditolak</h1>

        {{-- Pesan --}}
        <p class="text-sm text-gray-600 mb-6 leading-relaxed">
            @if (!empty($exception->getMessage()))
                {{ $exception->getMessage() }}
            @else
                Anda tidak memiliki izin untuk mengakses halaman ini.
                Pastikan Anda login dengan akun yang sesuai atau hubungi Administrator.
            @endif
        </p>

        {{-- Panduan --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-left mb-6 text-xs text-amber-800">
            <p class="font-semibold mb-1.5">Kemungkinan penyebab:</p>
            <ul class="space-y-1 list-disc list-inside">
                <li>Anda belum ditugaskan ke Mata Kuliah ini (Pengampuan MK)</li>
                <li>RPS ini dimiliki oleh Dosen lain</li>
                <li>Akun Anda tidak memiliki peran yang sesuai</li>
                <li>Semester pengampuan tidak cocok dengan semester RPS</li>
            </ul>
        </div>

        {{-- Tombol aksi --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}"
               class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Ke Dashboard
            </a>
        </div>

        {{-- RPS khusus: link ke pengampuan --}}
        @auth
            @if (auth()->user()->isKaprodi())
                <p class="mt-5 text-xs text-gray-500">
                    Sebagai Kaprodi, Anda bisa mengatur pengampuan di
                    <a href="{{ route('kaprodi.pengampuan.index') }}" class="text-blue-600 hover:underline font-medium">
                        Manajemen Pengampuan MK
                    </a>
                </p>
            @elseif (auth()->user()->isDosen())
                <p class="mt-5 text-xs text-gray-500">
                    Hubungi Kaprodi untuk memastikan Anda sudah ditugaskan ke Mata Kuliah ini.
                </p>
            @endif
        @endauth
    </div>
</body>
</html>
