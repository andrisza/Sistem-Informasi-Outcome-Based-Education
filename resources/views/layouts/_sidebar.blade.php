{{-- Partial: navigasi sidebar — menu disesuaikan per peran --}}

@php
    $role = auth()->user()->role->value;
    $kurikulumCtx = request()->route('kurikulum');

    // ── Helper: cek apakah satu item aktif ────────────────────────────────────
    $isItemActive = function (array $item) {
        if (isset($item['href'])) {
            // Bandingkan path + query saja (abaikan host/scheme agar tidak mismatch APP_URL)
            $parsed  = parse_url($item['href']);
            $curPath = ltrim(request()->getPathInfo(), '/');
            $tgtPath = ltrim($parsed['path'] ?? '', '/');
            if ($curPath !== $tgtPath) return false;
            parse_str($parsed['query'] ?? '', $tgtQuery);
            foreach ($tgtQuery as $k => $v) {
                if (request()->query($k) !== $v) return false;
            }
            return true;
        }

        $routeName = $item['route'] ?? null;
        if (!$routeName) return false;

        if (request()->routeIs($routeName)) return true;

        // Prefix-matching hanya untuk resource route (3+ bagian, suffix adalah CRUD action)
        $parts    = explode('.', $routeName);
        $lastPart = end($parts);
        $crudSuffixes = ['index', 'show', 'create', 'edit', 'store', 'update', 'destroy'];
        if (count($parts) >= 3 && in_array($lastPart, $crudSuffixes)) {
            $prefix = implode('.', array_slice($parts, 0, -1));
            return request()->routeIs($prefix . '.*');
        }
        return false;
    };

    // ── Helper: apakah grup harus terbuka (expand) ───────────────────────────
    // true jika openWhen cocok ATAU salah satu child aktif
    $groupIsOpen = function (array $group) use ($isItemActive) {
        if (isset($group['openWhen']) && request()->routeIs($group['openWhen'])) return true;
        foreach ($group['children'] ?? [] as $child) {
            if ($isItemActive($child)) return true;
        }
        return false;
    };

    // ── Helper: apakah salah satu child langsung sedang aktif (untuk styling) ─
    // Dipakai untuk membedakan state "active" vs "open via openWhen"
    $groupHasActiveChild = function (array $group) use ($isItemActive) {
        foreach ($group['children'] ?? [] as $child) {
            if ($isItemActive($child)) return true;
        }
        return false;
    };

    // ── Helper: bangun href dari item ────────────────────────────────────────
    $itemHref = function (array $item) {
        if (isset($item['href'])) return $item['href'];
        $route = $item['route'] ?? null;
        if (!$route) return '#';
        return isset($item['params']) ? route($route, $item['params']) : route($route);
    };

    // ── Sub-menu kontekstual saat user berada di halaman kurikulum tertentu ──
    $contextualKurikulumMenus = [];
    if ($kurikulumCtx) {
        $contextualKurikulumMenus = [
            ['divider' => true],
            ['section' => $kurikulumCtx->kode . ' — OBE'],

            // Ikhtisar — halaman ringkasan utama kurikulum
            ['label' => 'Ikhtisar', 'route' => 'kurikulum.show', 'icon' => 'grid', 'params' => [$kurikulumCtx]],

            // Data OBE — komponen penyusun kurikulum
            ['group' => 'Data OBE', 'icon' => 'book-open', 'children' => [
                ['label' => 'Profil Lulusan', 'route' => 'kurikulum.pl.index',           'icon' => 'academic-cap',  'params' => [$kurikulumCtx]],
                ['label' => 'CPL Prodi',      'route' => 'kurikulum.cpl-prodi.index',    'icon' => 'check-circle',  'params' => [$kurikulumCtx]],
                ['label' => 'Bahan Kajian',   'route' => 'kurikulum.bahan-kajian.index', 'icon' => 'book-open',     'params' => [$kurikulumCtx]],
                ['label' => 'Mata Kuliah',    'route' => 'kurikulum.mata-kuliah.index',  'icon' => 'document',      'params' => [$kurikulumCtx]],
                ['label' => 'Distribusi Smt', 'route' => 'kurikulum.distribusi-semester','icon' => 'collection',    'params' => [$kurikulumCtx]],
            ]],

            // Peta & Matriks — visualisasi hubungan antar komponen OBE
            ['group' => 'Peta & Matriks', 'icon' => 'map', 'children' => [
                ['label' => 'Matriks CPL↔PL',    'route' => 'kurikulum.pivot.pl-cpl',    'icon' => 'map',   'params' => [$kurikulumCtx]],
                ['label' => 'Matriks CPL↔BK',    'route' => 'kurikulum.pivot.cpl-bk',    'icon' => 'table', 'params' => [$kurikulumCtx]],
                ['label' => 'Matriks MK↔BK',     'route' => 'kurikulum.pivot.mk-bk',     'icon' => 'table', 'params' => [$kurikulumCtx]],
                ['label' => 'Matriks MK↔CPL',    'route' => 'kurikulum.pivot.mk-cpl',    'icon' => 'table', 'params' => [$kurikulumCtx]],
                ['label' => 'Matriks CPL↔CPMK',  'route' => 'kurikulum.pivot.cpl-cpmk',  'icon' => 'table', 'params' => [$kurikulumCtx]],
                ['label' => 'Matriks CPL↔BK↔MK', 'route' => 'kurikulum.pivot.cpl-bk-mk', 'icon' => 'grid',  'params' => [$kurikulumCtx]],
            ]],

            // Ringkasan — laporan dan capaian
            ['group' => 'Ringkasan', 'icon' => 'document-report', 'children' => [
                ['label' => 'Peta Pemenuhan CPL', 'route' => 'kurikulum.overview.pemenuhan-cpl', 'icon' => 'check-circle', 'params' => [$kurikulumCtx]],
                ['label' => 'CPMK & Sub-CPMK',   'route' => 'kurikulum.overview.cpmk',          'icon' => 'document',     'params' => [$kurikulumCtx]],
                ['label' => 'Rumusan Akhir',      'route' => 'kurikulum.overview.rumusan-akhir', 'icon' => 'calculator',   'params' => [$kurikulumCtx]],
            ]],

            // Administrasi — arsip dan pengelolaan tim
            ['group' => 'Administrasi', 'icon' => 'cog', 'children' => [
                ['label' => 'Arsip Rapat',   'route' => 'kurikulum.arsip-rapat.index', 'icon' => 'meeting',  'params' => [$kurikulumCtx]],
                ['label' => 'Tim Kurikulum', 'route' => 'kurikulum.tim.index',         'icon' => 'users',    'params' => [$kurikulumCtx]],
                ['label' => 'Periode',       'route' => 'kurikulum.periode.index',     'icon' => 'calendar', 'params' => [$kurikulumCtx]],
            ]],
        ];
    }

    // ── Menu per peran ───────────────────────────────────────────────────────
    $kaprodiMenus = array_merge([
        ['label' => 'Dashboard',         'route' => 'kaprodi.dashboard',               'icon' => 'home'],
        ['label' => 'Semester Akademik', 'route' => 'kaprodi.semester-akademik.index', 'icon' => 'calendar'],
        // openWhen: tetap terbuka di semua halaman kurikulum (termasuk sub-halaman spesifik)
        ['group' => 'Kurikulum & RPS', 'icon' => 'book-open', 'openWhen' => 'kurikulum.*', 'children' => [
            ['label' => 'Kurikulum',       'route' => 'kurikulum.index',                                            'icon' => 'book-open'],
            ['label' => 'Arsip Kurikulum', 'href'  => route('kurikulum.index', ['status' => 'arsip']), 'icon' => 'archive'],
            ['label' => 'Persetujuan RPS', 'route' => 'kaprodi.rps-approval.index',                                 'icon' => 'document-check'],
        ]],
        ['group' => 'Evaluasi & Laporan', 'icon' => 'chart-bar', 'children' => [
            ['label' => 'Evaluasi CQI',  'route' => 'kaprodi.cqi.index',     'icon' => 'chart-bar'],
            ['label' => 'Laporan OBE',   'route' => 'kaprodi.reports.index', 'icon' => 'document-report'],
        ]],
        ['group' => 'Sistem', 'icon' => 'cog', 'children' => [
            ['label' => 'Audit Trail',    'route' => 'kaprodi.activity-log.index', 'icon' => 'clock'],
            ['label' => 'Manajemen User', 'route' => 'kaprodi.users.index',        'icon' => 'users'],
        ]],
    ], $contextualKurikulumMenus);

    $kurikulumMenus = array_merge([
        ['label' => 'Dashboard', 'route' => 'kurikulum.dashboard', 'icon' => 'home'],
        // openWhen: tetap terbuka di semua halaman kurikulum
        ['group' => 'Kurikulum', 'icon' => 'book-open', 'openWhen' => 'kurikulum.*', 'children' => [
            ['label' => 'Semua Kurikulum', 'route' => 'kurikulum.index',                                            'icon' => 'book-open'],
            ['label' => 'Arsip Kurikulum', 'href'  => route('kurikulum.index', ['status' => 'arsip']), 'icon' => 'archive'],
        ]],
    ], $contextualKurikulumMenus);

    $dosenMenus = [
        ['label' => 'Dashboard', 'route' => 'dosen.dashboard', 'icon' => 'home'],
        ['group' => 'Pembelajaran', 'icon' => 'academic-cap', 'children' => [
            ['label' => 'Pengampuan MK',   'route' => 'dosen.pengampuan',    'icon' => 'academic-cap'],
            ['label' => 'RPS Saya',        'route' => 'dosen.rps.index',     'icon' => 'document'],
            ['label' => 'Jurnal Mengajar', 'route' => 'dosen.jurnal.index',  'icon' => 'pencil'],
        ]],
        ['group' => 'Penilaian & Materi', 'icon' => 'chart-bar', 'children' => [
            ['label' => 'Input Nilai',  'route' => 'dosen.nilai.index',  'icon' => 'calculator'],
            ['label' => 'Materi Ajar',  'route' => 'dosen.materi.index', 'icon' => 'folder'],
        ]],
    ];

    $mahasiswaMenus = [
        ['label' => 'Dashboard', 'route' => 'mahasiswa.dashboard', 'icon' => 'home'],
        ['group' => 'Akademik', 'icon' => 'collection', 'children' => [
            ['label' => 'Nilai Saya',    'route' => 'mahasiswa.nilai.index',  'icon' => 'chart-bar'],
            ['label' => 'Materi Kuliah', 'route' => 'mahasiswa.materi.index', 'icon' => 'folder'],
        ]],
        ['group' => 'Capaian OBE', 'icon' => 'check-circle', 'children' => [
            ['label' => 'Progress CPL', 'route' => 'mahasiswa.cpl-progress.index', 'icon' => 'trending-up'],
            ['label' => 'Progress PL',  'route' => 'mahasiswa.pl-progress',        'icon' => 'radar'],
        ]],
    ];

    $menus = match($role) {
        'kaprodi'       => $kaprodiMenus,
        'tim_kurikulum' => $kurikulumMenus,
        'dosen'         => $dosenMenus,
        'mahasiswa'     => $mahasiswaMenus,
        default         => [],
    };
@endphp

@foreach ($menus as $menu)

    @if (isset($menu['divider']))
        {{-- Pembatas visual sebelum kontekstual kurikulum --}}
        <div class="my-2 border-t border-slate-700/50"></div>

    @elseif (isset($menu['section']))
        {{-- Label seksi flat (non-collapsible) --}}
        <div class="px-3 pt-4 pb-1">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ $menu['section'] }}</p>
        </div>

    @elseif (isset($menu['children']))
        {{-- ── Dropdown collapsible group ───────────────────────────────── --}}
        @php
            $open      = $groupIsOpen($menu);
            $btnActive = $groupHasActiveChild($menu);
        @endphp

        <div class="sidebar-group" data-open="{{ $open ? '1' : '0' }}">
            <button type="button"
                    data-state="{{ $btnActive ? 'active' : ($open ? 'open' : 'closed') }}"
                    class="group-toggle w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                @include('layouts._icon', ['name' => $menu['icon'] ?? 'folder', 'class' => 'w-4 h-4 shrink-0'])
                <span class="flex-1 text-left">{{ $menu['group'] }}</span>
                <svg class="chevron w-3.5 h-3.5 shrink-0 transition-transform duration-150 {{ $open ? 'rotate-180' : '' }}"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="group-children overflow-hidden pl-3 mt-0.5 space-y-0.5 {{ $open ? '' : 'hidden' }}">
                @foreach ($menu['children'] as $child)
                    @php
                        $childActive = $isItemActive($child);
                        $childHref   = $itemHref($child);
                    @endphp
                    <a href="{{ $childHref }}"
                       class="{{ $childActive
                               ? 'bg-blue-600 text-white'
                               : 'text-slate-400 hover:bg-slate-700/60 hover:text-white' }}
                              flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                        @include('layouts._icon', ['name' => $child['icon'] ?? 'dot', 'class' => 'w-4 h-4 shrink-0'])
                        {{ $child['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

    @elseif (isset($menu['label']))
        {{-- ── Link biasa ───────────────────────────────────────────────── --}}
        @php
            $active = $isItemActive($menu);
            $href   = $itemHref($menu);
        @endphp

        <a href="{{ $href }}"
           class="{{ $active
               ? 'bg-slate-700 text-white'
               : 'text-slate-400 hover:bg-slate-700/60 hover:text-white' }}
                  flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
            @include('layouts._icon', ['name' => $menu['icon'] ?? 'dot', 'class' => 'w-4 h-4 shrink-0'])
            {{ $menu['label'] }}
        </a>

    @endif

@endforeach

{{-- Styling berbasis data-state — tiga state tombol grup dropdown --}}
<style>
/* Tombol grup: child sedang aktif → background + teks putih */
.group-toggle[data-state="active"] {
    background-color: rgb(51 65 85);
    color: rgb(255 255 255);
}
/* Tombol grup: terbuka via openWhen tapi tidak ada child aktif → teks agak terang, tanpa background */
.group-toggle[data-state="open"] {
    color: rgb(203 213 225);
}
/* Tombol grup: tertutup → teks redup */
.group-toggle[data-state="closed"] {
    color: rgb(148 163 184);
}
/* Hover untuk state non-active */
.group-toggle[data-state="open"]:hover,
.group-toggle[data-state="closed"]:hover {
    background-color: rgba(51, 65, 85, 0.6);
    color: rgb(255 255 255);
}
</style>

{{-- Toggle JavaScript untuk dropdown sidebar --}}
<script>
(function () {
    var PREFIX = 'sidebar:group:';

    function openGroup(btn, children, chevron) {
        children.classList.remove('hidden');
        chevron.classList.add('rotate-180');
        // Jika ada child aktif → state "active", jika tidak → "open"
        var hasActive = !!children.querySelector('a.bg-blue-600');
        btn.setAttribute('data-state', hasActive ? 'active' : 'open');
    }

    function closeGroup(btn, children, chevron) {
        children.classList.add('hidden');
        chevron.classList.remove('rotate-180');
        btn.setAttribute('data-state', 'closed');
    }

    document.querySelectorAll('.sidebar-group').forEach(function (group) {
        var btn      = group.querySelector('.group-toggle');
        var children = group.querySelector('.group-children');
        var chevron  = group.querySelector('.chevron');
        var labelEl  = btn && btn.querySelector('span.flex-1');

        // Guard: skip jika elemen tidak lengkap (cegah crash yg hentikan loop)
        if (!btn || !children || !chevron || !labelEl) return;

        var label      = labelEl.textContent.trim();
        var storageKey = PREFIX + label;
        var serverOpen = group.dataset.open === '1';

        // Hanya restore localStorage jika server tidak memaksa buka (tidak ada openWhen / active child)
        if (!serverOpen && localStorage.getItem(storageKey) === '1') {
            openGroup(btn, children, chevron);
        }

        btn.addEventListener('click', function () {
            var isOpen = !children.classList.contains('hidden');
            if (isOpen) {
                closeGroup(btn, children, chevron);
                localStorage.setItem(storageKey, '0');
            } else {
                openGroup(btn, children, chevron);
                localStorage.setItem(storageKey, '1');
            }
        });
    });
})();
</script>
