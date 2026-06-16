@extends('layouts.app')
@section('title', 'Peta Pemenuhan CPL')
@section('header', 'Peta Pemenuhan CPL')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Peta Pemenuhan CPL</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.overview.pemenuhan-cpl.export', $kurikulum)])
@endsection

@section('content')

@php
    $semCols    = range(1, 8);
    $isEditable = !$kurikulum->isArsip();

    // Statistik
    $totalCpl = $cplList->count();
    $totalMk  = $mkList->count();

    $cplDenganMk = 0;
    foreach ($cplList as $cpl) {
        foreach ($semCols as $s) {
            if (!empty($peta[$cpl->id][$s] ?? [])) { $cplDenganMk++; break; }
        }
    }

    // mk_id → [cpl_ids] — sudah dihitung controller, tersedia di $existing
    // Jumlah MK per CPL (flat)
    $mkPerCpl = [];
    foreach ($cplList as $cpl) {
        $cnt = 0;
        foreach ($semCols as $s) { $cnt += count($peta[$cpl->id][$s] ?? []); }
        $mkPerCpl[$cpl->id] = $cnt;
    }

    // Jumlah MK unik per semester (lintas CPL)
    $mkPerSmt = [];
    foreach ($semCols as $s) {
        $ids = [];
        foreach ($cplList as $cpl) {
            foreach ($peta[$cpl->id][$s] ?? [] as $mk) { $ids[] = $mk->id; }
        }
        $mkPerSmt[$s] = count(array_unique($ids));
    }

    // MK dikelompokkan per semester untuk popup
    $mkBySemester = $mkList->groupBy('semester')->map->values();
@endphp

{{-- Statistik --}}
<div class="grid grid-cols-3 gap-3 mb-4">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total MK</p>
        <p class="text-2xl font-black text-amber-600 mt-0.5">{{ $totalMk }}</p>
        <p class="text-[10px] text-gray-400">Mata Kuliah</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total CPL</p>
        <p class="text-2xl font-black text-blue-600 mt-0.5">{{ $totalCpl }}</p>
        <p class="text-[10px] text-gray-400">CPL Prodi</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">CPL Terpenuhi</p>
        <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ $cplDenganMk }}</p>
        <p class="text-[10px] text-gray-400">dari {{ $totalCpl }} CPL</p>
    </div>
</div>

{{-- Info --}}
<div class="mb-4 flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-800">
    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        <strong>Peta Pemenuhan CPL.</strong>
        Setiap sel menampilkan MK yang memenuhi CPL tersebut pada semester tertentu.
        @if ($isEditable)
            <strong>Klik sel untuk menambah/menghapus pemetaan MK–CPL. Tersimpan otomatis.</strong>
        @endif
        Arahkan kursor ke kode CPL atau MK untuk melihat deskripsinya.
    </div>
</div>

@if ($cplList->isEmpty() || $mkList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Belum ada data CPL atau MK.
        <a href="{{ route('kurikulum.cpl-prodi.index', $kurikulum) }}" class="underline">Tambah CPL →</a>
    </div>
@else

{{-- ═══ TABEL CPL × SEMESTER ═══ --}}
<div class="rounded-xl border border-amber-300 shadow-sm overflow-hidden bg-white">
<div class="overflow-x-auto">
<table class="border-collapse text-xs" id="peta-table" style="min-width:max-content;width:100%">

    <thead>
        <tr>
            <th rowspan="2"
                class="px-5 py-3 text-center font-bold border border-amber-400"
                style="background:#F59E0B;color:#fff;min-width:80px;vertical-align:middle">
                CPL
            </th>
            <th colspan="{{ count($semCols) }}"
                class="px-3 py-2 text-center font-bold border border-amber-400"
                style="background:#F59E0B;color:#fff">
                Semester
            </th>
        </tr>
        <tr>
            @foreach ($semCols as $s)
                <th class="px-3 py-2 text-center font-bold border border-amber-400"
                    style="background:#FBBF24;color:#fff;min-width:80px">
                    {{ $s }}
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($cplList as $i => $cpl)
            @php $rowBg = $i % 2 === 0 ? '#FFFFFF' : '#FFFBEB'; @endphp
            <tr style="background:{{ $rowBg }}">

                {{-- CPL label --}}
                <td class="px-4 py-2.5 text-center font-bold border border-amber-300 cpl-label"
                    style="background:#FEF3C7;color:#92400E;vertical-align:middle"
                    data-cpl-kode="{{ $cpl->kode_cpl }}"
                    data-cpl-desc="{{ $cpl->deskripsi }}">
                    {{ $cpl->kode_cpl }}
                </td>

                {{-- Semester cells --}}
                @foreach ($semCols as $s)
                    @php $mksInCell = $peta[$cpl->id][$s] ?? []; @endphp
                    <td class="border border-amber-200 align-top peta-cell {{ $isEditable ? 'cursor-pointer hover:bg-amber-50' : '' }}"
                        style="min-width:80px;padding:6px 8px;vertical-align:top;background:{{ !empty($mksInCell) ? $rowBg : ($i % 2 === 0 ? '#FAFAFA' : '#FFFDF5') }}"
                        data-cpl-id="{{ $cpl->id }}"
                        data-cpl-kode="{{ $cpl->kode_cpl }}"
                        data-smt="{{ $s }}">
                        <div class="mk-chips flex flex-col gap-0.5">
                            @foreach ($mksInCell as $mk)
                                <span class="mk-chip font-mono font-bold text-[11px] leading-tight block"
                                      style="color:#0F766E"
                                      data-mk-id="{{ $mk->id }}"
                                      data-mk-kode="{{ $mk->kode_mk }}"
                                      data-mk-nama="{{ $mk->nama_mk }}"
                                      data-mk-smt="{{ $mk->semester }}"
                                      data-mk-sks="{{ $mk->sks_total }}">{{ $mk->kode_mk }}</span>
                            @endforeach
                        </div>
                        @if ($isEditable)
                            <div class="add-hint text-[9px] mt-0.5 {{ empty($mksInCell) ? 'text-amber-300' : 'text-amber-200 opacity-0 group-hover:opacity-100' }}">
                                @if(empty($mksInCell)) + @endif
                            </div>
                        @endif
                    </td>
                @endforeach

            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td class="px-3 py-2 text-right text-xs font-bold border border-amber-400"
                style="background:#F59E0B;color:#fff">
                Jml MK
            </td>
            @foreach ($semCols as $s)
                <td class="px-2 py-2 text-center text-xs font-bold border border-amber-400"
                    style="background:#F59E0B;color:{{ $mkPerSmt[$s] > 0 ? '#fff' : '#FEF3C7' }}"
                    data-smt="{{ $s }}">
                    {{ $mkPerSmt[$s] > 0 ? $mkPerSmt[$s] : '—' }}
                </td>
            @endforeach
        </tr>
    </tfoot>

</table>
</div>
</div>

{{-- Peringatan CPL tanpa MK --}}
@php $cplKosong = $cplList->filter(fn($cpl) => ($mkPerCpl[$cpl->id] ?? 0) === 0); @endphp
@if ($cplKosong->isNotEmpty())
    <div class="mt-4 bg-red-50 border border-red-200 rounded-xl px-5 py-4 text-sm text-red-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">{{ $cplKosong->count() }} CPL belum dipetakan ke MK manapun</p>
            <p class="text-xs mt-1.5 flex flex-wrap gap-1">
                @foreach ($cplKosong as $cpl)
                    <span class="font-mono bg-red-100 border border-red-200 px-1.5 py-0.5 rounded text-red-900"
                          title="{{ $cpl->deskripsi }}">{{ $cpl->kode_cpl }}</span>
                @endforeach
            </p>
            @if ($isEditable)
                <p class="text-xs mt-1.5 text-red-600">Klik sel pada baris CPL tersebut untuk memetakan MK.</p>
            @endif
        </div>
    </div>
@endif

{{-- ═══ POPUP (hanya jika editable) ═══ --}}
@if ($isEditable)
<div id="peta-popup"
     style="position:fixed;z-index:9999;width:280px;max-height:400px;display:none;
            background:#fff;border-radius:12px;border:1px solid #E5E7EB;
            box-shadow:0 8px 32px rgba(0,0,0,.18);overflow:hidden">

    {{-- Header --}}
    <div style="background:#F59E0B;padding:10px 14px;display:flex;align-items:flex-start;justify-content:space-between">
        <div>
            <p id="popup-title" style="font-size:12px;font-weight:700;color:#fff;line-height:1.3"></p>
            <p id="popup-subtitle" style="font-size:10px;color:#FEF3C7;margin-top:2px"></p>
        </div>
        <button onclick="closePetaPopup()"
                style="background:rgba(255,255,255,.2);border:none;border-radius:6px;
                       width:22px;height:22px;cursor:pointer;display:flex;align-items:center;justify-content:center;
                       color:#fff;font-size:14px;flex-shrink:0;margin-left:8px;line-height:1">
            &times;
        </button>
    </div>

    {{-- Search --}}
    <div style="padding:8px 10px;border-bottom:1px solid #F3F4F6">
        <input type="text" id="popup-search" autocomplete="off"
               placeholder="Cari kode/nama MK..."
               style="width:100%;border:1px solid #E5E7EB;border-radius:8px;
                      padding:5px 10px;font-size:11px;outline:none;box-sizing:border-box">
    </div>

    {{-- List --}}
    <div id="popup-list" style="overflow-y:auto;max-height:280px"></div>

    {{-- Loading --}}
    <div id="popup-loading"
         style="display:none;padding:12px;text-align:center;font-size:11px;color:#9CA3AF">
        Menyimpan...
    </div>
</div>
@endif

{{-- Floating tooltip --}}
<div id="peta-tooltip"
     style="position:fixed;z-index:10000;pointer-events:none;display:none;
            background:#1E293B;color:#fff;font-size:11px;font-weight:500;
            border-radius:8px;padding:7px 12px;box-shadow:0 4px 16px rgba(0,0,0,.2);
            max-width:260px;line-height:1.4;word-break:break-word">
    <span id="tt-line1" style="display:block"></span>
    <span id="tt-line2" style="display:block;color:#94A3B8;font-size:10px;margin-top:2px"></span>
    <span style="position:absolute;left:50%;transform:translateX(-50%);top:100%;
                 border:5px solid transparent;border-top-color:#1E293B;border-bottom:0;width:0;height:0"></span>
</div>

@endif

@endsection

@push('scripts')
<script>
// ──────────────────────────────────────────────────────────────────
// TOOLTIP
// ──────────────────────────────────────────────────────────────────
(function () {
    var tip   = document.getElementById('peta-tooltip');
    var line1 = document.getElementById('tt-line1');
    var line2 = document.getElementById('tt-line2');
    if (!tip) return;

    function show(el, l1, l2) {
        line1.textContent = l1;
        line2.textContent = l2 || '';
        tip.style.display = 'block';
        positionTip(el);
    }
    function hide() { tip.style.display = 'none'; }

    function positionTip(el) {
        var r  = el.getBoundingClientRect();
        var tw = tip.offsetWidth;
        var th = tip.offsetHeight;
        var left = r.left + r.width / 2 - tw / 2;
        var top  = r.top - th - 8;
        if (left < 6) left = 6;
        if (left + tw > window.innerWidth - 6) left = window.innerWidth - tw - 6;
        if (top < 6) top = r.bottom + 8;
        tip.style.left = left + 'px';
        tip.style.top  = top  + 'px';
    }

    // CPL label tooltip
    document.querySelectorAll('.cpl-label').forEach(function (el) {
        el.addEventListener('mouseenter', function () {
            show(el, el.dataset.cplKode, el.dataset.cplDesc);
        });
        el.addEventListener('mousemove', function () { positionTip(el); });
        el.addEventListener('mouseleave', hide);
    });

    // MK chip tooltips (delegated, runs after DOM updates too)
    document.getElementById('peta-table')?.addEventListener('mouseover', function (e) {
        var chip = e.target.closest('.mk-chip');
        if (!chip) return;
        show(chip, chip.dataset.mkKode + ': ' + chip.dataset.mkNama,
             'Smt ' + chip.dataset.mkSmt + ' · ' + chip.dataset.mkSks + ' SKS');
    });
    document.getElementById('peta-table')?.addEventListener('mouseout', function (e) {
        if (e.target.closest('.mk-chip')) hide();
    });
})();
</script>

@if (!$kurikulum->isArsip())
@php
    $mkJsonBySmt = [];
    foreach (range(1,8) as $s) {
        $mkJsonBySmt[$s] = ($mkBySemester[$s] ?? collect())->map(fn($m) => [
            'id'       => $m->id,
            'kode_mk'  => $m->kode_mk,
            'nama_mk'  => $m->nama_mk,
            'sks_total'=> $m->sks_total,
        ])->values()->toArray();
    }
    // existing: mk_id => [cpl_ids]
    $existingJson = $existing ?? [];
@endphp
<script>
// ──────────────────────────────────────────────────────────────────
// INLINE EDITING — Peta Pemenuhan CPL
// ──────────────────────────────────────────────────────────────────
const CSRF        = document.querySelector('meta[name="csrf-token"]')?.content;
const TOGGLE_URL  = @json(route('kurikulum.pivot.toggle', $kurikulum));
const mksBySmt    = @json($mkJsonBySmt);   // {1:[{id,kode_mk,nama_mk,sks_total},...], 2:[...], ...}
const initExisting= @json($existingJson);  // {mkId:[cplId,...], ...}

// Mutable state: mkId (string) => Set of cplIds
const state = {};
for (const [mkId, cplIds] of Object.entries(initExisting)) {
    state[mkId] = new Set(cplIds);
}
function isMapped(mkId, cplId) {
    return (state[String(mkId)] || new Set()).has(Number(cplId));
}

// ─── Cell click ───────────────────────────────────────────────────
const popup    = document.getElementById('peta-popup');
const pList    = document.getElementById('popup-list');
const pSearch  = document.getElementById('popup-search');
const pLoading = document.getElementById('popup-loading');

let activeCplId   = null;
let activeCplKode = null;
let activeSmt     = null;
let activeCell    = null;

document.querySelectorAll('.peta-cell').forEach(function (cell) {
    cell.addEventListener('click', function (e) {
        // Don't re-open same cell
        if (activeCplId === Number(cell.dataset.cplId) && activeSmt === Number(cell.dataset.smt) && popup.style.display !== 'none') {
            closePetaPopup(); return;
        }
        activeCplId   = Number(cell.dataset.cplId);
        activeCplKode = cell.dataset.cplKode;
        activeSmt     = Number(cell.dataset.smt);
        activeCell    = cell;

        document.getElementById('popup-title').textContent    = activeCplKode + ' × Semester ' + activeSmt;
        document.getElementById('popup-subtitle').textContent = 'Pilih MK yang memenuhi CPL ini';
        pSearch.value = '';
        renderPopupList('');
        pSearch.oninput = function () { renderPopupList(this.value); };

        positionPopup(cell);
        popup.style.display = 'block';
        setTimeout(function () { pSearch.focus(); }, 50);
    });
});

document.addEventListener('click', function (e) {
    if (popup && !popup.contains(e.target) && !e.target.closest('.peta-cell'))
        closePetaPopup();
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePetaPopup();
});

// ─── Render daftar MK per semester ────────────────────────────────
function renderPopupList(filter) {
    pList.innerHTML = '';
    var mks = mksBySmt[activeSmt] || [];
    if (filter) {
        var f = filter.toLowerCase();
        mks = mks.filter(function (m) {
            return m.kode_mk.toLowerCase().includes(f) || m.nama_mk.toLowerCase().includes(f);
        });
    }

    if (mks.length === 0) {
        pList.innerHTML = '<p style="padding:16px;text-align:center;font-size:11px;color:#9CA3AF">'
            + (mksBySmt[activeSmt]?.length ? 'Tidak ada MK yang cocok.' : 'Tidak ada MK di semester ini.')
            + '</p>';
        return;
    }

    mks.forEach(function (mk) {
        var checked = isMapped(mk.id, activeCplId);
        var row = document.createElement('label');
        row.style.cssText = 'display:flex;align-items:flex-start;gap:10px;padding:9px 14px;'
            + 'cursor:pointer;border-bottom:1px solid #F9FAFB;'
            + (checked ? 'background:#ECFDF5;' : 'background:#fff;');
        row.innerHTML =
            '<input type="checkbox" data-mk-id="' + mk.id + '" '
            + (checked ? 'checked ' : '')
            + 'style="margin-top:2px;accent-color:#0F766E;cursor:pointer;flex-shrink:0">'
            + '<div style="flex:1;min-width:0">'
            +   '<span style="font-family:monospace;font-weight:700;font-size:11px;color:' + (checked ? '#047857' : '#1F2937') + '">' + mk.kode_mk + '</span>'
            +   '<p style="font-size:10px;color:#6B7280;margin:2px 0 0;line-height:1.3">' + mk.nama_mk + '</p>'
            +   '<p style="font-size:10px;color:#9CA3AF">' + mk.sks_total + ' SKS</p>'
            + '</div>';

        var cb = row.querySelector('input[type=checkbox]');
        cb.addEventListener('change', function () {
            doToggle(mk, this.checked, row, cb);
        });
        pList.appendChild(row);
    });
}

// ─── AJAX toggle ──────────────────────────────────────────────────
async function doToggle(mk, nowChecked, rowEl, cbEl) {
    cbEl.disabled = true;
    rowEl.style.opacity = '0.5';
    pLoading.style.display = 'block';

    try {
        var res = await fetch(TOGGLE_URL, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({
                table:   'pivot_mk_cpl',
                keys:    { id_mk: mk.id, id_cpl: activeCplId },
                checked: nowChecked,
            }),
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);

        // Update state
        if (!state[String(mk.id)]) state[String(mk.id)] = new Set();
        if (nowChecked) state[String(mk.id)].add(Number(activeCplId));
        else             state[String(mk.id)].delete(Number(activeCplId));

        // Update cell DOM
        updateCell(mk, nowChecked);

        // Re-render row style
        rowEl.style.background = nowChecked ? '#ECFDF5' : '#fff';
        rowEl.style.opacity    = '1';
        var codeSpan = rowEl.querySelector('span');
        if (codeSpan) codeSpan.style.color = nowChecked ? '#047857' : '#1F2937';

    } catch (err) {
        // Rollback checkbox
        cbEl.checked = !nowChecked;
        rowEl.style.opacity = '1';
        alert('Gagal menyimpan. Silakan coba lagi.');
    } finally {
        cbEl.disabled = false;
        pLoading.style.display = 'none';
    }
}

// ─── Update cell DOM ──────────────────────────────────────────────
function updateCell(mk, add) {
    if (!activeCell) return;
    var chips = activeCell.querySelector('.mk-chips');
    if (!chips) return;

    if (add) {
        // Add chip if not already there
        if (!chips.querySelector('[data-mk-id="' + mk.id + '"]')) {
            var span = document.createElement('span');
            span.className = 'mk-chip font-mono font-bold';
            span.style.cssText = 'color:#0F766E;font-size:11px;line-height:1.3;display:block;font-family:monospace;font-weight:700';
            span.dataset.mkId   = mk.id;
            span.dataset.mkKode = mk.kode_mk;
            span.dataset.mkNama = mk.nama_mk;
            span.dataset.mkSmt  = activeSmt;
            span.dataset.mkSks  = mk.sks_total;
            span.textContent = mk.kode_mk;
            chips.appendChild(span);
        }
    } else {
        // Remove chip
        var existing = chips.querySelector('[data-mk-id="' + mk.id + '"]');
        if (existing) existing.remove();
    }

    // Update footer counts
    updateFooterCounts();
}

// ─── Update footer row (jml MK per semester) ──────────────────────
function updateFooterCounts() {
    document.querySelectorAll('tfoot td[data-smt]').forEach(function (td) {
        var s    = Number(td.dataset.smt);
        var ids  = new Set();
        document.querySelectorAll('.peta-cell[data-smt="' + s + '"] .mk-chip').forEach(function (c) {
            ids.add(Number(c.dataset.mkId));
        });
        td.textContent = ids.size > 0 ? ids.size : '—';
        td.style.color = ids.size > 0 ? '#fff' : '#FEF3C7';
    });
}

// ─── Posisi popup ────────────────────────────────────────────────
function positionPopup(cell) {
    var r    = cell.getBoundingClientRect();
    var pw   = 280;
    var ph   = 400;
    var mar  = 8;
    var top  = r.bottom + window.scrollY + mar;
    var left = r.left   + window.scrollX;

    if (left + pw > window.innerWidth - mar)
        left = window.innerWidth - pw - mar + window.scrollX;
    if (left < mar + window.scrollX) left = mar + window.scrollX;
    if (r.bottom + ph + mar > window.innerHeight)
        top = r.top + window.scrollY - ph - mar;

    popup.style.top  = top  + 'px';
    popup.style.left = left + 'px';
}

function closePetaPopup() {
    if (!popup) return;
    popup.style.display = 'none';
    activeCplId = activeCplKode = activeSmt = activeCell = null;
}

</script>
@endif
@endpush
