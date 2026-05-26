@extends('layouts.app')

@section('title', 'Rumusan Akhir Penilaian')
@section('header', 'Rumusan Akhir MK & CPL')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Rumusan Akhir</span>
@endsection

@section('content')

{{-- Tabs --}}
<div class="flex gap-1 border-b border-gray-200 mb-4">
    <button class="tab-btn active px-4 py-2 text-xs font-semibold border-b-2 border-blue-600 text-blue-700" data-tab="mk">
        Rumusan Akhir Mata Kuliah
    </button>
    <button class="tab-btn px-4 py-2 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:text-blue-700" data-tab="cpl">
        Rumusan Akhir CPL
    </button>
</div>

{{-- ── TAB 1: Rumusan Akhir MK ────────────────────────────────────── --}}
<div id="tab-mk" class="tab-content space-y-3">
    <div class="text-xs text-gray-500 mb-2">
        Rumus nilai akhir per Mata Kuliah = Σ (Bobot Komponen × Skor). Komponen asesmen dibuat dosen pengampu di halaman Input Nilai.
    </div>
    @foreach ($mkList as $mk)
        @php
            $komp = $komponen->get($mk->id, collect());
            $totalBobot = $komp->sum('bobot_persen');
        @endphp
        <div class="bg-white border {{ $komp->isEmpty() ? 'border-amber-200' : ($totalBobot == 100 ? 'border-emerald-200' : 'border-gray-200') }} rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-2.5 bg-gray-50">
                <span class="text-[10px] font-bold uppercase text-gray-500">S{{ $mk->semester }}</span>
                <span class="font-mono font-bold text-blue-700 text-xs bg-white px-2 py-0.5 rounded cursor-help"
                      data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }}"
                      data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                <span class="text-sm font-medium text-gray-800 flex-1">{{ $mk->nama_mk }}</span>
                @if ($komp->isNotEmpty())
                    <span class="text-xs font-semibold {{ $totalBobot == 100 ? 'text-emerald-700' : 'text-amber-700' }}">
                        Σ Bobot = {{ number_format($totalBobot, 1) }}%
                    </span>
                @endif
            </div>
            @if ($komp->isNotEmpty())
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 border-y border-gray-100">
                            <th class="px-3 py-1.5 text-left font-semibold">Komponen</th>
                            <th class="px-3 py-1.5 text-left font-semibold w-32">Jenis</th>
                            <th class="px-3 py-1.5 text-left font-semibold">Terkait Sub-CPMK</th>
                            <th class="px-3 py-1.5 text-right font-semibold w-20">Bobot</th>
                            <th class="px-3 py-1.5 text-right font-semibold w-20">Skor Maks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($komp as $k)
                            <tr class="border-b border-gray-100 last:border-0">
                                <td class="px-3 py-1.5 font-medium text-gray-700">{{ $k->nama_komponen }}</td>
                                <td class="px-3 py-1.5">
                                    <span class="inline-block px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px]">{{ $k->jenis_komponen }}</span>
                                </td>
                                <td class="px-3 py-1.5 text-gray-600">
                                    @if ($k->subCpmk)
                                        <span class="font-mono text-emerald-700">{{ $k->subCpmk->kode_sub_cpmk }}</span>
                                        <span class="text-gray-400">— {{ \Str::limit($k->subCpmk->deskripsi, 50) }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-1.5 text-right font-semibold text-amber-700">{{ number_format((float) $k->bobot_persen, 1) }}%</td>
                                <td class="px-3 py-1.5 text-right text-gray-600">{{ number_format((float) $k->skor_maks, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-4 py-3 text-xs text-amber-700">Belum ada komponen asesmen (dosen pengampu yang menentukan).</div>
            @endif
        </div>
    @endforeach
</div>

{{-- ── TAB 2: Rumusan Akhir CPL ───────────────────────────────────── --}}
<div id="tab-cpl" class="tab-content hidden space-y-3">
    <div class="text-xs text-gray-500 mb-2">
        Capaian setiap CPL dihitung dari rata-rata nilai CPMK yang terkait (CPMK pada MK yang memetakan CPL tersebut).
    </div>
    @foreach ($cplList as $cpl)
        @php $cpmks = $cpmkPerCpl->get($cpl->id, collect()); @endphp
        <div class="bg-white border {{ $cpmks->isEmpty() ? 'border-amber-200' : 'border-gray-200' }} rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-2.5 {{ $cpmks->isEmpty() ? 'bg-amber-50' : 'bg-violet-50' }}">
                <span class="font-mono font-bold text-violet-800 text-xs bg-white px-2 py-0.5 rounded cursor-help"
                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                      data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                <span class="text-xs text-gray-700 flex-1">{{ \Str::limit($cpl->deskripsi, 120) }}</span>
                <span class="text-xs text-gray-500">{{ $cpmks->count() }} CPMK</span>
            </div>
            @if ($cpmks->isNotEmpty())
                <div class="px-4 py-2 text-xs text-gray-600 bg-gray-50/50 border-y border-gray-100 font-mono">
                    Capaian({{ $cpl->kode_cpl }}) = AVG( {{ $cpmks->pluck('kode_cpmk')->take(3)->implode(', ') }}{{ $cpmks->count() > 3 ? ', …' : '' }} )
                </div>
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="px-3 py-1.5 text-left font-semibold w-24">CPMK</th>
                            <th class="px-3 py-1.5 text-left font-semibold">Deskripsi</th>
                            <th class="px-3 py-1.5 text-left font-semibold w-48">Mata Kuliah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cpmks as $c)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-3 py-1.5"><span class="font-mono text-violet-700 font-semibold">{{ $c->kode_cpmk }}</span></td>
                                <td class="px-3 py-1.5 text-gray-700">{{ \Str::limit($c->deskripsi, 100) }}</td>
                                <td class="px-3 py-1.5">
                                    @if ($c->mataKuliah)
                                        <span class="font-mono text-blue-600">{{ $c->mataKuliah->kode_mk }}</span>
                                        <span class="text-gray-500 text-[10px]">— {{ \Str::limit($c->mataKuliah->nama_mk, 30) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-4 py-3 text-xs text-amber-700">
                    Belum ada CPMK yang terkait. Buat CPMK lewat halaman <a href="{{ route('kurikulum.overview.cpmk', $kurikulum) }}" class="underline font-medium">Peta CPMK</a>.
                </div>
            @endif
        </div>
    @endforeach
</div>

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
<script>
(function () {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active', 'border-blue-600', 'text-blue-700');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            btn.classList.add('active', 'border-blue-600', 'text-blue-700');
            btn.classList.remove('border-transparent', 'text-gray-500');
            const tab = btn.dataset.tab;
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');
        });
    });
})();
</script>
@endpush
