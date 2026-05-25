{{-- Partial: item navigasi sidebar, di-include dari layouts/app.blade.php --}}

@php
    $navItem = function(string $label, string $routeName, string $icon) {
        $active = request()->routeIs($routeName . '*');
        return [
            'label'  => $label,
            'href'   => route($routeName),
            'active' => $active,
            'icon'   => $icon,
        ];
    };

    // Tentukan menu berdasarkan peran
    $role = auth()->user()->role->value;

    $menus = match($role) {
        'kaprodi' => [
            ['label' => 'Dashboard',          'route' => 'kaprodi.dashboard',              'icon' => 'home'],
            ['label' => 'Manajemen User',      'route' => 'kaprodi.users.index',            'icon' => 'users'],
            ['label' => 'Semester Akademik',   'route' => 'kaprodi.semester-akademik.index','icon' => 'calendar'],
            ['label' => 'Persetujuan RPS',     'route' => 'kaprodi.rps-approval.index',     'icon' => 'document-check'],
            ['label' => 'Evaluasi CQI',        'route' => 'kaprodi.cqi.index',              'icon' => 'chart-bar'],
            ['label' => 'Laporan OBE',         'route' => 'kaprodi.reports.index',          'icon' => 'document-report'],
            ['label' => 'Audit Trail',         'route' => 'kaprodi.activity-log.index',     'icon' => 'clock'],
        ],
        'tim_kurikulum' => [
            ['label' => 'Dashboard',           'route' => 'kurikulum.dashboard',            'icon' => 'home'],
            ['label' => 'Kurikulum',           'route' => 'kurikulum.index',                'icon' => 'book-open'],
        ],
        'dosen' => [
            ['label' => 'Dashboard',           'route' => 'dosen.dashboard',                'icon' => 'home'],
            ['label' => 'RPS Saya',            'route' => 'dosen.rps.index',                'icon' => 'document'],
            ['label' => 'Jurnal Mengajar',     'route' => 'dosen.jurnal.index',             'icon' => 'pencil'],
            ['label' => 'Input Nilai',         'route' => 'dosen.nilai.index',              'icon' => 'calculator'],
            ['label' => 'Materi',              'route' => 'dosen.materi.index',             'icon' => 'folder'],
        ],
        'mahasiswa' => [
            ['label' => 'Dashboard',           'route' => 'mahasiswa.dashboard',            'icon' => 'home'],
            ['label' => 'Nilai Saya',          'route' => 'mahasiswa.nilai.index',          'icon' => 'chart-bar'],
            ['label' => 'Progress CPL',        'route' => 'mahasiswa.cpl-progress.index',   'icon' => 'trending-up'],
            ['label' => 'Materi',              'route' => 'mahasiswa.materi.index',         'icon' => 'folder'],
        ],
        default => [],
    };
@endphp

@foreach ($menus as $menu)
    @php
        $active = request()->routeIs(str($menu['route'])->beforeLast('.') . '.*')
                  || request()->routeIs($menu['route']);
    @endphp
    <a href="{{ route($menu['route']) }}"
       class="{{ $active
           ? 'bg-blue-600 text-white'
           : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}
              group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150">

        @include('layouts._icon', ['name' => $menu['icon'], 'class' => 'w-4 h-4 shrink-0'])
        {{ $menu['label'] }}
    </a>
@endforeach
