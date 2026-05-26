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
    <p class="flex-1">
        Klik <strong>×</strong> pada kode MK untuk menghapus dari sel. Pakai dropdown <strong>+ MK</strong> di setiap sel untuk menambah.
        <span class="text-blue-600">Menambah otomatis membuat relasi CPL↔BK dan MK↔BK di matriks primer.</span>
    </p>
</div>

{{-- Stats --}}
@php
    $totalRelasi = collect($matriks)->flatten(2)->count();
    $bkTerisi   = count(array_filter($matriks, fn($v) => !empty($v)));
@endphp
<div class="flex items-center gap-4 text-xs text-gray-500 mb-3 flex-wrap">
    <span><strong class="text-gray-700" id="total-relasi">{{ $totalRelasi }}</strong> total relasi</span>
    <span><strong class="text-gray-700">{{ $bkTerisi }}/{{ $bkList->count() }}</strong> BK dipetakan</span>
    <span class="text-emerald-600 font-medium">· Auto-save aktif</span>
</div>

<div class="rounded-xl border border-amber-200 shadow-sm overflow-hidden bg-white">
    <div class="overflow-x-auto">
        <table class="border-collapse w-full text-xs">
            <thead>
                <tr>
                    <th style="background:#F59E0B;min-width:84px;width:84px" class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                        <span class="text-amber-100">`</span>
                    </th>
                    @foreach ($cplList as $cpl)
                        <th style="background:#F59E0B;min-width:120px"
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
                        <td style="min-width:84px;width:84px;background:#FCD34D"
                            class="px-3 py-3 text-center border border-amber-300 sticky left-0 z-10 align-middle">
                            <span class="font-mono font-bold text-amber-900 text-xs cursor-help"
                                  data-tooltip="{{ $bk->kode_bk }}: {{ $bk->nama_bk }}"
                                  data-tip-label="Bahan Kajian">{{ $bk->kode_bk }}</span>
                        </td>
                        @foreach ($cplList as $cpl)
                            @php $mks       = $rowMatriks[$cpl->id] ?? []; @endphp
                            @php $mkIdsHere = $rowMatriksIds[$cpl->id] ?? []; @endphp
                            <td class="border border-amber-100 align-top p-1.5 cell-3d {{ count($mks) ? 'bg-amber-50/40' : '' }}"
                                style="min-width:120px"
                                data-bk="{{ $bk->id }}" data-cpl="{{ $cpl->id }}">
                                <div class="chip-list flex flex-col gap-1 mb-1">
                                    @foreach ($mks as $mk)
                                        <span class="chip-3d inline-flex items-center justify-between gap-1 bg-white border border-blue-200 rounded px-1.5 py-0.5"
                                              data-mk="{{ $mk->id }}">
                                            <span class="font-mono text-[10px] font-semibold text-blue-700 cursor-help"
                                                  data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                                  data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                            @unless ($kurikulum->isArsip())
                                                <button type="button" class="chip-3d-rm text-red-400 hover:text-red-700 font-bold leading-none ml-0.5" title="Hapus dari sel">×</button>
                                            @endunless
                                        </span>
                                    @endforeach
                                </div>
                                @unless ($kurikulum->isArsip())
                                    <select class="cell-add-mk text-[10px] w-full border border-gray-200 rounded px-1 py-0.5 text-gray-500 bg-white">
                                        <option value="">+ tambah MK</option>
                                        @foreach ($mkList as $mk)
                                            @if (!in_array($mk->id, $mkIdsHere))
                                                <option value="{{ $mk->id }}">{{ $mk->kode_mk }} — S{{ $mk->semester }}</option>
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

{{-- Status toast (reused from autosave partial style) --}}
<div id="cpl3d-status" class="fixed right-4 bottom-4 z-50 px-3 py-2 rounded-lg text-xs font-semibold text-white shadow-lg hidden">
    <span class="msg">Tersimpan</span>
</div>

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

    function showStatus(msg, kind) {
        if (!statusEl) return;
        statusEl.className = 'fixed right-4 bottom-4 z-50 px-3 py-2 rounded-lg text-xs font-semibold text-white shadow-lg';
        if (kind === 'ok')     statusEl.classList.add('bg-emerald-600');
        if (kind === 'error')  statusEl.classList.add('bg-red-600');
        if (kind === 'saving') statusEl.classList.add('bg-slate-700');
        statusEl.querySelector('.msg').textContent = msg;
        statusEl.classList.remove('hidden');
        clearTimeout(showStatus._t);
        if (kind !== 'saving') showStatus._t = setTimeout(() => statusEl.classList.add('hidden'), 1500);
    }

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
            showStatus('Tersimpan', 'ok');
            return true;
        } catch (e) {
            showStatus('Gagal: ' + e.message, 'error');
            return false;
        }
    }

    // Remove chip (× button)
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.chip-3d-rm');
        if (!btn) return;
        const chip = btn.closest('.chip-3d');
        const cell = chip.closest('.cell-3d');
        const mk   = parseInt(chip.dataset.mk);
        const bk   = parseInt(cell.dataset.bk);
        const cpl  = parseInt(cell.dataset.cpl);

        const ok = await toggle(bk, cpl, mk, false);
        if (!ok) return;

        // Remove chip from UI
        chip.remove();
        // Re-add the MK option back to the cell's <select>
        const sel = cell.querySelector('.cell-add-mk');
        if (sel) {
            const opt = document.createElement('option');
            opt.value = mk;
            opt.textContent = chip.querySelector('span').textContent.trim();
            // insert sorted by kode_mk text
            const existing = Array.from(sel.options).slice(1); // skip placeholder
            const insertBefore = existing.find(o => o.textContent > opt.textContent);
            sel.insertBefore(opt, insertBefore || null);
        }
        // Update cell background + total counter
        if (!cell.querySelector('.chip-3d')) cell.classList.remove('bg-amber-50/40');
        updateTotal(-1);
    });

    // Add MK via <select>
    document.addEventListener('change', async (e) => {
        const sel = e.target.closest('.cell-add-mk');
        if (!sel) return;
        const mk = parseInt(sel.value);
        if (!mk) return;
        const cell = sel.closest('.cell-3d');
        const bk   = parseInt(cell.dataset.bk);
        const cpl  = parseInt(cell.dataset.cpl);
        const optionText = sel.options[sel.selectedIndex].textContent;
        const mkCode = optionText.split('—')[0].trim();

        const ok = await toggle(bk, cpl, mk, true);
        if (!ok) { sel.value = ''; return; }

        // Add chip to UI
        const list = cell.querySelector('.chip-list');
        const chip = document.createElement('span');
        chip.className = 'chip-3d inline-flex items-center justify-between gap-1 bg-white border border-blue-200 rounded px-1.5 py-0.5';
        chip.dataset.mk = mk;
        chip.innerHTML = `<span class="font-mono text-[10px] font-semibold text-blue-700">${mkCode}</span>
                          <button type="button" class="chip-3d-rm text-red-400 hover:text-red-700 font-bold leading-none ml-0.5" title="Hapus">×</button>`;
        list.appendChild(chip);

        // Remove selected option from select, reset
        sel.removeChild(sel.options[sel.selectedIndex]);
        sel.value = '';
        cell.classList.add('bg-amber-50/40');
        updateTotal(+1);
    });

    function updateTotal(delta) {
        const el = document.getElementById('total-relasi');
        if (!el) return;
        el.textContent = (parseInt(el.textContent) || 0) + delta;
    }
})();
</script>
@endunless
@endpush
