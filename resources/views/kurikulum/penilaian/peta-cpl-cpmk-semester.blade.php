@extends('layouts.app')

@section('title', 'Peta CPL–CPMK–MK')
@section('header', 'Tabel Pemetaan CPL – CPMK – MK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Peta CPL–CPMK–MK</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.penilaian.peta-semester.export', $kurikulum)])
@endsection

@section('content')

@if (empty($tableData))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        CPL Prodi dan CPMK harus diisi terlebih dahulu.
    </div>
@else

{{-- ══ Bangun map terbalik: mk_id → cpl_id → [{kode, cpmk_id}] ══ --}}
@php
    // Gunakan $cplList yang sudah dikirim controller (urutan & data lengkap)
    $cplObjects = $cplList;

    // Invert: mk_id => cpl_id => [{kode, cpmk_id}]
    $cpmkMap = [];
    foreach ($tableData as $entry) {
        $cplId = $entry['cpl']->id;
        foreach ($entry['cpmk_rows'] as $cpmkRow) {
            foreach ($cpmkRow['semesters'] as $smt => $items) {
                foreach ($items as $item) {
                    $mkId = $item['mk']->id;
                    $cpmkMap[$mkId][$cplId][] = [
                        'kode'    => $cpmkRow['kode'],
                        'cpmk_id' => $item['cpmk_id'],
                    ];
                }
            }
        }
    }

    // Per CPL: existing CPMK codes (for modal dropdown)
    $existingByCpl = [];
    foreach ($tableData as $entry) {
        $cplId = $entry['cpl']->id;
        $existing = [];
        foreach ($entry['cpmk_rows'] as $row) {
            $existing[] = ['kode' => $row['kode'], 'deskripsi' => $row['deskripsi']];
        }
        $existingByCpl[$cplId] = $existing;
    }

    $isEditable = !$kurikulum->isArsip();
@endphp

{{-- Legend --}}
<div class="flex flex-wrap items-center gap-4 mb-3 text-xs text-gray-500">
    <span class="flex items-center gap-1.5">
        <span class="inline-flex px-1.5 py-0.5 rounded font-mono font-bold text-[10px] bg-amber-100 text-amber-800 border border-amber-300">CPMK011</span>
        Kode CPMK terpetakan pada MK × CPL tersebut
    </span>
    @if ($isEditable)
        <span class="flex items-center gap-1.5">
            <span class="inline-flex items-center justify-center w-4 h-4 rounded border border-dashed border-blue-300 text-blue-400 text-xs font-bold">+</span>
            Tambah CPMK pada sel
        </span>
        <span class="flex items-center gap-1.5">
            <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-red-100 text-red-500 text-xs font-bold">×</span>
            Hapus relasi
        </span>
    @endif
    <span class="ml-auto text-gray-400">Hover kode CPL atau CPMK untuk deskripsi lengkap</span>
</div>

@include('layouts._search', ['target'=>'peta-wrap','placeholder'=>'Cari MK atau kode CPMK...','mode'=>'dim','rowSelector'=>'tbody tr'])

<div id="peta-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden bg-white">
<div class="overflow-x-auto">
<table class="border-collapse text-xs" style="min-width:max-content">

    {{-- ══ HEADER baris 1 ══ --}}
    <thead>
        <tr>
            {{-- Corner: MK + Nama MK (2 cols, rowspan 2) --}}
            <th rowspan="2"
                style="background:#F59E0B;min-width:64px"
                class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-500 align-middle">
                MK
            </th>
            <th rowspan="2"
                style="background:#F59E0B;min-width:180px"
                class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-500 align-middle">
                Nama Mata Kuliah
            </th>
            {{-- "Capaian Pembelajaran Lulusan (CPL)" spanning all CPL columns --}}
            <th colspan="{{ $cplObjects->count() }}"
                style="background:#FCD34D;color:#92400E"
                class="px-3 py-2.5 text-center text-xs font-bold border border-amber-400 tracking-wide">
                Capaian Pembelajaran Lulusan (CPL)
            </th>
        </tr>

        {{-- ══ HEADER baris 2: kode CPL masing-masing ══ --}}
        <tr>
            @foreach ($cplObjects as $cpl)
                <th style="background:#F59E0B;min-width:80px"
                    class="px-2 py-2 text-center text-[10px] font-bold text-white border border-amber-500 cursor-help"
                    data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                    data-tip-label="CPL Prodi">
                    {{ $cpl->kode_cpl }}
                </th>
            @endforeach
        </tr>
    </thead>

    {{-- ══ BODY: satu baris per MK ══ --}}
    <tbody>
    @php $prevSmt = null; $rowIdx = 0; @endphp
    @foreach ($mkList as $mk)
        @php
            $rowBg  = ($rowIdx % 2 === 0) ? '#fff' : '#FFFBEB';
            $rowIdx++;
        @endphp

        {{-- Garis pemisah antar semester --}}
        @if ($prevSmt !== null && $mk->semester !== $prevSmt)
            <tr>
                <td colspan="{{ $cplObjects->count() + 2 }}"
                    style="background:#FCD34D;height:2px;padding:0;border:0"></td>
            </tr>
        @endif
        @php $prevSmt = $mk->semester; @endphp

        <tr style="background:{{ $rowBg }}" class="hover:bg-amber-50/30 transition-colors">

            {{-- Kode MK --}}
            <td class="px-2 py-2 text-center border border-amber-100 align-middle" style="min-width:64px">
                <span class="font-mono font-bold text-[11px] text-blue-800 block cursor-help"
                      data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} · Smt {{ $mk->semester }} · {{ $mk->sks_total }} SKS"
                      data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                <span class="text-[9px] text-gray-400 block mt-0.5">Smt {{ $mk->semester }}</span>
            </td>

            {{-- Nama MK --}}
            <td class="px-3 py-2 border border-amber-100 align-middle" style="min-width:180px">
                <span class="text-gray-800 font-medium text-[11px] leading-snug">{{ $mk->nama_mk }}</span>
                <span class="block text-gray-400 text-[10px] mt-0.5">{{ $mk->sks_total }} SKS</span>
            </td>

            {{-- Satu sel per CPL --}}
            @foreach ($cplObjects as $cpl)
                @php
                    $entries = $cpmkMap[$mk->id][$cpl->id] ?? [];
                    // Deduplicate by kode (keep first cpmk_id per kode)
                    $seen = [];
                    $uniqueEntries = [];
                    foreach ($entries as $e) {
                        if (!in_array($e['kode'], $seen)) {
                            $seen[] = $e['kode'];
                            $uniqueEntries[] = $e;
                        }
                    }
                @endphp
                <td class="border border-amber-100 align-top p-1" style="min-width:80px;vertical-align:top">
                    {{-- CPMK badges yang sudah ada --}}
                    @if (count($uniqueEntries))
                        <div class="space-y-0.5 mb-0.5">
                            @foreach ($uniqueEntries as $entry)
                                <div class="flex items-center gap-0.5 group/chip">
                                    <span class="font-mono font-bold text-[10px] bg-amber-100 text-amber-800
                                                 border border-amber-300 px-1 py-0.5 rounded
                                                 cursor-help flex-1 text-center leading-tight"
                                          data-tooltip="{{ $entry['kode'] }}"
                                          data-tip-label="CPMK">{{ $entry['kode'] }}</span>
                                    @if ($isEditable)
                                        <form method="POST"
                                              action="{{ route('kurikulum.penilaian.peta-semester.remove', [$kurikulum, $entry['cpmk_id']]) }}"
                                              onsubmit="return confirm('Hapus {{ $entry['kode'] }} dari {{ $mk->kode_mk }}?')"
                                              class="shrink-0">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-3.5 h-3.5 rounded-full bg-red-100 text-red-500 hover:bg-red-200
                                                           text-[10px] font-bold leading-none flex items-center justify-center
                                                           transition-colors"
                                                    title="Hapus relasi">×</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tombol + Tambah CPMK (hanya jika editable) --}}
                    @if ($isEditable)
                        <button type="button"
                                onclick="openAdd('{{ $cpl->id }}','{{ addslashes($cpl->kode_cpl) }}','{{ $mk->id }}','{{ addslashes($mk->kode_mk) }}')"
                                class="w-full flex items-center justify-center h-5 rounded
                                       border border-dashed border-blue-200 text-blue-300
                                       hover:border-blue-400 hover:text-blue-500 hover:bg-blue-50
                                       transition-colors"
                                title="Tambah CPMK di {{ $cpl->kode_cpl }} untuk {{ $mk->kode_mk }}">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    @endif
                </td>
            @endforeach

        </tr>
    @endforeach
    </tbody>

    {{-- FOOTER: jumlah MK per CPL --}}
    <tfoot>
        <tr style="background:#FEF3C7">
            <td colspan="2"
                class="px-3 py-2 text-center text-xs font-bold text-amber-800 border border-amber-300">
                {{ $mkList->count() }} MK
            </td>
            @foreach ($cplObjects as $cpl)
                @php
                    $cnt = collect($mkList)->filter(fn($mk) => !empty($cpmkMap[$mk->id][$cpl->id]))->count();
                @endphp
                <td class="px-1 py-2 text-center border border-amber-300">
                    @if ($cnt > 0)
                        <span class="block font-bold text-[11px] text-amber-800">{{ $cnt }}</span>
                        <span class="block text-[9px] text-amber-600">MK</span>
                    @else
                        <span class="text-gray-300 text-[10px]">—</span>
                    @endif
                </td>
            @endforeach
        </tr>
    </tfoot>

</table>
</div>
</div>

<p class="mt-2 text-[11px] text-gray-400">
    Garis kuning horizontal memisahkan antar semester. Kolom = CPL, baris = Mata Kuliah, sel = kode CPMK yang menghubungkan keduanya.
</p>


{{-- ══════════════════════════════════════════════════════
     MODAL: Tambah CPMK ke sel (MK × CPL)
══════════════════════════════════════════════════════════ --}}
@if ($isEditable)
<div id="add-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm"
     onclick="if(event.target===this)closeAdd()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        {{-- Header modal --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-amber-50">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Tambah CPMK</h3>
                <p id="modal-subtitle" class="text-[11px] text-gray-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeAdd()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('kurikulum.penilaian.peta-semester.add', $kurikulum) }}"
              class="px-6 py-5 space-y-4">
            @csrf
            <input type="hidden" name="id_cpl"  id="modal-id-cpl">
            <input type="hidden" name="id_mk"    id="modal-id-mk">
            <input type="hidden" name="deskripsi" id="modal-deskripsi">

            {{-- Info sel --}}
            <div class="bg-amber-50 rounded-lg border border-amber-200 px-3 py-2.5 text-xs text-amber-900 flex items-center gap-3">
                <div>
                    <span class="text-amber-600">MK:</span>
                    <span id="modal-mk-label" class="font-mono font-bold ml-1"></span>
                </div>
                <div class="text-amber-300">|</div>
                <div>
                    <span class="text-amber-600">CPL:</span>
                    <span id="modal-cpl-label" class="font-mono font-bold ml-1"></span>
                </div>
            </div>

            {{-- Pilih CPMK: existing atau baru --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                    Kode CPMK <span class="text-red-500">*</span>
                </label>

                {{-- Dropdown: CPMK yang sudah ada untuk CPL ini --}}
                <select id="modal-cpmk-select"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 mb-2"
                        onchange="handleCpmkSelect(this.value)">
                    <option value="">— Pilih CPMK yang sudah ada —</option>
                    {{-- Di-isi JS saat modal dibuka --}}
                </select>

                {{-- Separator --}}
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-[10px] text-gray-400">atau</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                {{-- Input kode baru --}}
                <input type="text" name="kode_cpmk" id="modal-kode-input"
                       placeholder="Ketik kode CPMK baru, mis: CPMK011"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono
                              focus:outline-none focus:ring-2 focus:ring-amber-400">
                <p class="text-[10px] text-gray-400 mt-1">Jika memilih dari dropdown, kode di-isi otomatis. Kode baru akan menggunakan deskripsi CPMK yang sudah ada.</p>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Tambahkan
                </button>
                <button type="button" onclick="closeAdd()"
                        class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
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
// Data existing CPMK per CPL (di-encode dari PHP)
var existingByCpl = @json($existingByCpl ?? []);

var modal      = document.getElementById('add-modal');
var selCpmk    = document.getElementById('modal-cpmk-select');
var inpKode    = document.getElementById('modal-kode-input');
var inpCpl     = document.getElementById('modal-id-cpl');
var inpMk      = document.getElementById('modal-id-mk');
var inpDesk    = document.getElementById('modal-deskripsi');
var lblSub     = document.getElementById('modal-subtitle');
var lblMk      = document.getElementById('modal-mk-label');
var lblCpl     = document.getElementById('modal-cpl-label');

function openAdd(cplId, cplKode, mkId, mkKode) {
    if (!modal) return;

    inpCpl.value = cplId;
    inpMk.value  = mkId;
    inpDesk.value = '';
    lblMk.textContent  = mkKode;
    lblCpl.textContent = cplKode;
    lblSub.textContent = mkKode + '  ×  ' + cplKode;
    if (inpKode) inpKode.value = '';

    // Populate dropdown dengan existing CPMK untuk CPL ini
    if (selCpmk) {
        selCpmk.innerHTML = '<option value="">— Pilih CPMK yang sudah ada —</option>';
        var list = existingByCpl[cplId] || [];
        list.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item.kode;
            opt.dataset.deskripsi = item.deskripsi;
            opt.textContent = item.kode + ' — ' + item.deskripsi.substring(0, 50) + (item.deskripsi.length > 50 ? '…' : '');
            selCpmk.appendChild(opt);
        });
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeAdd() {
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function handleCpmkSelect(val) {
    if (!val) {
        if (inpKode) inpKode.value = '';
        if (inpDesk) inpDesk.value = '';
        return;
    }
    // Fill kode input and deskripsi hidden
    if (inpKode) inpKode.value = val;
    var opt = selCpmk.querySelector('option[value="' + val + '"]');
    if (opt && inpDesk) inpDesk.value = opt.dataset.deskripsi || '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAdd();
});
</script>
@endpush
