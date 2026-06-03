{{--
    Global Search — inline dropdown lurus ke bawah dari search bar di navbar.
    Shortcut: Ctrl+K / Cmd+K, atau tekan / saat tidak ada input yang fokus.
--}}
@php
    $role      = auth()->user()->role->value;
    $user      = auth()->user();
    $activeKur = \App\Models\Kurikulum::where('status', 'aktif')->first();
    $latestKur = $activeKur ?? \App\Models\Kurikulum::orderByDesc('id')->first();
    $kId       = $latestKur?->id;
    $kode      = $latestKur?->kode ?? '—';

    // Helper: buat item dengan URL aman — jika route tidak ada, hasilkan null (lalu difilter)
    $mk = function(string $label, string $url, string $group = 'Umum', string $keywords = '') {
        return compact('label', 'url', 'group', 'keywords');
    };

    // Helper URL aman — tangkap exception jika route belum terdaftar
    $r = function(string $name, $params = []) {
        try { return route($name, $params); } catch (\Throwable $e) { return ''; }
    };

    $items = [];

    // ── KAPRODI ──────────────────────────────────────────────────────────────
    if ($role === 'kaprodi') {
        $items = [
            $mk('Dashboard',              $r('kaprodi.dashboard'),                              'Navigasi',  'home beranda'),
            $mk('Semester Akademik',      $r('kaprodi.semester-akademik.index'),                'Akademik',  'semester ganjil genap'),
            $mk('Tambah Semester Baru',   $r('kaprodi.semester-akademik.create'),               'Akademik',  'buat semester baru'),
            $mk('Pengampuan MK',          $r('kaprodi.pengampuan.index'),                       'Akademik',  'dosen mengajar mata kuliah'),
            $mk('Enrollment MK',          $r('kaprodi.enrollment.index'),                       'Akademik',  'mahasiswa daftar mata kuliah semester'),
            $mk('Daftarkan Mahasiswa',    $r('kaprodi.enrollment.batch'),                       'Akademik',  'batch enrollment tambah mahasiswa mk'),
            $mk('Semua Kurikulum',        $r('kurikulum.index'),                                'Kurikulum', 'daftar kurikulum'),
            $mk('Arsip Kurikulum',        $r('kurikulum.index', ['status'=>'arsip']),           'Kurikulum', 'arsip kurikulum lama'),
            $mk('Tambah Kurikulum',       $r('kurikulum.create'),                               'Kurikulum', 'buat kurikulum baru'),
            $mk('Persetujuan RPS',        $r('kaprodi.rps-approval.index'),                     'Kurikulum', 'rps approval review dosen'),
            $mk('Evaluasi CQI',           $r('kaprodi.cqi.index'),                              'Evaluasi',  'cqi continuous quality improvement'),
            $mk('Laporan OBE',            $r('kaprodi.reports.index'),                          'Evaluasi',  'laporan obe capaian cpl'),
            $mk('Audit Trail',            $r('kaprodi.activity-log.index'),                     'Sistem',    'log aktivitas history'),
            $mk('Manajemen User',         $r('kaprodi.users.index'),                            'Sistem',    'pengguna akun dosen mahasiswa'),
            $mk('Master Kategori',        $r('admin.master-kategori.index'),                    'Sistem',    'kategori cpl pl bk mk master'),
        ];
        if ($kId) {
            $items = array_merge($items, [
                $mk("CPL SN-Dikti [$kode]",          $r('kurikulum.cpl-sndikti.index', $kId),        'OBE Data',  'sindikti standar nasional'),
                $mk("Profil Lulusan [$kode]",         $r('kurikulum.pl.index', $kId),                 'OBE Data',  'pl profil lulusan'),
                $mk("CPL Prodi [$kode]",              $r('kurikulum.cpl-prodi.index', $kId),          'OBE Data',  'cpl capaian program'),
                $mk("Bahan Kajian [$kode]",           $r('kurikulum.bahan-kajian.index', $kId),       'OBE Data',  'bk bahan kajian'),
                $mk("Mata Kuliah [$kode]",            $r('kurikulum.mata-kuliah.index', $kId),        'OBE Data',  'mk daftar courses'),
                $mk("Organisasi MK [$kode]",          $r('kurikulum.organisasi-mk', $kId),            'OBE Data',  'distribusi semester'),
                $mk("Matriks PL↔CPL [$kode]",         $r('kurikulum.pivot.pl-cpl', $kId),             'Matriks',   'peta profil lulusan cpl'),
                $mk("Matriks CPL-SN↔CPL [$kode]",    $r('kurikulum.pivot.cplsn-cplp', $kId),         'Matriks',   'sindikti prodi mapping'),
                $mk("Matriks CPL↔BK [$kode]",         $r('kurikulum.pivot.cpl-bk', $kId),             'Matriks',   'cpl bahan kajian'),
                $mk("Matriks MK↔BK [$kode]",          $r('kurikulum.pivot.mk-bk', $kId),              'Matriks',   'mk bahan kajian'),
                $mk("Matriks MK↔CPL [$kode]",         $r('kurikulum.pivot.mk-cpl', $kId),             'Matriks',   'mk cpl cpmk auto'),
                $mk("Matriks CPL↔CPMK [$kode]",       $r('kurikulum.pivot.cpl-cpmk', $kId),           'Matriks',   'cpl cpmk bobot'),
                $mk("Matriks CPL↔BK↔MK [$kode]",      $r('kurikulum.pivot.cpl-bk-mk', $kId),          'Matriks',   '3d tiga dimensi'),
                $mk("Peta CPL–CPMK–MK [$kode]",       $r('kurikulum.overview.cpl-cpmk-mk', $kId),     'Matriks',   'tabel 13 pemetaan'),
                $mk("Peta CPL–CPMK–Semester [$kode]", $r('kurikulum.penilaian.peta-semester', $kId),  'Asesmen',   'semester tabel peta'),
                $mk("MK–CPMK–Sub-CPMK [$kode]",       $r('kurikulum.penilaian.mk-cpmk-subcpmk', $kId),'Asesmen',  'edit subcpmk peta'),
                $mk("Teknik Penilaian [$kode]",        $r('kurikulum.penilaian.teknik', $kId),         'Asesmen',   'quiz uts uas teknik'),
                $mk("Tahap & Mekanisme [$kode]",       $r('kurikulum.penilaian.tahap', $kId),          'Asesmen',   'tahap instrumen kriteria'),
                $mk("Bobot Penilaian [$kode]",         $r('kurikulum.penilaian.bobot', $kId),          'Asesmen',   'bobot persen nilai'),
                $mk("Rumusan Nilai Akhir MK [$kode]",  $r('kurikulum.penilaian.rumusan', $kId),        'Asesmen',   'skor maks total 100'),
                $mk("Rumusan Nilai Akhir CPL [$kode]", $r('kurikulum.penilaian.rumusan-cpl', $kId),    'Asesmen',   'lcpl nilai akhir cpl'),
                $mk("Peta Pemenuhan CPL [$kode]",      $r('kurikulum.overview.pemenuhan-cpl', $kId),   'Peta',      'ketercapaian fulfillment visual'),
            ]);
        }
    }

    // ── TIM KURIKULUM ─────────────────────────────────────────────────────────
    elseif ($role === 'tim_kurikulum') {
        $items = [
            $mk('Dashboard',        $r('kurikulum.dashboard'),              'Navigasi',  'home beranda'),
            $mk('Semua Kurikulum',  $r('kurikulum.index'),                  'Kurikulum', 'daftar kurikulum'),
            $mk('Arsip Kurikulum',  $r('kurikulum.index',['status'=>'arsip']),'Kurikulum','arsip lama'),
            $mk('Tambah Kurikulum', $r('kurikulum.create'),                 'Kurikulum', 'buat kurikulum baru'),
            $mk('Master Kategori',  $r('admin.master-kategori.index'),      'Sistem',    'kategori master'),
        ];
        if ($kId) {
            $items = array_merge($items, [
                $mk("Ikhtisar [$kode]",              $r('kurikulum.show', $kId),                      'Kurikulum',   'ringkasan overview'),
                $mk("CPL SN-Dikti [$kode]",          $r('kurikulum.cpl-sndikti.index', $kId),         'OBE Data',    'sindikti standar nasional'),
                $mk("Profil Lulusan [$kode]",         $r('kurikulum.pl.index', $kId),                  'OBE Data',    'pl profil lulusan'),
                $mk("CPL Prodi [$kode]",              $r('kurikulum.cpl-prodi.index', $kId),           'OBE Data',    'cpl capaian'),
                $mk("Bahan Kajian [$kode]",           $r('kurikulum.bahan-kajian.index', $kId),        'OBE Data',    'bk bahan kajian'),
                $mk("Mata Kuliah [$kode]",            $r('kurikulum.mata-kuliah.index', $kId),         'OBE Data',    'mk courses daftar'),
                $mk("Organisasi MK [$kode]",          $r('kurikulum.organisasi-mk', $kId),             'OBE Data',    'distribusi semester'),
                $mk("Matriks PL↔CPL [$kode]",         $r('kurikulum.pivot.pl-cpl', $kId),              'Matriks',     'profil lulusan cpl'),
                $mk("Matriks CPL-SN↔CPL [$kode]",    $r('kurikulum.pivot.cplsn-cplp', $kId),          'Matriks',     'sindikti prodi'),
                $mk("Matriks CPL↔BK [$kode]",         $r('kurikulum.pivot.cpl-bk', $kId),              'Matriks',     'bahan kajian cpl'),
                $mk("Matriks MK↔BK [$kode]",          $r('kurikulum.pivot.mk-bk', $kId),               'Matriks',     'mk bk'),
                $mk("Matriks MK↔CPL [$kode]",         $r('kurikulum.pivot.mk-cpl', $kId),              'Matriks',     'mk cpl'),
                $mk("Matriks CPL↔CPMK [$kode]",       $r('kurikulum.pivot.cpl-cpmk', $kId),            'Matriks',     'cpmk'),
                $mk("Matriks CPL↔BK↔MK [$kode]",      $r('kurikulum.pivot.cpl-bk-mk', $kId),           'Matriks',     '3d'),
                $mk("Peta CPL–CPMK–MK [$kode]",       $r('kurikulum.overview.cpl-cpmk-mk', $kId),      'Matriks',     'tabel 13'),
                $mk("Peta CPL–CPMK–Semester [$kode]", $r('kurikulum.penilaian.peta-semester', $kId),   'Asesmen',     'semester'),
                $mk("MK–CPMK–Sub-CPMK [$kode]",       $r('kurikulum.penilaian.mk-cpmk-subcpmk', $kId),'Asesmen',     'sub cpmk edit'),
                $mk("Teknik Penilaian [$kode]",        $r('kurikulum.penilaian.teknik', $kId),          'Asesmen',     'quiz uts uas'),
                $mk("Tahap & Mekanisme [$kode]",       $r('kurikulum.penilaian.tahap', $kId),           'Asesmen',     'instrumen kriteria'),
                $mk("Bobot Penilaian [$kode]",         $r('kurikulum.penilaian.bobot', $kId),           'Asesmen',     'bobot persen'),
                $mk("Rumusan Nilai Akhir MK [$kode]",  $r('kurikulum.penilaian.rumusan', $kId),         'Asesmen',     'skor maks'),
                $mk("Rumusan Nilai Akhir CPL [$kode]", $r('kurikulum.penilaian.rumusan-cpl', $kId),     'Asesmen',     'lcpl total'),
                $mk("Peta Pemenuhan CPL [$kode]",      $r('kurikulum.overview.pemenuhan-cpl', $kId),    'Peta',        'pemenuhan'),
                $mk("Arsip Rapat [$kode]",             $r('kurikulum.arsip-rapat.index', $kId),         'Administrasi','rapat notulen'),
                $mk("Tim Kurikulum [$kode]",           $r('kurikulum.tim.index', $kId),                 'Administrasi','tim anggota'),
                $mk("Periode [$kode]",                 $r('kurikulum.periode.index', $kId),             'Administrasi','periode penyusunan'),
                $mk("Lesson Learned [$kode]",          $r('kurikulum.lesson-learned.index', $kId),      'Administrasi','evaluasi catatan'),
                $mk("Checklist LEDIK [$kode]",         $r('kurikulum.ledik.index', $kId),               'Administrasi','plan do check action'),
            ]);
        }
    }

    // ── DOSEN ────────────────────────────────────────────────────────────────
    elseif ($role === 'dosen') {
        $items = [
            $mk('Dashboard',          $r('dosen.dashboard'),      'Navigasi',    'home beranda'),
            $mk('Pengampuan MK Saya', $r('dosen.pengampuan'),     'Pembelajaran','mata kuliah diampu'),
            $mk('RPS Saya',           $r('dosen.rps.index'),      'Pembelajaran','rencana pembelajaran semester rps'),
            $mk('Jurnal Mengajar',    $r('dosen.jurnal.index'),   'Pembelajaran','jurnal log mengajar pertemuan'),
            $mk('Input Nilai',        $r('dosen.nilai.index'),    'Penilaian',   'nilai mahasiswa input assessment'),
            $mk('Materi Ajar',        $r('dosen.materi.index'),   'Penilaian',   'materi bahan ajar resources'),
        ];
    }

    // ── MAHASISWA ─────────────────────────────────────────────────────────────
    elseif ($role === 'mahasiswa') {
        $items = [
            $mk('Dashboard',         $r('mahasiswa.dashboard'),          'Navigasi', 'home beranda'),
            $mk('Nilai Saya',        $r('mahasiswa.nilai.index'),        'Akademik', 'nilai assessment semester'),
            $mk('Materi Kuliah',     $r('mahasiswa.materi.index'),       'Akademik', 'bahan materi ajar'),
            $mk('Progress CPL Saya', $r('mahasiswa.cpl-progress.index'), 'Akademik', 'capaian cpl progress ketercapaian'),
            $mk('Progress PL',       $r('mahasiswa.pl-progress'),        'Akademik', 'profil lulusan progress'),
        ];
    }

    // Pastikan semua URL valid — buang item yang URL-nya kosong/error
    $items = array_values(array_filter($items, fn($i) => !empty($i['url'])));

    $itemsJson = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP);
@endphp

{{-- ═══════════════════════════════ JAVASCRIPT ════════════════════════════════ --}}
<script>
(function () {
    var ITEMS       = {!! $itemsJson !!};
    var input       = document.getElementById('global-search-input');
    var dropdown    = document.getElementById('global-search-dropdown');
    var clearBtn    = document.getElementById('global-search-clear');
    var kbd         = document.getElementById('global-search-kbd');
    var wrap        = document.getElementById('global-search-wrap');
    var mobileBtn   = document.getElementById('mobile-search-toggle');

    if (!input || !dropdown) return;

    var activeIdx   = -1;
    var timer       = null;
    var isOpen      = false;

    // ── Mobile toggle ────────────────────────────────────────────────────────
    if (mobileBtn && wrap) {
        mobileBtn.addEventListener('click', function () {
            wrap.classList.toggle('hidden');
            if (!wrap.classList.contains('hidden')) {
                wrap.style.display = 'block';
                wrap.style.position = 'absolute';
                wrap.style.top = '48px';
                wrap.style.left = '0';
                wrap.style.right = '0';
                wrap.style.zIndex = '300';
                wrap.style.padding = '0 16px 8px';
                wrap.style.background = 'white';
                wrap.style.borderBottom = '1px solid #e5e7eb';
                setTimeout(() => input.focus(), 50);
            } else {
                wrap.style = '';
            }
        });
    }

    // ── Show / Hide dropdown ─────────────────────────────────────────────────
    function openDropdown() {
        isOpen = true;
        dropdown.classList.remove('hidden');
        if (kbd) kbd.classList.add('hidden');
    }

    function closeDropdown() {
        isOpen = false;
        dropdown.classList.add('hidden');
        activeIdx = -1;
        if (kbd && !input.value) kbd.classList.remove('hidden');
    }

    // Click outside closes dropdown
    document.addEventListener('mousedown', function (e) {
        if (wrap && !wrap.contains(e.target)) {
            closeDropdown();
        }
    });

    // ── Keyboard shortcuts ────────────────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
        // Ctrl+K / Cmd+K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            input.focus();
            input.select();
            if (input.value) renderResults(input.value.trim());
            else showHint();
            openDropdown();
            return;
        }
        // '/' shortcut when no input focused
        if (e.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes(document.activeElement?.tagName)) {
            e.preventDefault();
            input.focus();
            input.select();
            openDropdown();
            return;
        }
        if (!isOpen) return;

        var items = dropdown.querySelectorAll('.gs-item');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIdx = Math.min(activeIdx + 1, items.length - 1);
            setActive(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIdx = Math.max(activeIdx - 1, 0);
            setActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIdx >= 0 && items[activeIdx]) {
                window.location.href = items[activeIdx].dataset.url;
            }
        } else if (e.key === 'Escape') {
            input.value = '';
            clearBtn.classList.add('hidden');
            closeDropdown();
            input.blur();
        }
    });

    function setActive(items) {
        items.forEach(function (el, i) {
            el.classList.toggle('bg-amber-50', i === activeIdx);
            el.classList.toggle('text-amber-700', i === activeIdx);
        });
        if (items[activeIdx]) items[activeIdx].scrollIntoView({ block: 'nearest' });
    }

    // ── Input events ──────────────────────────────────────────────────────────
    input.addEventListener('focus', function () {
        openDropdown();
        if (this.value.trim()) renderResults(this.value.trim());
        else showHint();
    });

    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = this.value.trim();
        clearBtn.classList.toggle('hidden', !q);
        if (kbd) kbd.classList.toggle('hidden', !!q);
        timer = setTimeout(function () {
            if (q) renderResults(q);
            else showHint();
        }, 80);
    });

    clearBtn.addEventListener('click', function () {
        input.value = '';
        clearBtn.classList.add('hidden');
        if (kbd) kbd.classList.remove('hidden');
        showHint();
        input.focus();
    });

    // ── Render ────────────────────────────────────────────────────────────────
    function showHint() {
        activeIdx = -1;
        dropdown.innerHTML =
            '<div class="px-4 py-3 text-xs text-gray-400">' +
            '<p class="font-medium text-gray-500 mb-2">Akses cepat menu:</p>' +
            '<div class="flex flex-wrap gap-1.5">' +
            ['Dashboard','CPL','MK','CPMK','Matriks','Penilaian','Profil Lulusan','Bahan Kajian'].map(function(h) {
                return '<button class="gs-hint px-2 py-0.5 bg-gray-100 hover:bg-amber-100 hover:text-amber-700 text-gray-600 rounded text-[10px] transition-colors" data-q="'+h+'">'+ h +'</button>';
            }).join('') +
            '</div>' +
            '<p class="mt-3 text-[10px] text-gray-300"><kbd class="px-1 py-0.5 bg-gray-100 rounded border border-gray-200 font-mono">↑↓</kbd> navigasi · <kbd class="px-1 py-0.5 bg-gray-100 rounded border border-gray-200 font-mono">↵</kbd> buka · <kbd class="px-1 py-0.5 bg-gray-100 rounded border border-gray-200 font-mono">Esc</kbd> tutup</p>' +
            '</div>';

        dropdown.querySelectorAll('.gs-hint').forEach(function (btn) {
            btn.addEventListener('click', function () {
                input.value = this.dataset.q;
                clearBtn.classList.remove('hidden');
                if (kbd) kbd.classList.add('hidden');
                renderResults(this.dataset.q);
                input.focus();
            });
        });
    }

    function renderResults(q) {
        activeIdx = -1;
        var ql = q.toLowerCase();

        var matched = ITEMS.filter(function (item) {
            return (item.label + ' ' + item.group + ' ' + item.keywords).toLowerCase().indexOf(ql) !== -1;
        });

        if (!matched.length) {
            dropdown.innerHTML =
                '<div class="px-4 py-6 text-center text-sm text-gray-400">' +
                '<svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">' +
                '<path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
                'Tidak ada menu yang cocok dengan <strong>"' + escHtml(q) + '"</strong></div>';
            return;
        }

        // Group by category
        var groups = {};
        matched.forEach(function (item) {
            if (!groups[item.group]) groups[item.group] = [];
            groups[item.group].push(item);
        });

        var html = '';
        Object.keys(groups).forEach(function (group) {
            html += '<div class="px-3 pt-3 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider sticky top-0 bg-white">' + escHtml(group) + '</div>';
            groups[group].forEach(function (item) {
                var label = highlightMatch(item.label, ql);
                html += '<a href="' + escHtml(item.url) + '" class="gs-item flex items-center gap-3 px-3 py-2.5 hover:bg-amber-50 transition-colors cursor-pointer group" data-url="' + escHtml(item.url) + '">' +
                    '<div class="w-7 h-7 rounded-lg bg-gray-100 group-hover:bg-amber-100 flex items-center justify-center shrink-0 transition-colors">' +
                    '<svg class="w-3.5 h-3.5 text-gray-500 group-hover:text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>' +
                    '</div>' +
                    '<span class="text-sm text-gray-700 group-hover:text-amber-700 truncate flex-1">' + label + '</span>' +
                    '</a>';
            });
        });

        html += '<div class="px-3 py-2 text-[10px] text-gray-300 border-t border-gray-100 mt-1">' + matched.length + ' menu ditemukan</div>';
        dropdown.innerHTML = html;

        // Click closes dropdown
        dropdown.querySelectorAll('.gs-item').forEach(function (el) {
            el.addEventListener('click', function () { closeDropdown(); });
        });
    }

    function highlightMatch(text, q) {
        var idx = text.toLowerCase().indexOf(q);
        if (idx < 0) return escHtml(text);
        return escHtml(text.slice(0, idx)) +
               '<mark class="bg-yellow-200 text-yellow-900 not-italic rounded-sm">' +
               escHtml(text.slice(idx, idx + q.length)) + '</mark>' +
               escHtml(text.slice(idx + q.length));
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Expose (for compatibility jika ada kode lama yang masih memanggil openPalette)
    window.openPalette = function () { input.focus(); input.select(); openDropdown(); };
    window.closePalette = closeDropdown;
})();
</script>
