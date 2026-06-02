@extends('layouts.app')

@section('title', 'Ringkasan Kurikulum')
@section('header', 'Ringkasan Kurikulum')

@section('content')

{{-- Search --}}
<div class="mb-4">
    <input type="text" id="search-input" placeholder="Cari kode, nama MK, CPL, PL..."
           class="w-full max-w-lg border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
           oninput="filterSearch(this.value)">
</div>

@forelse ($kurikulumList as $kur)
<div class="mb-8 searchable-block" data-search="{{ strtolower($kur->kode . ' ' . $kur->nama_kurikulum . ' ' . $kur->program_studi) }}">

    {{-- Header kurikulum --}}
    <div class="flex items-center gap-3 mb-3">
        <div class="flex-1">
            <h2 class="text-base font-bold text-gray-900">{{ $kur->nama_kurikulum }}</h2>
            <p class="text-xs text-gray-500">{{ $kur->program_studi }} — {{ $kur->jenjang }} &nbsp;|&nbsp; Kode: <span class="font-mono">{{ $kur->kode }}</span></p>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $kur->statusColor() }}">
            {{ ucfirst($kur->status) }}
        </span>
    </div>

    {{-- PL --}}
    @if ($kur->pl->isNotEmpty())
    <details class="mb-3 bg-amber-50 border border-amber-200 rounded-xl overflow-hidden">
        <summary class="px-4 py-2.5 text-xs font-semibold text-amber-800 cursor-pointer select-none">
            Profil Lulusan ({{ $kur->pl->count() }})
        </summary>
        <div class="px-4 pb-3 pt-1 space-y-1.5">
            @foreach ($kur->pl->sortBy('urutan') as $pl)
            <div class="flex items-start gap-2">
                <span class="font-mono font-bold text-xs px-1.5 py-0.5 rounded bg-amber-200 text-amber-900 shrink-0 searchable-item"
                      data-search="{{ strtolower($pl->kode_pl . ' ' . $pl->deskripsi) }}"
                      data-tooltip="{{ $pl->kode_pl }}: {{ $pl->deskripsi }}" data-tip-label="Profil Lulusan">
                    {{ $pl->kode_pl }}
                </span>
                <span class="text-xs text-gray-700">{{ $pl->deskripsi }}</span>
            </div>
            @endforeach
        </div>
    </details>
    @endif

    {{-- CPL --}}
    @if ($kur->cplProdi->isNotEmpty())
    <details class="mb-3 bg-blue-50 border border-blue-200 rounded-xl overflow-hidden">
        <summary class="px-4 py-2.5 text-xs font-semibold text-blue-800 cursor-pointer select-none">
            CPL Prodi ({{ $kur->cplProdi->count() }})
        </summary>
        <div class="px-4 pb-3 pt-1 space-y-1.5">
            @foreach ($kur->cplProdi->sortBy('urutan') as $cpl)
            <div class="flex items-start gap-2">
                <span class="font-mono font-bold text-xs px-1.5 py-0.5 rounded bg-blue-200 text-blue-900 shrink-0 searchable-item"
                      data-search="{{ strtolower($cpl->kode_cpl . ' ' . $cpl->deskripsi) }}"
                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}" data-tip-label="CPL Prodi">
                    {{ $cpl->kode_cpl }}
                </span>
                <span class="text-xs text-gray-700">{{ $cpl->deskripsi }}</span>
            </div>
            @endforeach
        </div>
    </details>
    @endif

    {{-- BK --}}
    @if ($kur->bahanKajian->isNotEmpty())
    <details class="mb-3 bg-emerald-50 border border-emerald-200 rounded-xl overflow-hidden">
        <summary class="px-4 py-2.5 text-xs font-semibold text-emerald-800 cursor-pointer select-none">
            Bahan Kajian ({{ $kur->bahanKajian->count() }})
        </summary>
        <div class="px-4 pb-3 pt-1 flex flex-wrap gap-2">
            @foreach ($kur->bahanKajian->sortBy('urutan') as $bk)
            <span class="font-mono font-bold text-xs px-2 py-0.5 rounded bg-emerald-200 text-emerald-900 cursor-default searchable-item"
                  data-search="{{ strtolower($bk->kode_bk . ' ' . $bk->nama_bk) }}"
                  data-tooltip="{{ $bk->kode_bk }}: {{ $bk->nama_bk }}" data-tip-label="Bahan Kajian">
                {{ $bk->kode_bk }}
            </span>
            @endforeach
        </div>
    </details>
    @endif

    {{-- MK per semester --}}
    <details class="mb-3 bg-white border border-gray-200 rounded-xl overflow-hidden" open>
        <summary class="px-4 py-2.5 text-xs font-semibold text-gray-700 cursor-pointer select-none">
            Mata Kuliah ({{ $kur->mataKuliah->count() }} MK)
        </summary>
        <div class="px-4 pb-4 pt-2">
            @foreach ($mkBySemester[$kur->id] as $smt => $mks)
            <div class="mb-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Semester {{ $smt }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach ($mks as $mk)
                    <div class="border border-gray-100 rounded-lg px-3 py-2 bg-gray-50 searchable-item"
                         data-search="{{ strtolower($mk->kode_mk . ' ' . $mk->nama_mk) }}">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="font-mono font-bold text-xs text-gray-800"
                                  data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }}" data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                            <span class="text-[10px] text-gray-500 font-medium">{{ ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0) }} SKS</span>
                        </div>
                        <p class="text-xs text-gray-600">{{ $mk->nama_mk }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </details>

</div>
@empty
<div class="flex flex-col items-center justify-center py-20 text-gray-400">
    <p class="text-sm">Tidak ada kurikulum aktif yang dapat ditampilkan.</p>
</div>
@endforelse

@endsection

@push('scripts')
<script>
function filterSearch(query) {
    var q = query.toLowerCase().trim();

    document.querySelectorAll('.searchable-block').forEach(function (block) {
        if (!q) { block.style.display = ''; return; }

        var blockText = block.dataset.search || '';
        var itemTexts = Array.from(block.querySelectorAll('.searchable-item'))
            .map(function (el) { return el.dataset.search || ''; })
            .join(' ');

        var match = blockText.includes(q) || itemTexts.includes(q);
        block.style.display = match ? '' : 'none';
    });
}
</script>
@endpush
