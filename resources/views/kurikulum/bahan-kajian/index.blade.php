@extends('layouts.app')

@section('title', 'Bahan Kajian – ' . $kurikulum->kode)
@section('header', 'Bahan Kajian')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Bahan Kajian</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.bahan-kajian.export', $kurikulum)])
    @if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
        <button type="submit" form="batch-approve-form" id="batch-btn"
                class="hidden inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Setujui Terpilih
            <span id="batch-count" class="bg-white text-green-700 text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center"></span>
        </button>
    @endif
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.bahan-kajian.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah BK
        </a>
    @endif
@endsection

@section('content')

{{-- Info banner --}}
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 mb-4 text-xs text-emerald-800">
    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Bahan Kajian adalah topik atau pokok bahasan yang menjadi cakupan materi pembelajaran. BK akan dipetakan ke CPL dan Mata Kuliah.</span>
    <span class="ml-auto shrink-0 font-semibold">{{ $bkList->count() }} BK terdaftar</span>
</div>

@include('layouts._search', ['target'=>'bk-table-wrap','placeholder'=>'Cari BK (kode, nama, deskripsi)...','mode'=>'hide','rowSelector'=>'tbody tr'])
<div id="bk-table-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr style="background:#F59E0B">
                    @if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-8">
                        <input type="checkbox" id="select-all" title="Pilih semua draft"
                               class="accent-amber-300 w-4 h-4 cursor-pointer rounded">
                    </th>
                    @endif
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-10">No</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-24">Kode BK</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300 w-40">Bahan Kajian</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300">Deskripsi</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-28">Kompetensi</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300 w-28">Referensi</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-24">Status</th>
                    @if (!$kurikulum->isArsip())
                        <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-28">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($bkList as $bk)
                    <tr class="hover:bg-amber-50 transition-colors border-b border-amber-100"
                        style="{{ $loop->even ? 'background:#fffbeb' : 'background:#fff' }}">
                        @if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
                        <td class="px-3 py-3 text-center border border-amber-100">
                            @if (($bk->status ?? 'draft') === 'draft')
                                <input type="checkbox" name="ids[]" value="{{ $bk->id }}"
                                       form="batch-approve-form" class="batch-check accent-amber-500 w-4 h-4 cursor-pointer rounded">
                            @endif
                        </td>
                        @endif
                        <td class="px-3 py-3 text-center text-xs text-gray-500 border border-amber-100 font-medium">
                            {{ $bk->urutan ?? $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 text-center border border-amber-100">
                            <span class="font-mono font-bold text-emerald-800 text-xs bg-emerald-100 px-2 py-0.5 rounded"
                                  data-tooltip="{{ $bk->kode_bk }}: {{ $bk->nama_bk }}{{ $bk->deskripsi ? ' — '.$bk->deskripsi : '' }}">{{ $bk->kode_bk }}</span>
                        </td>
                        <td class="px-4 py-3 border border-amber-100">
                            <p class="font-medium italic text-gray-800 text-sm leading-snug">{{ $bk->nama_bk }}</p>
                        </td>
                        <td class="px-4 py-3 border border-amber-100 text-xs text-gray-600 leading-relaxed">
                            {{ $bk->deskripsi ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center border border-amber-100">
                            @php
                                $kompCls = match($bk->kompetensi ?? '') {
                                    'Utama'     => 'bg-blue-100 text-blue-800',
                                    'Pendukung' => 'bg-amber-100 text-amber-800',
                                    'Umum'      => 'bg-gray-100 text-gray-700',
                                    default     => 'bg-gray-50 text-gray-400',
                                };
                            @endphp
                            @if ($bk->kompetensi)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $kompCls }}">{{ $bk->kompetensi }}</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-amber-100 text-xs text-gray-500">
                            {{ $bk->referensi ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center border border-amber-100">
                            @if (($bk->status ?? 'draft') === 'approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Draft</span>
                            @endif
                        </td>
                        @if (!$kurikulum->isArsip())
                            <td class="px-3 py-3 border border-amber-100">
                                <div class="flex items-center justify-center gap-1 flex-wrap">
                                    @if (auth()->user()->role->value === 'kaprodi' && ($bk->status ?? 'draft') === 'draft')
                                        <form method="POST" action="{{ route('kurikulum.bahan-kajian.approve', [$kurikulum, $bk]) }}"
                                              onsubmit="return confirm('Setujui Bahan Kajian {{ $bk->kode_bk }}?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition-colors" title="Approve">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('kurikulum.bahan-kajian.edit', [$kurikulum, $bk]) }}"
                                       class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('kurikulum.bahan-kajian.destroy', [$kurikulum, $bk]) }}"
                                          onsubmit="return confirm('Hapus Bahan Kajian {{ $bk->kode_bk }}?')">
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
                @empty
                    <tr>
                        <td colspan="{{ $kurikulum->isArsip() ? 7 : (auth()->user()->role->value === 'kaprodi' ? 9 : 8) }}" class="px-5 py-14 text-center text-sm text-gray-400 bg-amber-50/30">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>Belum ada Bahan Kajian.</span>
                                @if (!$kurikulum->isArsip())
                                    <a href="{{ route('kurikulum.bahan-kajian.create', $kurikulum) }}" class="text-emerald-600 hover:underline font-medium">Tambah BK pertama →</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
<form id="batch-approve-form" method="POST" action="{{ route('kurikulum.bahan-kajian.batch-approve', $kurikulum) }}">
    @csrf
</form>
@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
<script>
(function() {
    const selectAll  = document.getElementById('select-all');
    const batchBtn   = document.getElementById('batch-btn');
    const batchCount = document.getElementById('batch-count');

    function updateBatch() {
        const checks  = document.querySelectorAll('.batch-check');
        const checked = document.querySelectorAll('.batch-check:checked');
        const n = checked.length;
        if (batchBtn) {
            batchBtn.classList.toggle('hidden', n === 0);
            if (batchCount) batchCount.textContent = n;
        }
        if (selectAll) {
            selectAll.checked       = checks.length > 0 && n === checks.length;
            selectAll.indeterminate = n > 0 && n < checks.length;
        }
    }

    selectAll?.addEventListener('change', function () {
        document.querySelectorAll('.batch-check').forEach(cb => { cb.checked = this.checked; });
        updateBatch();
    });

    document.querySelectorAll('.batch-check').forEach(cb => cb.addEventListener('change', updateBatch));
})();
</script>
@endpush
