@extends('layouts.app')

@section('title', 'Pemetaan CPL – CPMK – MK')
@section('header', 'Tabel Pemetaan CPL – CPMK – MK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Peta CPL–CPMK–MK</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.overview.cpl-cpmk-mk.export', $kurikulum)])
@endsection

@section('content')

{{-- Panduan --}}
<div class="mb-4 bg-blue-50 border border-blue-100 rounded-xl px-5 py-3.5 text-xs text-blue-800 space-y-1">
    <p class="font-semibold text-sm text-blue-900">Panduan Pemetaan CPL–CPMK–MK</p>
    <p>CPL yang dibebankan pada mata kuliah diturunkan menjadi <strong>Capaian Pembelajaran Mata Kuliah (CPMK)</strong>. Satu kode CPMK dapat diajarkan di banyak MK.</p>
    <p>Kode CPMK di-generate otomatis dengan format <span class="font-mono bg-blue-100 px-1 rounded">CPMK{kode-CPL-2digit}{urutan}</span> — contoh: <span class="font-mono bg-blue-100 px-1 rounded">CPMK011</span> = CPMK pertama dari CPL01.</p>
</div>

@if (empty($tableData))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">CPL Prodi dan Mata Kuliah harus diisi terlebih dahulu.</p>
        </div>
    </div>
@else

{{-- Search --}}
@include('layouts._search', ['target'=>'cpl-cpmk-wrap','placeholder'=>'Cari CPL, MK, atau CPMK...','mode'=>'dim','rowSelector'=>'tbody tr'])

{{-- Statistik ringkas --}}
@php
    $totalCpmkKode = collect($tableData)->sum(fn($e) => $e['cpmk_groups']->count());
    $totalCpl      = count($tableData);
@endphp
<div class="flex items-center gap-4 mb-3 text-xs text-gray-500">
    <span>{{ $totalCpl }} CPL</span>
    <span class="text-gray-300">·</span>
    <span>{{ $totalCpmkKode }} Kode CPMK</span>
    <span class="text-gray-300">·</span>
    <span>{{ $mkList->count() }} Mata Kuliah</span>
</div>

<div id="cpl-cpmk-wrap" class="rounded-xl border border-gray-200 shadow-sm overflow-hidden bg-white">
<div class="overflow-x-auto">
<table class="border-collapse text-xs w-full">

    {{-- ═══ HEADER ═══ --}}
    <thead>
        <tr style="background:#F59E0B">
            <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-500 w-9">No</th>
            <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-500 w-20">CPL</th>
            <th class="px-4 py-3 text-left   text-xs font-bold text-white border border-amber-500" style="min-width:200px">Deskripsi CPL</th>
            <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-500 w-24">Kode CPMK</th>
            <th class="px-4 py-3 text-left   text-xs font-bold text-white border border-amber-500" style="min-width:220px">Deskripsi CPMK</th>
            <th class="px-4 py-3 text-left   text-xs font-bold text-white border border-amber-500" style="min-width:160px">Mata Kuliah</th>
            @if (!$kurikulum->isArsip())
            <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-500 w-28">Aksi</th>
            @endif
        </tr>
    </thead>

    {{-- ═══ BODY ═══ --}}
    <tbody>
    @php
        $no = 0;
        $catColors = [
            'Sikap'               => ['bg'=>'#EFF6FF','txt'=>'#1E40AF','border'=>'#BFDBFE','cpmkBg'=>'#F0F9FF'],
            'Keterampilan Umum'   => ['bg'=>'#F0FDF4','txt'=>'#065F46','border'=>'#A7F3D0','cpmkBg'=>'#ECFDF5'],
            'Keterampilan Khusus' => ['bg'=>'#F5F3FF','txt'=>'#4C1D95','border'=>'#DDD6FE','cpmkBg'=>'#FAF5FF'],
            'Pengetahuan'         => ['bg'=>'#FFFBEB','txt'=>'#78350F','border'=>'#FDE68A','cpmkBg'=>'#FEFCE8'],
        ];
        $isEditable = !$kurikulum->isArsip();
    @endphp

    @foreach ($tableData as $entry)
        @php
            $cpl      = $entry['cpl'];
            $groups   = $entry['cpmk_groups'];
            $rowCount = $entry['row_count'];
            $no++;
            $kat = $cpl->kategori ?? 'Lainnya';
            $cc  = $catColors[$kat] ?? ['bg'=>'#F9FAFB','txt'=>'#374151','border'=>'#E5E7EB','cpmkBg'=>'#F9FAFB'];
        @endphp

        @if ($groups->isEmpty())
            {{-- CPL tanpa CPMK --}}
            <tr>
                <td class="px-3 py-3 text-center font-bold text-gray-500 border border-gray-200 align-middle"
                    style="background:{{ $cc['bg'] }}">{{ $no }}</td>
                <td class="px-3 py-3 text-center border border-gray-200 align-middle"
                    style="background:{{ $cc['bg'] }}">
                    <span class="font-mono font-bold text-xs px-2 py-0.5 rounded border"
                          style="color:{{ $cc['txt'] }};border-color:{{ $cc['border'] }};background:{{ $cc['bg'] }}">
                        {{ $cpl->kode_cpl }}
                    </span>
                    @if ($isEditable)
                    <div class="mt-1.5">
                        <button type="button"
                                onclick="openAddCpmk('{{ $cpl->id }}','{{ addslashes($cpl->kode_cpl) }}')"
                                class="text-[9px] font-semibold text-blue-600 hover:text-blue-800 px-1.5 py-0.5 border border-blue-200 rounded hover:bg-blue-50 transition-colors flex items-center gap-0.5 mx-auto">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            CPMK
                        </button>
                    </div>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-700 border border-gray-200 leading-relaxed align-middle text-xs"
                    style="background:{{ $cc['bg'] }}">{{ $cpl->deskripsi }}</td>
                <td colspan="{{ $isEditable ? 4 : 3 }}"
                    class="px-4 py-4 text-center border border-gray-200 text-gray-400 text-xs italic">
                    Belum ada CPMK untuk CPL ini.
                    @if ($isEditable)
                    <a href="#" onclick="openAddCpmk('{{ $cpl->id }}','{{ addslashes($cpl->kode_cpl) }}');return false"
                       class="ml-1 text-blue-600 hover:underline not-italic">Tambah sekarang →</a>
                    @endif
                </td>
            </tr>
        @else
            @foreach ($groups as $gi => $group)
                <tr class="hover:bg-amber-50/30 transition-colors" style="background:#fff">

                    {{-- No + CPL: rowspan pada baris pertama saja --}}
                    @if ($gi === 0)
                        <td class="px-3 py-3 text-center font-bold text-sm border border-gray-200 align-top"
                            rowspan="{{ $rowCount }}"
                            style="background:{{ $cc['bg'] }};color:{{ $cc['txt'] }}">
                            {{ $no }}
                        </td>
                        <td class="px-3 py-3 text-center border border-gray-200 align-top"
                            rowspan="{{ $rowCount }}"
                            style="background:{{ $cc['bg'] }}">
                            <span class="font-mono font-bold text-xs px-2 py-1 rounded border block mb-1.5"
                                  style="color:{{ $cc['txt'] }};border-color:{{ $cc['border'] }};background:{{ $cc['bg'] }}">
                                {{ $cpl->kode_cpl }}
                            </span>
                            @if ($isEditable)
                            <button type="button"
                                    onclick="openAddCpmk('{{ $cpl->id }}','{{ addslashes($cpl->kode_cpl) }}')"
                                    class="text-[9px] font-semibold text-blue-600 hover:text-blue-800 px-1.5 py-0.5 border border-blue-200 rounded hover:bg-blue-50 transition-colors inline-flex items-center gap-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                CPMK
                            </button>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700 border border-gray-200 leading-relaxed align-top text-xs"
                            rowspan="{{ $rowCount }}"
                            style="background:{{ $cc['bg'] }};min-width:200px">
                            {{ $cpl->deskripsi }}
                        </td>
                    @endif

                    {{-- Kode CPMK --}}
                    <td class="px-3 py-3 text-center border border-gray-200 align-top"
                        style="background:{{ $cc['cpmkBg'] }}">
                        <span class="font-mono font-bold text-xs text-blue-700 bg-blue-100 border border-blue-200 px-2 py-0.5 rounded block">
                            {{ $group['kode_cpmk'] }}
                        </span>
                    </td>

                    {{-- Deskripsi CPMK --}}
                    <td class="px-4 py-3 text-gray-700 border border-gray-200 leading-relaxed align-top text-xs"
                        style="min-width:220px">
                        {{ $group['deskripsi'] }}
                    </td>

                    {{-- Mata Kuliah (badges, bisa banyak) --}}
                    <td class="px-3 py-3 border border-gray-200 align-top" style="min-width:160px">
                        <div class="flex flex-wrap gap-1.5">
                            @forelse ($group['mks'] as $mk)
                                <div class="group/mk relative inline-flex items-center gap-0.5">
                                    {{-- Badge MK --}}
                                    <span class="font-mono font-bold text-[10px] px-1.5 py-0.5 rounded
                                                 bg-amber-100 text-amber-800 border border-amber-200 cursor-help leading-tight">
                                        {{ $mk->kode_mk }}
                                    </span>
                                    {{-- Remove MK button --}}
                                    @if ($isEditable)
                                        @php
                                            $rec = $group['records']->firstWhere('id_mk', $mk->id);
                                        @endphp
                                        @if ($rec)
                                        <form method="POST"
                                              action="{{ route('kurikulum.overview.cpl-cpmk-mk.destroy', [$kurikulum, $rec->id]) }}"
                                              onsubmit="return confirm('Lepas {{ $mk->kode_mk }} dari {{ $group['kode_cpmk'] }}?')"
                                              class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-3.5 h-3.5 rounded-full bg-gray-200 hover:bg-red-400 text-gray-500 hover:text-white
                                                           flex items-center justify-center transition-colors text-[9px] font-bold leading-none"
                                                    title="Lepas dari CPMK">×</button>
                                        </form>
                                        @endif
                                    @endif
                                    {{-- Tooltip nama MK --}}
                                    <div class="absolute z-40 bottom-full left-0 mb-1.5 hidden group-hover/mk:block
                                                bg-slate-800 text-white text-[11px] font-medium rounded-lg px-3 py-2
                                                shadow-lg pointer-events-none whitespace-nowrap max-w-[220px] leading-snug">
                                        {{ $mk->nama_mk }}
                                        <span class="block text-slate-400 text-[10px] mt-0.5">Smt {{ $mk->semester }} · {{ $mk->sks_total }} SKS</span>
                                        <span class="absolute left-3 top-full w-0 h-0"
                                              style="border:5px solid transparent;border-top-color:#1E293B;border-bottom:0"></span>
                                    </div>
                                </div>
                            @empty
                                <span class="text-gray-300 text-xs italic">Belum ada MK</span>
                            @endforelse

                            {{-- Tambah MK ke CPMK yang sudah ada --}}
                            @if ($isEditable)
                            <button type="button"
                                    onclick="openAddMkToCpmk(
                                        '{{ $cpl->id }}',
                                        '{{ addslashes($cpl->kode_cpl) }}',
                                        '{{ addslashes($group['kode_cpmk']) }}',
                                        '{{ addslashes($group['deskripsi']) }}'
                                    )"
                                    class="inline-flex items-center justify-center w-5 h-5 rounded
                                           bg-gray-100 hover:bg-blue-100 text-gray-400 hover:text-blue-600
                                           border border-gray-200 hover:border-blue-300 transition-colors"
                                    title="Tambah MK ke CPMK ini">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>

                    {{-- Aksi --}}
                    @if ($isEditable)
                    <td class="px-3 py-3 text-center border border-gray-200 align-top">
                        <div class="flex items-center justify-center gap-1">
                            {{-- Edit CPMK (deskripsi) --}}
                            <button type="button"
                                    onclick="openEditCpmk(
                                        {{ $group['first_id'] }},
                                        '{{ addslashes($group['kode_cpmk']) }}',
                                        '{{ addslashes($group['deskripsi']) }}'
                                    )"
                                    class="text-[10px] font-medium text-gray-600 hover:text-blue-700
                                           px-2 py-0.5 border border-gray-200 rounded
                                           hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                Edit
                            </button>
                            {{-- Hapus semua record kode ini --}}
                            <form method="POST"
                                  action="{{ route('kurikulum.overview.cpl-cpmk-mk.destroy', [$kurikulum, $group['first_id']]) }}"
                                  onsubmit="return confirmHapusGroup('{{ $group['kode_cpmk'] }}', {{ $group['records']->count() }})">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-[10px] font-medium text-red-500 hover:text-red-700
                                               px-2 py-0.5 border border-red-200 rounded
                                               hover:border-red-300 hover:bg-red-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif

                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>

    {{-- FOOTER SUMMARY --}}
    <tfoot>
        <tr style="background:#FEF3C7">
            <td colspan="2"
                class="px-3 py-2.5 text-center text-xs font-bold text-amber-800 border border-amber-300">
                {{ $totalCpl }} CPL
            </td>
            <td class="px-4 py-2.5 text-xs text-amber-700 border border-amber-300">
                {{ $mkList->count() }} Mata Kuliah terdaftar
            </td>
            <td class="px-3 py-2.5 text-center text-xs font-bold text-amber-800 border border-amber-300">
                {{ $totalCpmkKode }} CPMK
            </td>
            <td colspan="{{ $isEditable ? 3 : 2 }}" class="px-4 py-2.5 text-xs text-amber-700 border border-amber-300">
                Satu kode CPMK dapat diajarkan di banyak MK — setiap MK membentuk satu record CPMK tersendiri.
            </td>
        </tr>
    </tfoot>

</table>
</div>
</div>

@endif {{-- end empty check --}}


{{-- ══════════════════════════════════════════════════════════════
     MODAL 1: Tambah CPMK Baru
═══════════════════════════════════════════════════════════════════ --}}
@if (!$kurikulum->isArsip() && !empty($tableData))
<div id="modal-tambah-cpmk"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm"
     onclick="if(event.target===this) closeModal('modal-tambah-cpmk')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-amber-50">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Tambah CPMK Baru</h3>
                <p id="modal-cpl-label" class="text-[11px] text-gray-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeModal('modal-tambah-cpmk')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('kurikulum.overview.cpl-cpmk-mk.store', $kurikulum) }}" class="px-6 py-5 space-y-4">
            @csrf
            <input type="hidden" name="id_cpl"             id="modal-id-cpl">
            <input type="hidden" name="existing_kode_cpmk" value="">

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="id_mk" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="">— Pilih Mata Kuliah —</option>
                    @foreach ($mkList as $mk)
                        <option value="{{ $mk->id }}">Smt {{ $mk->semester }} · {{ $mk->kode_mk }} — {{ $mk->nama_mk }} ({{ $mk->sks_total }} SKS)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Deskripsi CPMK <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" required rows="3"
                          placeholder="Rumusan kemampuan yang diturunkan dari CPL (kata kerja tindakan sesuai Taksonomi Bloom)"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Tambah CPMK
                </button>
                <button type="button" onclick="closeModal('modal-tambah-cpmk')"
                        class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 2: Tambah MK ke CPMK yang sudah ada
═══════════════════════════════════════════════════════════════════ --}}
<div id="modal-tambah-mk"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm"
     onclick="if(event.target===this) closeModal('modal-tambah-mk')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-blue-50">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Tambah MK ke CPMK</h3>
                <p id="modal-mk-label" class="text-[11px] text-gray-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeModal('modal-tambah-mk')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('kurikulum.overview.cpl-cpmk-mk.store', $kurikulum) }}" class="px-6 py-5 space-y-4">
            @csrf
            <input type="hidden" name="id_cpl"             id="modal-mk-id-cpl">
            <input type="hidden" name="existing_kode_cpmk" id="modal-mk-kode-cpmk">
            {{-- Deskripsi diisi JS dari record existing --}}
            <input type="hidden" name="deskripsi"           id="modal-mk-deskripsi">

            <div class="bg-blue-50 rounded-lg px-3 py-2.5 text-xs text-blue-800 border border-blue-100">
                <p class="font-semibold" id="modal-mk-kode-label">CPMK —</p>
                <p class="text-blue-700 mt-0.5 text-[11px]" id="modal-mk-deskripsi-label"></p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="id_mk" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">— Pilih Mata Kuliah —</option>
                    @foreach ($mkList as $mk)
                        <option value="{{ $mk->id }}">Smt {{ $mk->semester }} · {{ $mk->kode_mk }} — {{ $mk->nama_mk }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-400 mt-1">MK yang dipilih akan dikaitkan dengan CPMK di atas.</p>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Kaitkan MK
                </button>
                <button type="button" onclick="closeModal('modal-tambah-mk')"
                        class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL 3: Edit CPMK (deskripsi saja)
═══════════════════════════════════════════════════════════════════ --}}
<div id="modal-edit-cpmk"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm"
     onclick="if(event.target===this) closeModal('modal-edit-cpmk')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Edit CPMK</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Perubahan berlaku untuk semua MK yang mengajarkan CPMK ini.</p>
            </div>
            <button type="button" onclick="closeModal('modal-edit-cpmk')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="form-edit-cpmk" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            @method('PATCH')
            {{-- Kode CPMK hidden — wajib dikirim ke controller tapi tidak bisa diubah user --}}
            <input type="hidden" name="kode_cpmk" id="edit-kode-cpmk">

            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs text-gray-600">
                Kode CPMK: <span id="edit-kode-label" class="font-mono font-bold text-blue-700"></span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Deskripsi CPMK <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" id="edit-deskripsi" required rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 resize-none"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="closeModal('modal-edit-cpmk')"
                        class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
<script>
// ── Buka/tutup modal ──────────────────────────────────────────────────────────
function openModal(id) {
    const m = document.getElementById(id);
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden');
    m.classList.remove('flex');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['modal-tambah-cpmk','modal-tambah-mk','modal-edit-cpmk'].forEach(closeModal);
    }
});

// ── Modal 1: Tambah CPMK Baru ─────────────────────────────────────────────────
function openAddCpmk(cplId, cplKode) {
    document.getElementById('modal-id-cpl').value = cplId;
    document.getElementById('modal-cpl-label').textContent = 'CPL: ' + cplKode;
    openModal('modal-tambah-cpmk');
}

// ── Modal 2: Tambah MK ke CPMK yang sudah ada ─────────────────────────────────
function openAddMkToCpmk(cplId, cplKode, kodeCpmk, deskripsi) {
    document.getElementById('modal-mk-id-cpl').value              = cplId;
    document.getElementById('modal-mk-kode-cpmk').value           = kodeCpmk;
    document.getElementById('modal-mk-deskripsi').value           = deskripsi;
    document.getElementById('modal-mk-label').textContent         = 'CPL: ' + cplKode;
    document.getElementById('modal-mk-kode-label').textContent    = kodeCpmk;
    document.getElementById('modal-mk-deskripsi-label').textContent = deskripsi;
    const sel = document.querySelector('#modal-tambah-mk select[name="id_mk"]');
    if (sel) sel.value = '';
    openModal('modal-tambah-mk');
}

// ── Modal 3: Edit CPMK ────────────────────────────────────────────────────────
function openEditCpmk(cpmkId, kode, deskripsi) {
    const baseUrl = '{{ rtrim(route("kurikulum.overview.cpl-cpmk-mk", $kurikulum), "/") }}/';
    document.getElementById('form-edit-cpmk').action  = baseUrl + cpmkId;
    document.getElementById('edit-kode-cpmk').value   = kode;
    document.getElementById('edit-kode-label').textContent = kode;
    document.getElementById('edit-deskripsi').value   = deskripsi;
    openModal('modal-edit-cpmk');
}

// ── Konfirmasi hapus group ────────────────────────────────────────────────────
function confirmHapusGroup(kode, count) {
    if (count > 1) {
        return confirm('Hapus ' + kode + '?\n\nPeringatan: CPMK ini dikaitkan dengan ' + count + ' MK. Hapus akan melepas SATU record (satu MK). MK lain tetap terkait.\n\nLanjutkan?');
    }
    return confirm('Hapus CPMK ' + kode + '?');
}

// ── Auto-buka modal jika ada error validasi ───────────────────────────────────
@if ($errors->any())
    openAddCpmk(
        '{{ old('id_cpl') }}',
        '{{ old('id_cpl') ? ($cplList->find(old('id_cpl'))?->kode_cpl ?? '') : '' }}'
    );
@endif
</script>
@endpush
