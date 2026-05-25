@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')

    <h2 class="text-xl font-bold text-gray-900 mb-1">Selamat datang kembali</h2>
    <p class="text-sm text-gray-500 mb-6">Masuk ke akun SI-OBE Anda</p>

    {{-- Error global --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-5">
            @foreach ($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Email
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                autofocus
                required
                placeholder="nama@kampus.ac.id"
                class="w-full border rounded-lg px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                       {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
            >
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                Password
            </label>
            <input
                id="password"
                type="password"
                name="password"
                autocomplete="current-password"
                required
                placeholder="••••••••"
                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
        </div>

        {{-- Remember me --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" value="1"
                       class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg px-4 py-2.5
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            Masuk
        </button>
    </form>

    {{-- Info peran --}}
    <div class="mt-6 pt-5 border-t border-gray-100">
        <p class="text-xs text-gray-400 text-center mb-3">Akses sistem berdasarkan peran</p>
        <div class="grid grid-cols-2 gap-2 text-xs text-center">
            <div class="bg-blue-50 text-blue-700 rounded-lg py-2 px-3 font-medium">Kaprodi</div>
            <div class="bg-violet-50 text-violet-700 rounded-lg py-2 px-3 font-medium">Tim Kurikulum</div>
            <div class="bg-emerald-50 text-emerald-700 rounded-lg py-2 px-3 font-medium">Dosen</div>
            <div class="bg-amber-50 text-amber-700 rounded-lg py-2 px-3 font-medium">Mahasiswa</div>
        </div>
    </div>

@endsection
