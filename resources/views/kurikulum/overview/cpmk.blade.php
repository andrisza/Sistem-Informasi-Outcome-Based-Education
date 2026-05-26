@extends('layouts.app')

@section('title', 'Pemetaan MK → CPMK → Sub-CPMK')
@section('header', 'Pemetaan MK → CPMK → Sub-CPMK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">CPMK & Sub-CPMK</span>
@endsection

@section('content')

<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-4 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p>Ringkasan seluruh CPMK dan Sub-CPMK per Mata Kuliah. Klik kode MK untuk membuka detail/CRUD CPMK pada MK terkait.</p>
</div>

<div class="space-y-3">
@foreach ($mkList as $mk)
    @php $cpmkList = $mk->cpmk ?? collect(); @endphp
    <div class="bg-white border {{ $cpmkList->isEmpty() ? 'border-amber-200' : 'border-gray-200' }} rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-4 py-2.5 {{ $cpmkList->isEmpty() ? 'bg-amber-50' : 'bg-blue-50' }}">
            <span class="text-[10px] font-bold uppercase text-gray-500">Semester {{ $mk->semester }}</span>
            <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mk]) }}"
               class="font-mono font-bold text-blue-700 text-xs bg-white px-2 py-0.5 rounded hover:underline"
               data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} ({{ $mk->sks_total }} SKS)"
               data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</a>
            <span class="text-sm font-medium text-gray-800 flex-1">{{ $mk->nama_mk }}</span>
            <span class="text-xs text-gray-500">{{ $cpmkList->count() }} CPMK</span>
        </div>

        @if ($cpmkList->isNotEmpty())
            <div class="divide-y divide-gray-100">
                @foreach ($cpmkList as $cpmk)
                    <div class="px-4 py-3 grid grid-cols-1 lg:grid-cols-12 gap-3 hover:bg-gray-50">
                        <div class="lg:col-span-4 flex items-start gap-2">
                            <span class="font-mono font-semibold text-violet-700 text-xs bg-violet-50 px-1.5 py-0.5 rounded shrink-0">{{ $cpmk->kode_cpmk }}</span>
                            <div>
                                <p class="text-xs text-gray-700 leading-snug">{{ \Str::limit($cpmk->deskripsi, 110) }}</p>
                                @if ($cpmk->cplProdi)
                                    <span class="inline-block mt-1 text-[10px] font-mono text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded cursor-help"
                                          data-tooltip="{{ $cpmk->cplProdi->kode_cpl }}: {{ $cpmk->cplProdi->deskripsi }}"
                                          data-tip-label="CPL Prodi terkait">↳ {{ $cpmk->cplProdi->kode_cpl }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="lg:col-span-8">
                            @if ($cpmk->subCpmk->isNotEmpty())
                                <div class="space-y-1">
                                    @foreach ($cpmk->subCpmk as $sub)
                                        <div class="flex items-start gap-2 text-xs">
                                            <span class="font-mono font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded shrink-0">{{ $sub->kode_sub_cpmk }}</span>
                                            <span class="text-gray-600 leading-snug flex-1">{{ \Str::limit($sub->deskripsi, 120) }}</span>
                                            <span class="text-emerald-600 font-semibold shrink-0">{{ number_format((float) $sub->bobot, 0) }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-amber-600">Belum ada Sub-CPMK.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-4 py-3 text-xs text-amber-700">
                Belum ada CPMK.
                <a href="{{ route('kurikulum.mata-kuliah.cpmk.create', [$kurikulum, $mk]) }}" class="underline font-medium">Tambah CPMK →</a>
            </div>
        @endif
    </div>
@endforeach
</div>

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
