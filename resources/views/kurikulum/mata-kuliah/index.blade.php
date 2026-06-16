@extends('layouts.app')

@section('title', 'Susunan Mata Kuliah – ' . $kurikulum->kode)
@section('header', 'Susunan Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Susunan Mata Kuliah</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.mata-kuliah.export', $kurikulum)])
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.mata-kuliah.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah MK
        </a>
    @endif
@endsection

@section('content')

@php
    $totalSks    = $mkList->sum('sks_total');
    $sortedMkList = $mkList->sortBy('kode_mk')->values();
    $isEditable   = !$kurikulum->isArsip();
@endphp

{{-- Summary bar --}}
<div class="flex flex-wrap items-center gap-4 bg-white border border-gray-100 rounded-xl px-5 py-3 mb-4 shadow-sm text-xs">
    <span class="text-gray-500">Total: <strong class="text-gray-800">{{ $mkList->count() }} MK</strong></span>
    <span class="text-gray-300">|</span>
    <span class="text-gray-500">Total SKS: <strong class="text-gray-800">{{ $totalSks }}</strong></span>
    <span class="text-gray-300">|</span>
    <span class="text-gray-500">Utama: <strong class="text-blue-700">{{ $mkList->where('kompetensi_mk','Utama')->count() }}</strong></span>
    <span class="text-gray-300">·</span>
    <span class="text-gray-500">Pendukung: <strong class="text-amber-700">{{ $mkList->where('kompetensi_mk','Pendukung')->count() }}</strong></span>
    @if ($isEditable)
        <span class="text-gray-300">|</span>
        <span class="text-emerald-600 font-medium">· Klik angka semester untuk mengubah semester MK</span>
    @endif
</div>

@if ($mkList->isEmpty())
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm px-5 py-14 text-center text-gray-400 text-sm">
        <div class="flex flex-col items-center gap-2">
            <svg class="w-10 h-10 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span>Belum ada Mata Kuliah.</span>
            @if ($isEditable)
                <a href="{{ route('kurikulum.mata-kuliah.create', $kurikulum) }}" class="text-amber-600 hover:underline font-medium">Tambah MK pertama →</a>
            @endif
        </div>
    </div>
@else

@include('layouts._search', ['target'=>'mk-table-wrap','placeholder'=>'Cari kode MK atau nama...','mode'=>'hide','rowSelector'=>'tbody tr'])

{{-- Toast notifikasi semester --}}
<div id="smt-toast"
     class="fixed right-4 bottom-4 z-50 px-4 py-2.5 rounded-xl text-xs font-semibold text-white shadow-lg hidden transition-all"
     style="background:#047857">
    <span id="smt-toast-msg">Semester diperbarui</span>
</div>

{{-- ══════════════════════════════════════════════════════════
     TABEL 9 — Susunan Mata Kuliah
     Baris = MK (diurutkan kode_mk), Kolom terakhir = Semester 1–8
════════════════════════════════════════════════════════════ --}}
<div id="mk-table-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden bg-white">
<div class="overflow-x-auto">
<table class="border-collapse text-xs w-full" style="min-width:max-content">
    <thead>
        <tr style="background:#B45309">
            <th class="px-3 py-3 text-center text-white font-bold border border-amber-600 w-8">No</th>
            <th class="px-3 py-3 text-center text-white font-bold border border-amber-600 w-20">Kode MK</th>
            <th class="px-4 py-3 text-left text-white font-bold border border-amber-600" style="min-width:160px">Nama Mata Kuliah</th>
            <th class="px-2 py-3 text-center text-white font-bold border border-amber-600 w-20">Kompetensi</th>
            <th class="px-2 py-3 text-center text-white font-bold border border-amber-600 w-10">SKS</th>
            @for ($s = 1; $s <= 8; $s++)
                <th class="px-2 py-3 text-center text-white font-bold border border-amber-600 w-9">{{ $s }}</th>
            @endfor
            @if ($isEditable)
                <th class="px-3 py-3 text-center text-white font-bold border border-amber-600 w-16"></th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($sortedMkList as $no => $mk)
            @php
                $isUtama = ($mk->kompetensi_mk ?? 'Utama') === 'Utama';
                $rowBg   = $loop->even ? '#FFFBEB' : '#FFFFFF';
            @endphp
            <tr class="hover:bg-amber-50/60 transition-colors" style="background:{{ $rowBg }}"
                data-mk-id="{{ $mk->id }}"
                data-mk-semester="{{ $mk->semester }}">

                {{-- No --}}
                <td class="px-2 py-2.5 text-center text-gray-500 font-medium border border-amber-100 text-[11px]">
                    {{ $no + 1 }}
                </td>

                {{-- Kode MK --}}
                <td class="px-3 py-2.5 text-center border border-amber-100">
                    <span class="font-mono font-bold text-blue-800 text-xs bg-blue-50 px-2 py-0.5 rounded">{{ $mk->kode_mk }}</span>
                    @if ($mk->kode_prasyarat)
                        <div class="mt-0.5">
                            <span class="text-[9px] text-orange-600 bg-orange-50 border border-orange-200 px-1 py-0.5 rounded">
                                ↑ {{ $mk->kode_prasyarat }}
                            </span>
                        </div>
                    @endif
                </td>

                {{-- Nama MK --}}
                <td class="px-4 py-2.5 border border-amber-100" style="min-width:160px">
                    <a href="{{ route('kurikulum.mata-kuliah.show', [$kurikulum, $mk]) }}"
                       class="font-medium text-gray-800 hover:text-blue-700 hover:underline text-xs">{{ $mk->nama_mk }}</a>
                </td>

                {{-- Kompetensi --}}
                <td class="px-2 py-2.5 text-center border border-amber-100">
                    @if ($isUtama)
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-semibold bg-blue-100 text-blue-800">Utama</span>
                    @else
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-semibold bg-amber-100 text-amber-800">Pendukung</span>
                    @endif
                </td>

                {{-- SKS Total (tanpa breakdown T+P) --}}
                <td class="px-2 py-2.5 text-center border border-amber-100">
                    <span class="font-bold text-gray-800 text-sm">{{ $mk->sks_total }}</span>
                </td>

                {{-- Semester 1–8 — klik untuk ubah semester (jika editable) --}}
                @for ($s = 1; $s <= 8; $s++)
                    @php $isActive = ($mk->semester == $s); @endphp
                    <td class="text-center border border-amber-100 px-0 py-0 smt-cell
                                {{ $isEditable ? 'cursor-pointer hover:bg-amber-100' : '' }}"
                        style="{{ $isActive ? 'background:#FEF3C7' : '' }};height:40px;width:36px"
                        @if ($isEditable) data-smt="{{ $s }}" @endif
                        title="{{ $isEditable ? ($isActive ? 'Semester '.$s.' (aktif)' : 'Klik untuk pindah ke semester '.$s) : '' }}">
                        @if ($isActive)
                            <svg class="w-4 h-4 text-amber-600 mx-auto pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-gray-200 text-sm pointer-events-none select-none">·</span>
                        @endif
                    </td>
                @endfor

                {{-- Aksi --}}
                @if ($isEditable)
                    <td class="px-2 py-2.5 border border-amber-100">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('kurikulum.mata-kuliah.edit', [$kurikulum, $mk]) }}"
                               class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('kurikulum.mata-kuliah.destroy', [$kurikulum, $mk]) }}"
                                  onsubmit="return confirm('Hapus MK {{ $mk->kode_mk }}? Semua CPMK terkait juga akan dihapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background:#92400E">
            <td colspan="5"
                class="px-4 py-2.5 text-right text-xs font-bold text-white border border-amber-700">
                TOTAL
            </td>
            @for ($s = 1; $s <= 8; $s++)
                @php $smtSks = $mkList->where('semester', $s)->sum('sks_total'); @endphp
                <td class="px-2 py-2.5 text-center text-xs font-bold text-white border border-amber-700">
                    {{ $smtSks > 0 ? $smtSks : '—' }}
                </td>
            @endfor
            @if ($isEditable)
                <td class="border border-amber-700"></td>
            @endif
        </tr>
    </tfoot>
</table>
</div>
</div>

{{-- MK tanpa semester --}}
@php $mkTanpaSmt = $mkList->whereNull('semester'); @endphp
@if ($mkTanpaSmt->isNotEmpty())
<div class="mt-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-800 flex items-start gap-2">
    <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <span>
        <strong>{{ $mkTanpaSmt->count() }} MK</strong> belum memiliki semester:
        {{ $mkTanpaSmt->pluck('kode_mk')->join(', ') }}
        — klik sel semester pada baris MK tersebut untuk menetapkan.
    </span>
</div>
@endif

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@if ($isEditable)
<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
    const BASE  = @json(rtrim(route('kurikulum.mata-kuliah.index', $kurikulum), '/'));
    const toast = document.getElementById('smt-toast');
    const toastMsg = document.getElementById('smt-toast-msg');
    let toastTimer = null;

    function showToast(msg, ok) {
        if (!toast) return;
        toast.style.background = ok ? '#047857' : '#B91C1C';
        toastMsg.textContent = msg;
        toast.classList.remove('hidden');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.add('hidden'), 2200);
    }

    // Attach click on semester cells
    document.querySelectorAll('tbody tr').forEach(function (row) {
        const mkId   = row.dataset.mkId;
        if (!mkId) return;

        row.querySelectorAll('.smt-cell[data-smt]').forEach(function (cell) {
            cell.addEventListener('click', async function () {
                const newSmt = parseInt(cell.dataset.smt);
                const oldSmt = parseInt(row.dataset.mkSemester) || 0;
                if (newSmt === oldSmt) return; // already active

                // Optimistic UI update
                row.querySelectorAll('.smt-cell').forEach(function (c) {
                    const s = parseInt(c.dataset.smt);
                    if (s === newSmt) {
                        c.style.background = '#FEF3C7';
                        c.innerHTML = '<svg class="w-4 h-4 text-amber-600 mx-auto pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
                    } else {
                        c.style.background = '';
                        c.innerHTML = '<span class="text-gray-200 text-sm pointer-events-none select-none">·</span>';
                    }
                });
                row.dataset.mkSemester = newSmt;

                // Also update the footer total SKS (approximate: just mark for reload)
                try {
                    const res = await fetch(BASE + '/' + mkId + '/semester', {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                        },
                        body: JSON.stringify({ semester: newSmt }),
                    });
                    const json = await res.json();
                    if (!res.ok || !json.success) throw new Error(json.message || 'HTTP ' + res.status);
                    showToast('Semester diperbarui ke Semester ' + newSmt, true);
                } catch (err) {
                    // Rollback UI
                    row.querySelectorAll('.smt-cell').forEach(function (c) {
                        const s = parseInt(c.dataset.smt);
                        if (s === oldSmt) {
                            c.style.background = '#FEF3C7';
                            c.innerHTML = '<svg class="w-4 h-4 text-amber-600 mx-auto pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
                        } else {
                            c.style.background = '';
                            c.innerHTML = '<span class="text-gray-200 text-sm pointer-events-none select-none">·</span>';
                        }
                    });
                    row.dataset.mkSemester = oldSmt;
                    showToast('Gagal: ' + err.message, false);
                }
            });
        });
    });
})();
</script>
@endif
@endpush
