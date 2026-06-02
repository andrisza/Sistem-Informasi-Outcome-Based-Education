@extends('layouts.app')

@section('title', 'Peta CPL–CPMK–MK Semester')
@section('header', 'Tabel 13 Peta Pemenuhan CPL – CPMK – MK Semester')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Peta CPL–CPMK–Semester</span>
@endsection

@section('content')

@if (session('success'))
    <div class="mb-3 flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5 text-sm text-green-800">
        <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-3 flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-2.5 text-sm text-red-800">
        <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
@endif

@if (empty($tableData))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        CPL Prodi dan CPMK harus diisi terlebih dahulu.
    </div>
@else

{{-- Legend + Info --}}
<div class="flex flex-wrap items-center gap-4 mb-3 text-xs text-gray-500">
    <span class="flex items-center gap-1.5">
        <span class="inline-flex px-1.5 py-0.5 rounded font-mono font-bold text-[10px] bg-amber-100 text-amber-800">MK01</span>
        Kode MK terpetakan di semester tersebut
    </span>
    @if (!$kurikulum->isArsip())
        <span class="flex items-center gap-1.5">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded border-2 border-dashed border-blue-300 text-blue-400 text-xs">+</span>
            Klik untuk tambah MK di semester tersebut
        </span>
        <span class="flex items-center gap-1.5">
            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-red-100 text-red-500 text-xs">×</span>
            Klik × untuk hapus relasi MK
        </span>
    @endif
    <span class="ml-auto text-gray-400">Hover kode untuk deskripsi lengkap</span>
</div>

@include('layouts._search', ['target'=>'peta-semester-wrap','placeholder'=>'Cari CPL atau CPMK...','mode'=>'dim','rowSelector'=>'tbody tr'])
<div id="peta-semester-wrap" class="rounded-xl border border-blue-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs" style="min-width: max-content; width: 100%">
            {{-- ══ HEADER ══ --}}
            <thead>
                <tr>
                    {{-- Corner --}}
                    <th rowspan="2" style="background:#1D4ED8;min-width:72px"
                        class="px-3 py-3 text-center font-bold text-white border border-blue-500 align-middle">
                        CPL
                    </th>
                    <th rowspan="2" style="background:#1D4ED8;min-width:88px"
                        class="px-3 py-3 text-center font-bold text-white border border-blue-500 align-middle">
                        CPMK
                    </th>
                    {{-- Semester label spanning all --}}
                    <th colspan="{{ count($semesterRange) }}"
                        style="background:#60A5FA;color:#1e3a8a"
                        class="px-3 py-2 text-center font-bold border border-blue-400 tracking-wide uppercase text-xs">
                        Semester
                    </th>
                </tr>
                <tr>
                    @foreach ($semesterRange as $smt)
                        <th style="background:#93C5FD;color:#1e3a8a;min-width:64px"
                            class="px-2 py-2 text-center font-bold border border-blue-300">
                            {{ $smt }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            {{-- ══ BODY ══ --}}
            <tbody>
                @foreach ($tableData as $entry)
                    @php
                        $cpl       = $entry['cpl'];
                        $cpmkRows  = $entry['cpmk_rows'];
                        $rowCount  = $entry['row_count'];
                        $cplStart  = true;
                        $rowBg     = $loop->even ? '#eff6ff' : '#fff';
                    @endphp

                    @if (empty($cpmkRows))
                        <tr style="background:{{ $rowBg }}">
                            <td class="px-3 py-2.5 text-center font-mono font-bold text-blue-800 border border-blue-100 text-xs align-middle"
                                style="background:#dbeafe">
                                {{ $cpl->kode_cpl }}
                            </td>
                            <td colspan="{{ count($semesterRange) + 1 }}"
                                class="px-4 py-2.5 text-gray-400 italic border border-blue-100 text-xs">
                                Belum ada CPMK untuk CPL ini
                            </td>
                        </tr>
                    @else
                        @foreach ($cpmkRows as $cpmkRow)
                            @php
                                $kode      = $cpmkRow['kode'];
                                $deskripsi = $cpmkRow['deskripsi'];
                                $semesters = $cpmkRow['semesters'];
                            @endphp
                            <tr style="background:{{ $rowBg }}" class="hover:bg-blue-50/40">

                                {{-- CPL column: rowspan over all CPMK rows for this CPL --}}
                                @if ($cplStart)
                                    @php $cplStart = false; @endphp
                                    <td class="px-3 py-2.5 text-center border border-blue-200 align-middle"
                                        rowspan="{{ $rowCount }}"
                                        style="background:#bfdbfe;min-width:72px">
                                        <span class="font-mono font-bold text-blue-800 text-xs cursor-help"
                                              data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                              data-tip-label="CPL">{{ $cpl->kode_cpl }}</span>
                                    </td>
                                @endif

                                {{-- CPMK column --}}
                                <td class="px-3 py-2 border border-blue-100 align-top" style="min-width:88px">
                                    <span class="font-mono font-bold text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded cursor-help block text-center"
                                          data-tooltip="{{ $kode }}: {{ $deskripsi }}"
                                          data-tip-label="CPMK">{{ $kode }}</span>
                                </td>

                                {{-- Semester cells --}}
                                @foreach ($semesterRange as $smt)
                                    @php $mks = $semesters[$smt] ?? collect(); @endphp
                                    <td class="border border-blue-100 align-top p-1" style="min-width:64px;vertical-align:top">
                                        {{-- MK chips already assigned --}}
                                        @if ($mks->isNotEmpty())
                                            <div class="space-y-0.5">
                                                @foreach ($mks as $item)
                                                    <div class="flex items-center gap-0.5 group">
                                                        <span class="font-mono font-bold text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded cursor-help flex-1 text-center"
                                                              data-tooltip="{{ $item['mk']->kode_mk }}: {{ $item['mk']->nama_mk }} ({{ $item['mk']->sks_teori + $item['mk']->sks_praktikum }} SKS)"
                                                              data-tip-label="Mata Kuliah">{{ $item['mk']->kode_mk }}</span>
                                                        @if (!$kurikulum->isArsip())
                                                            <form method="POST"
                                                                  action="{{ route('kurikulum.penilaian.peta-semester.remove', [$kurikulum, $item['cpmk_id']]) }}"
                                                                  onsubmit="return confirm('Hapus {{ $kode }} dari {{ $item['mk']->kode_mk }}?')"
                                                                  class="shrink-0">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                        class="w-4 h-4 rounded-full bg-red-100 text-red-500 hover:bg-red-200 text-xs font-bold leading-none flex items-center justify-center transition-colors"
                                                                        title="Hapus relasi">×</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Add button for empty/add more --}}
                                        @if (!$kurikulum->isArsip())
                                            <button type="button"
                                                    onclick="openAddModal('{{ $cpl->id }}','{{ $kode }}','{{ addslashes($deskripsi) }}',{{ $smt }})"
                                                    class="mt-0.5 w-full flex items-center justify-center h-5 rounded border-2 border-dashed border-blue-200 text-blue-300 hover:border-blue-400 hover:text-blue-500 transition-colors"
                                                    title="Tambah MK di Semester {{ $smt }}">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif

                    {{-- CPL(n) placeholder row --}}
                    @if ($loop->last)
                        <tr style="background:#f0f9ff">
                            <td class="px-3 py-2 text-center border border-blue-100 text-gray-400 italic text-xs"
                                style="background:#dbeafe">CPL(n)</td>
                            <td class="px-3 py-2 text-center border border-blue-100 text-gray-300 text-xs">…</td>
                            @foreach ($semesterRange as $smt)
                                <td class="border border-blue-100 text-center text-gray-300 text-xs p-1">…</td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ══ Modal Tambah MK ══ --}}
@if (!$kurikulum->isArsip())
<div id="add-modal" class="fixed inset-0 z-50 hidden" role="dialog">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40" onclick="closeAddModal()"></div>

    {{-- Panel --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-800">Tambah MK ke Peta CPMK</h3>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Info --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 mb-4 text-xs text-blue-800">
            <span class="font-semibold">CPMK:</span> <span id="modal-kode-cpmk" class="font-mono font-bold"></span>
            &nbsp;|&nbsp;
            <span class="font-semibold">Semester:</span> <span id="modal-semester"></span>
        </div>

        <form method="POST" action="{{ route('kurikulum.penilaian.peta-semester.add', $kurikulum) }}">
            @csrf
            <input type="hidden" name="id_cpl"    id="modal-id-cpl">
            <input type="hidden" name="kode_cpmk"  id="modal-kode-cpmk-hidden">
            <input type="hidden" name="deskripsi"  id="modal-deskripsi">

            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Pilih Mata Kuliah (Semester <span id="modal-smt-label"></span>)
                    <span class="text-red-500">*</span>
                </label>
                <select name="id_mk" id="modal-mk-select" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Pilih MK —</option>
                    @foreach ($mkList->groupBy('semester') as $smt => $mks)
                        <optgroup label="Semester {{ $smt }}" data-semester="{{ $smt }}">
                            @foreach ($mks as $mk)
                                <option value="{{ $mk->id }}" data-semester="{{ $mk->semester }}">
                                    {{ $mk->kode_mk }} — {{ $mk->nama_mk }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-400 mt-1">Hanya MK dari semester yang dipilih yang ditampilkan.</p>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Tambahkan
                </button>
                <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endif {{-- end if tableData not empty --}}

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
<script>
(function () {
    var modal       = document.getElementById('add-modal');
    var selMk       = document.getElementById('modal-mk-select');
    var lblKode     = document.getElementById('modal-kode-cpmk');
    var lblSmt      = document.getElementById('modal-semester');
    var lblSmtLabel = document.getElementById('modal-smt-label');
    var inpCpl      = document.getElementById('modal-id-cpl');
    var inpKode     = document.getElementById('modal-kode-cpmk-hidden');
    var inpDesk     = document.getElementById('modal-deskripsi');

    window.openAddModal = function (cplId, kode, deskripsi, semester) {
        if (!modal) return;
        inpCpl.value  = cplId;
        inpKode.value = kode;
        inpDesk.value = deskripsi;
        lblKode.textContent = kode;
        lblSmt.textContent  = semester;
        if (lblSmtLabel) lblSmtLabel.textContent = semester;

        // Filter MK selector to show only MK in selected semester
        if (selMk) {
            Array.from(selMk.options).forEach(function (opt) {
                var optSmt = opt.dataset.semester;
                if (opt.value === '') {
                    opt.hidden = false;
                } else {
                    opt.hidden = (parseInt(optSmt) !== parseInt(semester));
                }
            });
            // Also show/hide optgroups
            Array.from(selMk.querySelectorAll('optgroup')).forEach(function (grp) {
                grp.hidden = (parseInt(grp.dataset.semester) !== parseInt(semester));
            });
            selMk.value = ''; // reset selection
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeAddModal = function () {
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAddModal();
    });
})();
</script>
@endpush
