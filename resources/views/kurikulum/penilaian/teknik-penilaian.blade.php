@extends('layouts.app')
@section('title', 'Teknik Penilaian CPMK')
@section('header', 'Teknik Penilaian CPMK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Teknik Penilaian</span>
@endsection

@section('content')

@if (session('success'))
    <div class="mb-4 flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-800">
        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if ($byCpl->isEmpty())
    <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-5 text-sm text-blue-800">
        Belum ada data CPMK. Silakan isi Mata Kuliah dan CPMK terlebih dahulu.
    </div>
@else

@if (!$kurikulum->isArsip())
<form method="POST" action="{{ route('kurikulum.penilaian.teknik.save', $kurikulum) }}" id="teknik-form">
    @csrf

    {{-- ══ Hidden inputs CPMK IDs — di luar tabel agar tidak merusak struktur kolom ══ --}}
    <div style="display:none" aria-hidden="true">
        @foreach ($cplList as $cpl)
            @php $cpmks = $byCpl->get($cpl->id, collect()); @endphp
            @foreach ($cpmks as $cpmk)
                <input type="hidden" name="cpmk_ids[]" value="{{ $cpmk->id }}">
            @endforeach
        @endforeach
    </div>
@endif

<div class="rounded-xl border border-blue-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs">
            <thead>
                <tr style="background:#3B82F6">
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-16 sticky left-0 z-20">CPL</th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">MK</th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-28">CPMK</th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-24">Partisipasi<br><span class="font-normal text-blue-100">(Quiz)</span></th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-28">Observasi<br><span class="font-normal text-blue-100">(Praktek/Tugas)</span></th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-28">Unjuk Kerja<br><span class="font-normal text-blue-100">(Presentasi)</span></th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Tes Tulis<br><span class="font-normal text-blue-100">(UTS)</span></th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Tes Tulis<br><span class="font-normal text-blue-100">(UAS)</span></th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-28">Tes Lisan<br><span class="font-normal text-blue-100">(Tugas Kelompok)</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cplList as $cpl)
                    @php $cpmks = $byCpl->get($cpl->id, collect()); @endphp
                    @if ($cpmks->isEmpty()) @continue @endif

                    @foreach ($cpmks as $ci => $cpmk)
                        @php
                            $p     = $cpmk->penilaian;
                            $rowBg = ($ci % 2 === 0) ? '#eff6ff' : '#fff';
                        @endphp
                        <tr style="background:{{ $rowBg }}" class="hover:bg-blue-50/40">

                            {{-- CPL (rowspan) --}}
                            @if ($ci === 0)
                                <td class="px-3 py-2.5 text-center border border-blue-100 align-middle sticky left-0 z-10"
                                    rowspan="{{ $cpmks->count() }}"
                                    style="background:#dbeafe">
                                    <span class="font-mono font-bold text-blue-800 text-xs cursor-help"
                                          data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                          data-tip-label="CPL">{{ $cpl->kode_cpl }}</span>
                                </td>
                            @endif

                            {{-- MK --}}
                            <td class="px-3 py-2.5 text-center border border-blue-100 align-middle">
                                <span class="font-mono font-bold text-[10px] text-blue-700 cursor-help"
                                      data-tooltip="{{ $cpmk->mataKuliah->kode_mk ?? '—' }}: {{ $cpmk->mataKuliah->nama_mk ?? '' }} (Smt {{ $cpmk->mataKuliah->semester ?? '?' }})"
                                      data-tip-label="Mata Kuliah">
                                    {{ $cpmk->mataKuliah->kode_mk ?? '—' }}
                                </span>
                            </td>

                            {{-- CPMK --}}
                            <td class="px-3 py-2.5 text-center border border-blue-100 align-middle">
                                <span class="font-mono font-bold text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded cursor-help"
                                      data-tooltip="{{ $cpmk->kode_cpmk }}: {{ $cpmk->deskripsi }}"
                                      data-tip-label="CPMK">{{ $cpmk->kode_cpmk }}</span>
                            </td>

                            {{-- 6 Checkbox columns (9 total header columns — no extra hidden TD) --}}
                            @foreach (['teknik_quiz','teknik_observasi','teknik_unjuk_kerja','teknik_uts','teknik_uas','teknik_tes_lisan'] as $field)
                                <td class="border border-blue-100 text-center align-middle" style="height:40px;min-width:72px">
                                    @if (!$kurikulum->isArsip())
                                        <input type="checkbox"
                                               name="penilaian[{{ $cpmk->id }}][{{ $field }}]"
                                               value="1"
                                               {{ ($p && $p->$field) ? 'checked' : '' }}
                                               class="w-4 h-4 accent-blue-600 cursor-pointer">
                                    @else
                                        @if ($p && $p->$field)
                                            <span class="text-blue-600 font-bold">✓</span>
                                        @else
                                            <span class="text-gray-200 text-xs">—</span>
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if (!$kurikulum->isArsip())
<div class="mt-4 flex items-center gap-3">
    <button type="submit" form="teknik-form"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition-colors">
        Simpan Teknik Penilaian
    </button>
    <span class="text-xs text-gray-400">
        Setelah simpan, hasil akan langsung tampil di
        <a href="{{ route('kurikulum.penilaian.tahap', $kurikulum) }}" class="text-blue-500 hover:underline">Tahap &amp; Mekanisme →</a>
    </span>
</div>
</form>
@endif

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
