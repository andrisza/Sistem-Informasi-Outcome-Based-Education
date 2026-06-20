@extends('layouts.app')
@section('title', 'CPL SN-Dikti')
@section('header', 'CPL SN-Dikti (Standar Nasional Dikti)')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">CPL SN-Dikti</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.cpl-sndikti.export', $kurikulum)])
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
    <a href="{{ route('kurikulum.cpl-sndikti.create', $kurikulum) }}"
       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah CPL SN
    </a>
@endsection

@section('content')

<div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-4 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>CPL SN-Dikti ditetapkan oleh Standar Nasional Pendidikan Tinggi (Permendikbud 53/2023). Bersifat global, digunakan sebagai rujukan CPL Prodi.</span>
    <span class="ml-auto shrink-0 font-semibold">{{ $cplsnList->count() }} CPL</span>
</div>

@php
    $katOrder = ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan'];
    $katLabels = [
        'Sikap'               => 'A. SIKAP (S)',
        'Keterampilan Umum'   => 'B. KETERAMPILAN UMUM (KU)',
        'Keterampilan Khusus' => 'C. KETERAMPILAN KHUSUS (KK)',
        'Pengetahuan'         => 'D. PENGETAHUAN (P)',
    ];
    $katColors = [
        'Sikap'               => ['header' => '#3B82F6', 'badgeBg' => '#DBEAFE', 'badgeTxt' => '#1e40af', 'rowA' => '#eff6ff', 'rowB' => '#fff'],
        'Keterampilan Umum'   => ['header' => '#10B981', 'badgeBg' => '#D1FAE5', 'badgeTxt' => '#065f46', 'rowA' => '#f0fdf4', 'rowB' => '#fff'],
        'Keterampilan Khusus' => ['header' => '#F59E0B', 'badgeBg' => '#FEF3C7', 'badgeTxt' => '#78350f', 'rowA' => '#fffbeb', 'rowB' => '#fff'],
        'Pengetahuan'         => ['header' => '#8B5CF6', 'badgeBg' => '#EDE9FE', 'badgeTxt' => '#4c1d95', 'rowA' => '#f5f3ff', 'rowB' => '#fff'],
    ];
    $grouped = $cplsnList->groupBy('kategori');
@endphp

@if ($cplsnList->isEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-14 text-center text-gray-400 text-sm">
        <div class="flex flex-col items-center gap-2">
            <svg class="w-10 h-10 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Belum ada data CPL SN-Dikti.</span>
            <a href="{{ route('kurikulum.cpl-sndikti.create', $kurikulum) }}" class="text-amber-600 hover:underline font-medium">Tambah sekarang →</a>
        </div>
    </div>
@else

<div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden">
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
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-28">Kode CPL SN</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300">Deskripsi</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-24">Status</th>
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($katOrder as $kat)
                    @php $items = $grouped->get($kat, collect())->sortBy('urutan'); @endphp
                    @if ($items->isEmpty()) @continue @endif
                    @php $cc = $katColors[$kat]; @endphp

                    {{-- Kategori header --}}
                    <tr>
                        <td colspan="{{ auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip() ? 6 : 5 }}"
                            class="px-4 py-2 text-xs font-bold uppercase tracking-wider border border-gray-200"
                            style="background:{{ $cc['header'] }};color:#fff">
                            {{ $katLabels[$kat] }}
                            <span class="ml-2 font-normal opacity-80">({{ $items->count() }} CPL)</span>
                        </td>
                    </tr>

                    @foreach ($items->values() as $i => $cplsn)
                        <tr class="hover:opacity-90 transition-opacity border-b border-gray-100"
                            style="background:{{ $i % 2 === 0 ? $cc['rowA'] : $cc['rowB'] }}">
                            @if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
                            <td class="px-3 py-3 text-center border border-gray-100">
                                @if (($cplsn->status ?? 'draft') === 'draft')
                                    <input type="checkbox" name="ids[]" value="{{ $cplsn->id }}"
                                           form="batch-approve-form" class="batch-check accent-amber-500 w-4 h-4 cursor-pointer rounded">
                                @endif
                            </td>
                            @endif
                            <td class="px-3 py-3 text-center text-xs text-gray-500 border border-gray-100 font-medium">
                                {{ $i + 1 }}
                            </td>
                            <td class="px-4 py-3 text-center border border-gray-100">
                                <span class="font-mono font-bold text-xs px-2 py-0.5 rounded"
                                      style="background:{{ $cc['badgeBg'] }};color:{{ $cc['badgeTxt'] }}">{{ $cplsn->kode }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-sm border border-gray-100 leading-relaxed">
                                {{ $cplsn->deskripsi }}
                            </td>
                            <td class="px-4 py-3 text-center border border-gray-100">
                                @if (($cplsn->status ?? 'draft') === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Draft</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 border border-gray-100">
                                <div class="flex items-center justify-center gap-1 flex-wrap">
                                    @if (auth()->user()->role->value === 'kaprodi' && ($cplsn->status ?? 'draft') === 'draft' && !$kurikulum->isArsip())
                                        <form method="POST" action="{{ route('kurikulum.cpl-sndikti.approve', [$kurikulum, $cplsn]) }}"
                                              onsubmit="return confirm('Setujui {{ $cplsn->kode }}?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition-colors" title="Approve">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('kurikulum.cpl-sndikti.edit', [$kurikulum, $cplsn]) }}"
                                       class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('kurikulum.cpl-sndikti.destroy', [$kurikulum, $cplsn]) }}"
                                          onsubmit="return confirm('Hapus {{ $cplsn->kode }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endif

@if (auth()->user()->role->value === 'kaprodi' && !$kurikulum->isArsip())
<form id="batch-approve-form" method="POST" action="{{ route('kurikulum.cpl-sndikti.batch-approve', $kurikulum) }}">
    @csrf
</form>
@endif

@endsection

@push('scripts')
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
