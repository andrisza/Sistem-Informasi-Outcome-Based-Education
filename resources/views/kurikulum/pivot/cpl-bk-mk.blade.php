@extends('layouts.app')

@section('title', 'Matriks CPL ↔ BK ↔ MK')
@section('header', 'Pemetaan CPL ↔ Bahan Kajian ↔ Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Matriks CPL ↔ BK ↔ MK</span>
@endsection

@section('content')

@if ($cplList->isEmpty() || $bkList->isEmpty() || $mkById->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">CPL Prodi, Bahan Kajian, dan Mata Kuliah harus diisi terlebih dahulu.</p>
        </div>
    </div>
@else

{{-- Info banner --}}
<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-3 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="flex-1 space-y-1">
        <p>
            <strong>Cara pakai:</strong>
            Pilih Mata Kuliah dari dropdown <strong>+ Tambah MK</strong> (dikelompokkan per semester) untuk menambah ke sel.
            Klik tombol <strong class="text-red-600">×</strong> pada chip MK untuk menghapus dari sel.
        </p>
        <p class="text-blue-600">
            <svg class="w-3 h-3 inline-block mr-0.5 -mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
            Setiap perubahan di sini <strong>otomatis tersimpan</strong> dan menyinkronkan matriks primer CPL↔BK serta MK↔BK.
        </p>
    </div>
</div>

{{-- Legend --}}
<div class="flex flex-wrap items-center gap-3 mb-3 text-[11px] text-gray-600">
    <span class="font-medium text-gray-700">Keterangan:</span>
    <span class="inline-flex items-center gap-1">
        <span class="inline-flex items-center gap-1 bg-white border border-blue-200 rounded px-1.5 py-0.5">
            <span class="font-mono font-semibold text-blue-700 text-[10px]">MK01</span>
            <span class="text-[9px] text-slate-500 font-medium bg-slate-100 rounded px-1 leading-4">S1</span>
            <span class="text-red-400 font-bold leading-none">×</span>
        </span>
        = chip MK (klik × hapus)
    </span>
    <span class="inline-flex items-center gap-1">
        <span class="inline-flex items-center gap-1 bg-amber-50 border border-dashed border-amber-300 rounded px-2 py-0.5 text-amber-700 font-semibold">
            + Tambah MK
        </span>
        = dropdown untuk menambah
    </span>
    <span class="inline-flex items-center gap-1">
        <span class="text-emerald-600 font-medium">Auto-save aktif</span>
        <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
    </span>
</div>

{{-- Stats --}}
@php
    $totalRelasi = collect($matriks)->flatten(2)->count();
    $bkTerisi   = count(array_filter($matriks, fn($v) => !empty($v)));
    $mkBySemester = $mkList->groupBy('semester')->sortKeys();
@endphp
<div class="flex items-center gap-4 text-xs text-gray-500 mb-3 flex-wrap">
    <span><strong class="text-gray-700" id="total-relasi">{{ $totalRelasi }}</strong> total relasi</span>
    <span><strong class="text-gray-700">{{ $bkTerisi }}/{{ $bkList->count() }}</strong> BK dipetakan</span>
    <span><strong class="text-gray-700">{{ $mkList->count() }}</strong> MK tersedia</span>
</div>

@include('layouts._search', ['target'=>'cpl-bk-mk-wrap','placeholder'=>'Cari BK (kode/nama)...','mode'=>'dim','rowSelector'=>'tbody tr'])
<div id="cpl-bk-mk-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden bg-white">
    <div class="overflow-x-auto">
        <table class="border-collapse w-full text-xs">
            <thead>
                <tr>
                    <th style="background:#F59E0B;min-width:100px;width:100px" class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                        <div class="text-amber-100 text-[10px] font-normal leading-tight">BK \ CPL</div>
                    </th>
                    @foreach ($cplList as $cpl)
                        <th style="background:#F59E0B;min-width:140px"
                            class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">
                            <span class="font-mono cursor-help block"
                                  data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                  data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($bkList as $bk)
                    @php $rowMatriks    = $matriks[$bk->id] ?? []; @endphp
                    @php $rowMatriksIds = $matriksIds[$bk->id] ?? []; @endphp
                    <tr class="hover:bg-amber-50/30">
                        <td style="min-width:100px;width:100px;background:#FCD34D"
                            class="px-3 py-3 text-center border border-amber-300 sticky left-0 z-10 align-middle">
                            <span class="font-mono font-bold text-amber-900 text-xs cursor-help block"
                                  data-tooltip="{{ $bk->kode_bk }}: {{ $bk->nama_bk }}"
                                  data-tip-label="Bahan Kajian">{{ $bk->kode_bk }}</span>
                            @if (!empty($bk->nama_bk))
                                <span class="text-amber-700 text-[9px] block leading-tight mt-0.5 truncate max-w-[80px] mx-auto" title="{{ $bk->nama_bk }}">{{ Str::limit($bk->nama_bk, 20) }}</span>
                            @endif
                        </td>
                        @foreach ($cplList as $cpl)
                            @php $mks       = $rowMatriks[$cpl->id] ?? []; @endphp
                            @php $mkIdsHere = $rowMatriksIds[$cpl->id] ?? []; @endphp
                            <td class="border border-amber-100 align-top p-1.5 cell-3d {{ count($mks) ? 'bg-amber-50/40' : 'bg-white' }}"
                                style="min-width:140px"
                                data-bk="{{ $bk->id }}" data-cpl="{{ $cpl->id }}">

                                {{-- Chip list --}}
                                <div class="chip-list flex flex-col gap-1 mb-1.5">
                                    @foreach ($mks as $mk)
                                        <span class="chip-3d inline-flex items-center justify-between gap-1 bg-white border border-blue-200 rounded px-1.5 py-0.5 shadow-sm"
                                              data-mk="{{ $mk->id }}"
                                              data-mk-code="{{ $mk->kode_mk }}"
                                              data-mk-sem="{{ $mk->semester }}">
                                            <span class="font-mono text-[10px] font-semibold text-blue-700 cursor-help"
                                                  data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                                  data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                            <span class="text-[9px] text-slate-500 font-medium bg-slate-100 rounded px-1 leading-4">S{{ $mk->semester }}</span>
                                            @unless ($kurikulum->isArsip())
                                                <button type="button" class="chip-3d-rm text-red-400 hover:text-red-700 font-bold leading-none ml-0.5" title="Hapus dari sel">×</button>
                                            @endunless
                                        </span>
                                    @endforeach
                                </div>

                                {{-- Add MK dropdown --}}
                                @unless ($kurikulum->isArsip())
                                    @php
                                        // Build available MK grouped by semester for this cell
                                        $availBySemester = $mkBySemester->map(fn($group) => $group->filter(fn($mk) => !in_array($mk->id, $mkIdsHere)));
                                    @endphp
                                    <select class="cell-add-mk text-[10px] w-full border border-dashed border-amber-300 rounded px-1 py-0.5 text-amber-700 bg-amber-50 hover:border-amber-400 focus:outline-none focus:ring-1 focus:ring-amber-400 cursor-pointer">
                                        <option value="">+ Tambah MK</option>
                                        @foreach ($availBySemester as $sem => $semMks)
                                            @if ($semMks->isNotEmpty())
                                                <optgroup label="Semester {{ $sem }}">
                                                    @foreach ($semMks as $mk)
                                                        <option value="{{ $mk->id }}"
                                                                data-code="{{ $mk->kode_mk }}"
                                                                data-sem="{{ $mk->semester }}">{{ $mk->kode_mk }} — {{ Str::limit($mk->nama_mk, 28) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                @endunless
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Status toast --}}
<div id="cpl3d-status" class="fixed right-4 bottom-4 z-50 px-3 py-2 rounded-lg text-xs font-semibold text-white shadow-lg hidden transition-all">
    <span class="msg">Tersimpan</span>
</div>

{{-- Flash animation styles --}}
<style>
@keyframes flash-green {
    0%   { background-color: #d1fae5; }
    60%  { background-color: #d1fae5; }
    100% { background-color: inherit; }
}
@keyframes flash-red {
    0%   { background-color: #fee2e2; }
    60%  { background-color: #fee2e2; }
    100% { background-color: inherit; }
}
.cell-flash-add  { animation: flash-green 0.8s ease-out; }
.cell-flash-rm   { animation: flash-red   0.8s ease-out; }
</style>

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@unless ($kurikulum->isArsip())
<script>
(function () {
    const url   = @json(route('kurikulum.pivot.toggle', $kurikulum));
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const statusEl = document.getElementById('cpl3d-status');

    // ── Status toast ─────────────────────────────────────────────────────────
    function showStatus(msg, kind) {
        if (!statusEl) return;
        statusEl.className = 'fixed right-4 bottom-4 z-50 px-3 py-2 rounded-lg text-xs font-semibold text-white shadow-lg transition-all';
        if (kind === 'ok')     statusEl.classList.add('bg-emerald-600');
        if (kind === 'error')  statusEl.classList.add('bg-red-600');
        if (kind === 'saving') statusEl.classList.add('bg-slate-700');
        statusEl.querySelector('.msg').textContent = msg;
        statusEl.classList.remove('hidden');
        clearTimeout(showStatus._t);
        if (kind !== 'saving') showStatus._t = setTimeout(() => statusEl.classList.add('hidden'), 2000);
    }

    // ── Cell flash feedback ────────────────────────────────────────────────────
    function flashCell(cell, type) {
        cell.classList.remove('cell-flash-add', 'cell-flash-rm');
        // Force reflow so the animation restarts even if called repeatedly
        void cell.offsetWidth;
        cell.classList.add(type === 'add' ? 'cell-flash-add' : 'cell-flash-rm');
        cell.addEventListener('animationend', () => {
            cell.classList.remove('cell-flash-add', 'cell-flash-rm');
        }, { once: true });
    }

    // ── AJAX toggle ───────────────────────────────────────────────────────────
    async function toggle(bk, cpl, mk, checked) {
        showStatus('Menyimpan…', 'saving');
        try {
            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    table:   'pivot_cpl_bk_mk',
                    keys:    { id_cpl: cpl, id_bk: bk, id_mk: mk },
                    checked: checked,
                }),
            });
            const json = await res.json();
            if (!res.ok || !json.ok) throw new Error(json.message || ('HTTP ' + res.status));
            showStatus(json.derived ? 'Tersimpan & relasi primer disinkronkan' : 'Tersimpan', 'ok');
            return true;
        } catch (e) {
            showStatus('Gagal: ' + e.message, 'error');
            return false;
        }
    }

    // ── Remove chip (× button) ────────────────────────────────────────────────
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.chip-3d-rm');
        if (!btn) return;

        const chip    = btn.closest('.chip-3d');
        const cell    = chip.closest('.cell-3d');
        const mk      = parseInt(chip.dataset.mk);
        const mkCode  = chip.dataset.mkCode  || chip.querySelector('span')?.textContent.trim();
        const mkSem   = chip.dataset.mkSem   || '';
        const bk      = parseInt(cell.dataset.bk);
        const cpl     = parseInt(cell.dataset.cpl);

        // Optimistic: disable button to prevent double-click
        btn.disabled = true;

        const ok = await toggle(bk, cpl, mk, false);
        if (!ok) { btn.disabled = false; return; }

        // Flash the cell red before removing
        flashCell(cell, 'remove');

        // Remove chip from UI
        chip.remove();

        // Re-add the MK option back to the cell's <select> in the correct optgroup
        const sel = cell.querySelector('.cell-add-mk');
        if (sel && mkCode) {
            const semLabel = mkSem ? `Semester ${mkSem}` : null;
            let optgroup = semLabel
                ? Array.from(sel.querySelectorAll('optgroup')).find(g => g.label === semLabel)
                : null;

            if (!optgroup && semLabel) {
                // Create the optgroup if missing (sorted by semester number)
                optgroup = document.createElement('optgroup');
                optgroup.label = semLabel;
                // Find correct insertion position among existing optgroups
                const groups = Array.from(sel.querySelectorAll('optgroup'));
                const semNum = parseInt(mkSem) || 999;
                const after  = groups.find(g => parseInt(g.label.replace('Semester ', '')) > semNum);
                sel.insertBefore(optgroup, after || null);
            }

            const opt       = document.createElement('option');
            opt.value       = mk;
            opt.dataset.code = mkCode;
            opt.dataset.sem  = mkSem;
            // Re-use the label format: "MKXX — Nama MK"
            const titleSpan = chip.querySelector('[data-tooltip]');
            const fullTitle = titleSpan ? titleSpan.getAttribute('data-tooltip').split(':')[0].trim() : mkCode;
            opt.textContent = mkCode + (titleSpan ? ' — ' + (titleSpan.getAttribute('data-tooltip').split(':')[1] || '').split('(')[0].trim() : '');

            if (optgroup) {
                // Insert sorted inside optgroup
                const siblings = Array.from(optgroup.options || optgroup.querySelectorAll('option'));
                const before   = siblings.find(o => o.textContent > opt.textContent);
                optgroup.insertBefore(opt, before || null);
            } else {
                sel.appendChild(opt);
            }
        }

        // Update cell background + total counter
        if (!cell.querySelector('.chip-3d')) cell.classList.remove('bg-amber-50/40');
        updateTotal(-1);
    });

    // ── Add MK via <select> ───────────────────────────────────────────────────
    document.addEventListener('change', async (e) => {
        const sel = e.target.closest('.cell-add-mk');
        if (!sel) return;

        const mk  = parseInt(sel.value);
        if (!mk) return;

        const cell        = sel.closest('.cell-3d');
        const bk          = parseInt(cell.dataset.bk);
        const cpl         = parseInt(cell.dataset.cpl);
        const selOpt      = sel.options[sel.selectedIndex];
        const mkCode      = selOpt.dataset.code  || selOpt.textContent.split('—')[0].trim();
        const mkSem       = selOpt.dataset.sem   || '';
        const optionText  = selOpt.textContent;

        // Disable select during save
        sel.disabled = true;

        const ok = await toggle(bk, cpl, mk, true);
        sel.disabled = false;
        if (!ok) { sel.value = ''; return; }

        // Build and insert chip
        const list  = cell.querySelector('.chip-list');
        const chip  = document.createElement('span');
        chip.className  = 'chip-3d inline-flex items-center justify-between gap-1 bg-white border border-blue-200 rounded px-1.5 py-0.5 shadow-sm';
        chip.dataset.mk      = mk;
        chip.dataset.mkCode  = mkCode;
        chip.dataset.mkSem   = mkSem;
        chip.innerHTML = `
            <span class="font-mono text-[10px] font-semibold text-blue-700">${mkCode}</span>
            <span class="text-[9px] text-slate-500 font-medium bg-slate-100 rounded px-1 leading-4">S${mkSem}</span>
            <button type="button" class="chip-3d-rm text-red-400 hover:text-red-700 font-bold leading-none ml-0.5" title="Hapus">×</button>`;
        list.appendChild(chip);

        // Remove from select (option may be inside an optgroup)
        selOpt.remove();
        sel.value = '';

        // Remove empty optgroups
        Array.from(sel.querySelectorAll('optgroup')).forEach(g => {
            if (g.children.length === 0) g.remove();
        });

        // Flash the cell green
        cell.classList.add('bg-amber-50/40');
        flashCell(cell, 'add');

        updateTotal(+1);
    });

    // ── Total counter ─────────────────────────────────────────────────────────
    function updateTotal(delta) {
        const el = document.getElementById('total-relasi');
        if (!el) return;
        el.textContent = Math.max(0, (parseInt(el.textContent) || 0) + delta);
    }
})();
</script>
@endunless
@endpush
