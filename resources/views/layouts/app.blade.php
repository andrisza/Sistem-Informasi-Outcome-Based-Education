<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SI-OBE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #app-sidebar { transition: width 0.22s ease; }
        #app-sidebar .sidebar-content { min-width: 16rem; }
    </style>
</head>
<body class="h-full flex overflow-hidden">

{{-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════════ --}}
<aside id="app-sidebar"
       class="bg-slate-900 flex flex-col shrink-0 overflow-y-auto overflow-x-hidden"
       style="width:16rem">

    {{-- Logo --}}
    <div class="sidebar-content flex items-center gap-3 px-5 py-5 border-b border-slate-700">
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
    <nav class="sidebar-content flex-1 px-3 py-4 space-y-0.5">
        @include('layouts._sidebar')
    </nav>

    {{-- User footer --}}
    <div class="sidebar-content px-3 py-4 border-t border-slate-700">
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
<div id="app-main" class="flex-1 flex flex-col overflow-hidden min-w-0">

    {{-- Top header --}}
    <header class="bg-white border-b border-gray-200 px-4 py-2.5 flex items-center gap-3 shrink-0">
        {{-- Sidebar toggle --}}
        <button id="sidebar-toggle-btn"
                onclick="toggleSidebar()"
                title="Tampilkan / Sembunyikan Sidebar"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Page title (compressed) --}}
        <div class="min-w-0 hidden md:block" style="max-width:240px">
            <h2 class="text-gray-900 font-semibold text-sm truncate leading-tight">@yield('header', 'Dashboard')</h2>
            @hasSection('breadcrumb')
            <nav class="flex items-center gap-1 text-[10px] text-gray-400 flex-wrap truncate">
                @yield('breadcrumb')
            </nav>
            @endif
        </div>

        {{-- ══ INLINE GLOBAL SEARCH BAR ══ --}}
        <div id="global-search-wrap" class="flex-1 relative" style="max-width:460px">
            {{-- Input --}}
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus-within:bg-white focus-within:border-amber-400 focus-within:ring-2 focus-within:ring-amber-100 transition-all">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="global-search-input"
                       placeholder="Cari menu, fitur, halaman..."
                       autocomplete="off"
                       spellcheck="false"
                       class="flex-1 text-sm bg-transparent outline-none text-gray-800 placeholder-gray-400 min-w-0">
                <kbd id="global-search-kbd"
                     class="hidden sm:inline-flex items-center px-1.5 py-0.5 bg-gray-200 rounded text-[10px] font-mono text-gray-500 border border-gray-300 select-none shrink-0">
                    Ctrl K
                </kbd>
                <button type="button"
                        id="global-search-clear"
                        class="hidden p-0.5 text-gray-400 hover:text-gray-600 rounded shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Dropdown hasil pencarian — muncul LURUS ke bawah --}}
            <div id="global-search-dropdown"
                 class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white rounded-xl border border-gray-200 shadow-xl z-[200] overflow-hidden"
                 style="max-height:420px;overflow-y:auto">
                {{-- Diisi oleh JS --}}
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0 ml-auto">
            @yield('header-actions')

            {{-- Notification bell --}}
            @php
                $unreadCount = \App\Models\Notifikasi::where('id_user', auth()->id())->where('dibaca', 0)->count();
            @endphp
            <a href="{{ route('notifikasi.index') }}"
               class="relative p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
               title="Notifikasi">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if ($unreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1 overflow-y-auto overflow-x-auto px-6 py-6">
        @yield('content')
    </main>
</div>

@stack('scripts')

{{-- Global Command Palette (search menu) --}}
@include('layouts._command-palette')

{{-- Global floating tooltip (data-tooltip + data-tip-label) --}}
@include('layouts._pivot-tooltip')

{{-- ═══════════════════════════════ TOAST NOTIFICATIONS ════════════════════════ --}}
<div id="toast-container"
     class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none"
     style="min-width:320px;max-width:420px">
</div>

@php
    $toasts = [];
    if (session('success')) $toasts[] = ['type'=>'success','msg'=>session('success')];
    if (session('error'))   $toasts[] = ['type'=>'error',  'msg'=>session('error')];
    if (session('warning')) $toasts[] = ['type'=>'warning', 'msg'=>session('warning')];
    if ($errors->any())     $toasts[] = ['type'=>'error',   'msg'=>implode(' ', $errors->all())];
@endphp

@if (count($toasts))
<script>
(function(){
    const toasts = @json($toasts);
    document.addEventListener('DOMContentLoaded', function(){
        toasts.forEach(function(t){ window._siToast(t.type, t.msg); });
    });
})();
</script>
@endif

{{-- Sidebar toggle logic --}}
<script>
(function () {
    const sidebar = document.getElementById('app-sidebar');
    const KEY = 'si_obe_sidebar_open';

    const savedState = localStorage.getItem(KEY);
    if (savedState === '0') {
        sidebar.style.width = '0';
    } else {
        sidebar.style.width = '16rem';
    }

    window.toggleSidebar = function () {
        const isOpen = sidebar.style.width !== '0px' && sidebar.style.width !== '0';
        sidebar.style.width = isOpen ? '0' : '16rem';
        localStorage.setItem(KEY, isOpen ? '0' : '1');
    };
})();

/* ── Toast system ───────────────────────────────────────────────────── */
(function () {
    const DURATION = 4500; // ms sebelum auto-dismiss

    const CONFIGS = {
        success: {
            bar:  '#22c55e',
            bg:   '#f0fdf4',
            border:'#bbf7d0',
            text: '#166534',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
            iconColor: '#22c55e',
        },
        error: {
            bar:  '#ef4444',
            bg:   '#fef2f2',
            border:'#fecaca',
            text: '#991b1b',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>',
            iconColor: '#ef4444',
        },
        warning: {
            bar:  '#f59e0b',
            bg:   '#fffbeb',
            border:'#fde68a',
            text: '#92400e',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
            iconColor: '#f59e0b',
        },
        info: {
            bar:  '#3b82f6',
            bg:   '#eff6ff',
            border:'#bfdbfe',
            text: '#1e40af',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            iconColor: '#3b82f6',
        },
    };

    window._siToast = function (type, message) {
        const cfg = CONFIGS[type] || CONFIGS.info;
        const container = document.getElementById('toast-container');

        const wrap = document.createElement('div');
        wrap.style.cssText = [
            'pointer-events:auto',
            'position:relative',
            'overflow:hidden',
            'display:flex',
            'align-items:flex-start',
            'gap:10px',
            'padding:12px 14px',
            'border-radius:12px',
            'border:1px solid ' + cfg.border,
            'background:' + cfg.bg,
            'color:' + cfg.text,
            'font-size:13.5px',
            'line-height:1.45',
            'box-shadow:0 4px 16px rgba(0,0,0,.10)',
            'transform:translateX(110%)',
            'transition:transform .3s cubic-bezier(.4,0,.2,1), opacity .3s ease',
            'opacity:0',
        ].join(';');

        // Icon
        const iconWrap = document.createElement('div');
        iconWrap.style.cssText = 'flex-shrink:0;margin-top:1px';
        iconWrap.innerHTML = `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="${cfg.iconColor}" stroke-width="2.5">${cfg.icon}</svg>`;

        // Message
        const msgEl = document.createElement('div');
        msgEl.style.cssText = 'flex:1;min-width:0;padding-right:4px';
        msgEl.textContent = message;

        // Close button
        const closeBtn = document.createElement('button');
        closeBtn.style.cssText = 'flex-shrink:0;background:none;border:none;cursor:pointer;padding:0;opacity:.5;transition:opacity .15s;margin-top:1px';
        closeBtn.innerHTML = `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`;
        closeBtn.onmouseenter = () => closeBtn.style.opacity = '1';
        closeBtn.onmouseleave = () => closeBtn.style.opacity = '.5';
        closeBtn.onclick = () => dismiss(wrap, bar, timerId);

        // Progress bar
        const bar = document.createElement('div');
        bar.style.cssText = [
            'position:absolute',
            'bottom:0',
            'left:0',
            'height:3px',
            'width:100%',
            'background:' + cfg.bar,
            'transform-origin:left',
            'transition:transform ' + DURATION + 'ms linear',
        ].join(';');

        wrap.append(iconWrap, msgEl, closeBtn, bar);
        container.appendChild(wrap);

        // Slide in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                wrap.style.transform = 'translateX(0)';
                wrap.style.opacity  = '1';
                bar.style.transform = 'scaleX(0)';
            });
        });

        const timerId = setTimeout(() => dismiss(wrap, bar, timerId), DURATION);

        // Pause on hover
        wrap.onmouseenter = () => {
            clearTimeout(timerId);
            bar.style.transition = 'none';
        };
        wrap.onmouseleave = () => {
            bar.style.transition = 'transform 1500ms linear';
            bar.style.transform  = 'scaleX(0)';
            const newTimer = setTimeout(() => dismiss(wrap, bar, newTimer), 1500);
            closeBtn.onclick = () => dismiss(wrap, bar, newTimer);
        };
    };

    function dismiss(wrap, bar, timerId) {
        clearTimeout(timerId);
        wrap.style.transform = 'translateX(110%)';
        wrap.style.opacity   = '0';
        setTimeout(() => wrap.remove(), 320);
    }
})();
</script>
</body>
</html>
