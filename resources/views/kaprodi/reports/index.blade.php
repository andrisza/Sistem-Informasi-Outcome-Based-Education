@extends('layouts.app')

@section('title', 'Laporan')
@section('header', 'Laporan OBE')

@section('breadcrumb')
    <span>Dashboard</span>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Laporan</span>
@endsection

@section('content')

{{-- Info banner --}}
<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-6 text-sm text-blue-800">
    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        <p class="font-semibold mb-0.5">Laporan Ketercapaian OBE</p>
        <p class="text-xs text-blue-700/80">Pilih kurikulum yang ingin dianalisis, lalu klik <span class="font-semibold">Buka Laporan</span> untuk melihat detail capaian.</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">

    {{-- Ketercapaian CPL --}}
    <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-200 transition-all flex flex-col overflow-hidden">
        <div class="p-5 flex flex-col gap-3 flex-1">
            <div class="flex items-start justify-between gap-3">
                <div class="w-11 h-11 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-wider bg-blue-50 text-blue-700 px-2 py-1 rounded">Per Kurikulum</span>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 mb-1">Ketercapaian CPL</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Laporan capaian Capaian Pembelajaran Lulusan per kurikulum.</p>
            </div>
        </div>
        <div class="px-5 pb-5 pt-3 border-t border-gray-100 bg-gray-50/50 space-y-2.5">
            <label class="block text-xs font-medium text-gray-600">Pilih Kurikulum</label>
            <select id="sel-cpl"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">— Pilih Kurikulum —</option>
                @foreach ($kurikulumList as $k)
                    <option value="{{ $k->id }}">{{ $k->kode }} – {{ $k->nama_kurikulum }}</option>
                @endforeach
            </select>
            <button type="button" onclick="goReport('cpl')"
                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                Buka Laporan
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Ketercapaian PL --}}
    <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-violet-200 transition-all flex flex-col overflow-hidden">
        <div class="p-5 flex flex-col gap-3 flex-1">
            <div class="flex items-start justify-between gap-3">
                <div class="w-11 h-11 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-wider bg-violet-50 text-violet-700 px-2 py-1 rounded">Per Kurikulum</span>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 mb-1">Ketercapaian PL</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Laporan capaian Profil Lulusan per kurikulum.</p>
            </div>
        </div>
        <div class="px-5 pb-5 pt-3 border-t border-gray-100 bg-gray-50/50 space-y-2.5">
            <label class="block text-xs font-medium text-gray-600">Pilih Kurikulum</label>
            <select id="sel-pl"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                <option value="">— Pilih Kurikulum —</option>
                @foreach ($kurikulumList as $k)
                    <option value="{{ $k->id }}">{{ $k->kode }} – {{ $k->nama_kurikulum }}</option>
                @endforeach
            </select>
            <button type="button" onclick="goReport('pl')"
                    class="w-full inline-flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                Buka Laporan
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Rekapitulasi Ketercapaian --}}
    <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all flex flex-col overflow-hidden">
        <div class="p-5 flex flex-col gap-3 flex-1">
            <div class="flex items-start justify-between gap-3">
                <div class="w-11 h-11 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-700 px-2 py-1 rounded">Lintas Kurikulum</span>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 mb-1">Rekapitulasi Ketercapaian</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Laporan ringkasan capaian OBE lintas kurikulum dan semester.</p>
            </div>
        </div>
        <div class="px-5 pb-5 pt-3 border-t border-gray-100 bg-gray-50/50">
            <a href="{{ route('kaprodi.reports.ketercapaian') }}"
               class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                Buka Laporan
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
const cplBase = '{{ url("/kaprodi/reports/cpl") }}';
const plBase  = '{{ url("/kaprodi/reports/pl") }}';

function goReport(type) {
    const id = document.getElementById('sel-' + type).value;
    if (!id) {
        alert('Pilih kurikulum terlebih dahulu.');
        return;
    }
    window.location = (type === 'cpl' ? cplBase : plBase) + '/' + id;
}
</script>
@endpush

@endsection
