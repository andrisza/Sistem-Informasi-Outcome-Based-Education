@extends('layouts.app')

@section('title', 'CPL Prodi – ' . $kurikulum->kode)
@section('header', 'CPL Prodi')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">CPL Prodi</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.cpl-prodi.export', $kurikulum)])
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
    @if (!$kurikulum->isArsip() && $cplList->isNotEmpty())
        <a href="{{ route('kurikulum.cpl-prodi.create', $kurikulum) }}"
           style="display:inline-flex;align-items:center;gap:8px;background:#7C3AED;color:#fff;font-size:14px;font-weight:600;padding:8px 16px;border-radius:8px;text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,.12)">
            <svg style="width:16px;height:16px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah CPL
        </a>
    @endif
@endsection

@section('content')

{{-- Alur pengisian banner --}}
<div class="bg-violet-50 border border-violet-200 rounded-xl px-4 py-3 mb-4 text-xs text-violet-800">
    <div class="flex items-center gap-3">
        <svg class="w-4 h-4 text-violet-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Capaian Pembelajaran Lulusan (CPL) Prodi adalah kemampuan spesifik yang harus dikuasai lulusan, mengacu pada CPL SN-Dikti.</span>
        <span class="ml-auto shrink-0 font-semibold">{{ $cplList->count() }} CPL terdaftar</span>
    </div>
    <div class="mt-2 pl-7 flex items-center gap-1.5 text-violet-600 flex-wrap">
        <span class="font-medium">Alur pengisian:</span>
        <a href="{{ route('kurikulum.cpl-sndikti.index', $kurikulum) }}" class="hover:underline">CPL SN-Dikti</a>
        <span class="opacity-50">→</span>
        <span class="font-semibold text-violet-800">CPL Prodi</span>
        <span class="opacity-50">→</span>
        <a href="{{ route('kurikulum.pivot.cplsn-cplp', $kurikulum) }}" class="hover:underline font-medium text-blue-600">Pemetaan CPL-SN↔CPL-P</a>
        <span class="ml-1 text-violet-500">(Referensi terisi otomatis setelah pemetaan)</span>
    </div>
</div>

@if ($cplList->isEmpty())
    {{-- Empty state — tombol Tambah di tengah --}}
    @if (!$kurikulum->isArsip())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-16 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-violet-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-400 text-sm mb-5">Belum ada CPL Prodi yang ditambahkan.</p>
            <a href="{{ route('kurikulum.cpl-prodi.create', $kurikulum) }}"
               style="display:inline-flex;align-items:center;gap:8px;background:#7C3AED;color:#fff;font-size:14px;font-weight:600;padding:10px 22px;border-radius:8px;text-decoration:none">
                <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah CPL Pertama
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-14 text-center text-gray-400 text-sm">
            Belum ada CPL Prodi.
        </div>
    @endif
@else

@include('layouts._search', ['target'=>'cpl-table-wrap','placeholder'=>'Cari CPL (kode atau deskripsi)...','mode'=>'hide','rowSelector'=>'tbody tr.cpl-row'])

<div id="cpl-table-wrap" class="rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr style="background:#7C3AED">
                    @if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-violet-400 w-8">
                        <input type="checkbox" id="select-all" title="Pilih semua draft"
                               class="accent-violet-300 w-4 h-4 cursor-pointer rounded">
                    </th>
                    @endif
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-violet-400 w-10">No</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-violet-400 w-28">Kode CPL</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-violet-400">Deskripsi CPL</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-violet-400 w-48"
                        title="Diisi otomatis dari Pemetaan CPL-SN↔CPL-P">Referensi SN-Dikti</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-violet-400 w-24">Status</th>
                    @if (!$kurikulum->isArsip())
                        <th class="px-3 py-3 text-center text-xs font-bold text-white border border-violet-400 w-28">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($cplList as $i => $cpl)
                    <tr class="cpl-row hover:bg-violet-50/40 transition-colors border-b border-gray-100"
                        style="background:{{ $i % 2 === 0 ? '#faf5ff' : '#fff' }}">
                        @if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
                        <td class="px-3 py-3 text-center border border-gray-100">
                            @if (($cpl->status ?? 'draft') === 'draft')
                                <input type="checkbox" name="ids[]" value="{{ $cpl->id }}"
                                       form="batch-approve-form" class="batch-check accent-violet-500 w-4 h-4 cursor-pointer rounded">
                            @endif
                        </td>
                        @endif
                        <td class="px-3 py-3 text-center text-xs text-gray-500 border border-gray-100 font-medium">
                            {{ $i + 1 }}
                        </td>
                        <td class="px-4 py-3 text-center border border-gray-100">
                            <span class="font-mono font-bold text-xs bg-violet-100 text-violet-800 px-2 py-0.5 rounded cursor-help"
                                  data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                  data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 text-sm border border-gray-100 leading-relaxed">
                            {{ $cpl->deskripsi }}
                        </td>
                        <td class="px-4 py-3 border border-gray-100 text-xs leading-snug">
                            @if ($cpl->referensi)
                                <span class="font-mono text-violet-700 font-medium">{{ $cpl->referensi }}</span>
                            @else
                                <span class="text-gray-300 italic">Belum dipetakan</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center border border-gray-100">
                            @if (($cpl->status ?? 'draft') === 'approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Draft</span>
                            @endif
                        </td>
                        @if (!$kurikulum->isArsip())
                            <td class="px-3 py-3 border border-gray-100">
                                <div class="flex items-center justify-center gap-1 flex-wrap">
                                    @if (auth()->user()->role->value === 'kaprodi' && ($cpl->status ?? 'draft') === 'draft')
                                        <form method="POST" action="{{ route('kurikulum.cpl-prodi.approve', [$kurikulum, $cpl]) }}"
                                              onsubmit="return confirm('Setujui CPL {{ $cpl->kode_cpl }}?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition-colors" title="Approve">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('kurikulum.cpl-prodi.edit', [$kurikulum, $cpl]) }}"
                                       class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('kurikulum.cpl-prodi.destroy', [$kurikulum, $cpl]) }}"
                                          onsubmit="return confirm('Hapus CPL {{ $cpl->kode_cpl }}?')">
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
        </table>
    </div>
</div>

@endif

@if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
<form id="batch-approve-form" method="POST" action="{{ route('kurikulum.cpl-prodi.batch-approve', $kurikulum) }}">
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
