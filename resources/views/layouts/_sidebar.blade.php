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

            // ── Kunci fix "double active" ──────────────────────────────────────
            // Jika target href TIDAK memiliki query string sama sekali, item ini
            // hanya aktif ketika URL saat ini juga tidak memiliki query string.
            // Contoh: item "/kurikulum" (tanpa ?status=arsip) tidak boleh aktif
            // saat URL adalah "/kurikulum?status=arsip" karena ada item sibling
            // yang lebih spesifik (?status=arsip) yang seharusnya aktif.
            if (empty($tgtQuery) && request()->getQueryString() !== null) {
                return false;
            }

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

            // Data OBE — urutan alur: CPL SN-Dikti → CPL Prodi → Pemetaan SN-P → PL → Bahan Kajian
            ['group' => 'Data OBE', 'icon' => 'book-open', 'children' => [
                ['label' => 'CPL SN-Dikti',          'route' => 'kurikulum.cpl-sndikti.index',  'icon' => 'academic-cap',  'params' => [$kurikulumCtx]],
                ['label' => 'CPL Prodi',             'route' => 'kurikulum.cpl-prodi.index',    'icon' => 'check-circle',  'params' => [$kurikulumCtx]],
                ['label' => 'Pemetaan CPL-SN↔CPL-P', 'route' => 'kurikulum.pivot.cplsn-cplp',  'icon' => 'table',         'params' => [$kurikulumCtx]],
                ['label' => 'Profil Lulusan',        'route' => 'kurikulum.pl.index',           'icon' => 'academic-cap',  'params' => [$kurikulumCtx]],
                ['label' => 'Bahan Kajian',          'route' => 'kurikulum.bahan-kajian.index', 'icon' => 'book-open',     'params' => [$kurikulumCtx]],
            ]],

            // Peta & Matriks — urutan sesuai alur kurikulum OBE
            ['group' => 'Peta & Matriks', 'icon' => 'map', 'children' => [
                ['label' => 'Matriks CPL↔PL',        'route' => 'kurikulum.pivot.pl-cpl',         'icon' => 'map',   'params' => [$kurikulumCtx]],
                ['label' => 'Matriks CPL↔BK',        'route' => 'kurikulum.pivot.cpl-bk',         'icon' => 'table', 'params' => [$kurikulumCtx]],
                ['label' => 'Matriks BK↔MK',         'route' => 'kurikulum.pivot.mk-bk',          'icon' => 'table', 'params' => [$kurikulumCtx]],
                ['label' => 'Matriks CPL↔MK',        'route' => 'kurikulum.pivot.mk-cpl',         'icon' => 'table', 'params' => [$kurikulumCtx]],
                ['label' => 'Matriks CPL↔BK↔MK',    'route' => 'kurikulum.pivot.cpl-bk-mk',      'icon' => 'grid',  'params' => [$kurikulumCtx]],
                ['label' => 'Susunan Mata Kuliah',   'route' => 'kurikulum.mata-kuliah.index',    'icon' => 'document','params' => [$kurikulumCtx]],
                ['label' => 'Organisasi Mata Kuliah','route' => 'kurikulum.organisasi-mk',         'icon' => 'grid',   'params' => [$kurikulumCtx]],
                ['label' => 'Peta Pemenuhan CPL',    'route' => 'kurikulum.overview.pemenuhan-cpl','icon' => 'map',   'params' => [$kurikulumCtx]],
                ['label' => 'Peta CPL–CPMK–MK',     'route' => 'kurikulum.overview.cpl-cpmk-mk',  'icon' => 'table', 'params' => [$kurikulumCtx]],
            ]],

            // Asesmen & Penilaian
            ['group' => 'Asesmen', 'icon' => 'calculator', 'children' => [
                ['label' => 'CPL-CPMK-MK Semester',   'route' => 'kurikulum.penilaian.peta-semester',   'icon' => 'table',        'params' => [$kurikulumCtx]],
                ['label' => 'Peta MK–CPMK–Sub-CPMK',  'route' => 'kurikulum.penilaian.mk-cpmk-subcpmk','icon' => 'document',     'params' => [$kurikulumCtx]],
                ['label' => 'Teknik Penilaian CPMK',   'route' => 'kurikulum.penilaian.teknik',          'icon' => 'check-circle', 'params' => [$kurikulumCtx]],
                ['label' => 'Tahap & Mekanisme',       'route' => 'kurikulum.penilaian.tahap',           'icon' => 'document',     'params' => [$kurikulumCtx]],
                ['label' => 'Bobot Penilaian CPL',     'route' => 'kurikulum.penilaian.bobot',           'icon' => 'calculator',   'params' => [$kurikulumCtx]],
                ['label' => 'Bobot Penilaian MK',      'route' => 'kurikulum.penilaian.bobot-mk',        'icon' => 'calculator',   'params' => [$kurikulumCtx]],
                ['label' => 'Rumusan Nilai Akhir MK',  'route' => 'kurikulum.penilaian.rumusan',         'icon' => 'chart-bar',    'params' => [$kurikulumCtx]],
                ['label' => 'Rumusan Nilai Akhir CPL', 'route' => 'kurikulum.penilaian.rumusan-cpl',     'icon' => 'chart-bar',    'params' => [$kurikulumCtx]],
                ['label' => 'Rekap Nilai per MK',      'route' => 'kurikulum.penilaian.rekap-nilai',     'icon' => 'document-report', 'params' => [$kurikulumCtx]],
            ]],

            // Evaluasi & Laporan — proses penilaian & evaluasi CPL per CPL, dan assessment lintas CPL/MK
            ['group' => 'Evaluasi & Laporan', 'icon' => 'document-report', 'children' => [
                ['label' => 'Proses Penilaian dan Evaluasi CPL', 'route' => 'kurikulum.penilaian.evaluasi-cpl', 'icon' => 'document-report', 'params' => [$kurikulumCtx]],
                ['label' => 'Assessment terhadap CPL dan MK', 'route' => 'kurikulum.penilaian.rekap-cpl',   'icon' => 'table',        'params' => [$kurikulumCtx]],
            ]],

            // Administrasi — arsip dan pengelolaan tim
            ['group' => 'Administrasi', 'icon' => 'cog', 'children' => [
                ['label' => 'Arsip Rapat',        'route' => 'kurikulum.arsip-rapat.index', 'icon' => 'meeting',  'params' => [$kurikulumCtx]],
                ['label' => 'Generate PDF',       'route' => 'kurikulum.generate-pdf',      'icon' => 'document',     'params' => [$kurikulumCtx]],
            ]],
        ];
    }

    // ── Menu per peran ───────────────────────────────────────────────────────
    $kaprodiMenus = array_merge([
        ['label' => 'Dashboard',         'route' => 'kaprodi.dashboard',               'icon' => 'home'],
        ['label' => 'Semester Akademik', 'route' => 'kaprodi.semester-akademik.index', 'icon' => 'calendar'],
        // Pengampuan MK — prasyarat agar Dosen bisa membuat RPS
        ['label' => 'Pengampuan MK',     'route' => 'kaprodi.pengampuan.index',        'icon' => 'academic-cap'],
        // Enrollment MK — daftarkan mahasiswa ke mata kuliah per semester
        ['label' => 'Enrollment MK',     'route' => 'kaprodi.enrollment.index',        'icon' => 'user-group'],
        // openWhen: tetap terbuka di semua halaman kurikulum (termasuk sub-halaman spesifik)
        ['group' => 'Kurikulum & RPS', 'icon' => 'book-open', 'openWhen' => 'kurikulum.*', 'children' => [
            // href (bukan route) agar aturan "tanpa query = tidak aktif saat ada query" berlaku
            ['label' => 'Kurikulum',       'href'  => route('kurikulum.index'),                        'icon' => 'book-open'],
            ['label' => 'Arsip Kurikulum', 'href'  => route('kurikulum.index', ['status' => 'arsip']), 'icon' => 'archive'],
            ['label' => 'Persetujuan RPS', 'route' => 'kaprodi.rps-approval.index',                    'icon' => 'document-check'],
        ]],
        ['group' => 'Sistem', 'icon' => 'cog', 'children' => [
            ['label' => 'Audit Trail',    'route' => 'kaprodi.activity-log.index',    'icon' => 'clock'],
            ['label' => 'Manajemen User', 'route' => 'kaprodi.users.index',           'icon' => 'users'],
            ['label' => 'Master Kategori','route' => 'admin.master-kategori.index',   'icon' => 'cog'],
        ]],
    ], $contextualKurikulumMenus);

    $kurikulumMenus = array_merge([
        ['label' => 'Dashboard', 'route' => 'kurikulum.dashboard', 'icon' => 'home'],
        // openWhen: tetap terbuka di semua halaman kurikulum
        ['group' => 'Kurikulum', 'icon' => 'book-open', 'openWhen' => 'kurikulum.*', 'children' => [
            // href (bukan route) agar aturan "tanpa query = tidak aktif saat ada query" berlaku
            ['label' => 'Semua Kurikulum', 'href'  => route('kurikulum.index'),                        'icon' => 'book-open'],
            ['label' => 'Arsip Kurikulum', 'href'  => route('kurikulum.index', ['status' => 'arsip']), 'icon' => 'archive'],
        ]],
        ['group' => 'Sistem', 'icon' => 'cog', 'children' => [
            ['label' => 'Master Kategori', 'route' => 'admin.master-kategori.index', 'icon' => 'cog'],
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
/*
 * Aturan visual sidebar group button:
 *   - HANYA child item (link <a>) yang boleh punya background highlight (bg-blue-600).
 *   - Group button (dropdown toggle) TIDAK pernah punya background — hanya warna teks
 *     dan garis kiri tipis yang membedakan state-nya.
 *   Ini mencegah "double highlight" di mana parent DAN child terlihat aktif sekaligus.
 */

/* State: salah satu child adalah halaman aktif saat ini
   → Teks putih penuh + sedikit lebih terang dari "open".
   TIDAK ada background — hanya child item (bg-blue-600) yang boleh highlight. */
.group-toggle[data-state="active"] {
    color: rgb(255 255 255);
    font-weight: 600;
}

/* State: dropdown terbuka via openWhen tapi tidak ada child aktif */
.group-toggle[data-state="open"] {
    color: rgb(203 213 225); /* slate-300 — sedikit lebih terang dari closed */
}

/* State: dropdown tertutup */
.group-toggle[data-state="closed"] {
    color: rgb(148 163 184); /* slate-400 */
}

/* Hover: berlaku untuk semua state (background ringan saat kursor di atas) */
.group-toggle:hover {
    background-color: rgba(51, 65, 85, 0.5); /* slate-700/50 */
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
