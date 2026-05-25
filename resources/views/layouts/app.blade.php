<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SI-OBE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex overflow-hidden">

{{-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════════ --}}
<aside class="w-64 bg-slate-900 flex flex-col shrink-0 overflow-y-auto">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700">
        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-600 shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 7l-9-5 9-5 9 5-9 5z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-white font-bold text-sm leading-tight">SI-OBE</p>
            <p class="text-slate-400 text-xs truncate">{{ auth()->user()->role->label() }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">
        @include('layouts._sidebar')
    </nav>

    {{-- User footer --}}
    <div class="px-3 py-4 border-t border-slate-700">
        <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-800">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center shrink-0 text-white text-xs font-bold uppercase">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-400 text-xs truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white text-xs transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════════ MAIN AREA ═══════════════════════════════════ --}}
<div class="flex-1 flex flex-col overflow-hidden">

    {{-- Top header --}}
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shrink-0">
        <div>
            <h2 class="text-gray-900 font-semibold text-base">@yield('header', 'Dashboard')</h2>
            @hasSection('breadcrumb')
            <nav class="flex items-center gap-1 text-xs text-gray-500 mt-0.5">
                @yield('breadcrumb')
            </nav>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @yield('header-actions')
        </div>
    </header>

    {{-- Flash messages --}}
    <div class="px-6 pt-4">
        @if (session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm mb-0">
                <svg class="w-4 h-4 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm mb-0">
                <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg px-4 py-3 text-sm mb-0">
                <svg class="w-4 h-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ session('warning') }}
            </div>
        @endif
    </div>

    {{-- Page content --}}
    <main class="flex-1 overflow-y-auto px-6 py-6">
        @yield('content')
    </main>
</div>

</body>
</html>
