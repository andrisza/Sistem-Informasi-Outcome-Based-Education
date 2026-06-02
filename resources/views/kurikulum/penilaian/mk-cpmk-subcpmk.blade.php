@extends('layouts.app')
@section('title', 'Pemetaan MK–CPMK–SubCPMK')
@section('header', 'Pemetaan MK – CPMK – Sub-CPMK')
@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Peta MK–CPMK–Sub-CPMK</span>
@endsection

@section('content')

@if (session('success'))
    <div class="mb-4 flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-800">
        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

@php $thisUrl = route('kurikulum.penilaian.mk-cpmk-subcpmk', $kurikulum); @endphp

@if ($mkList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">Mata Kuliah belum diisi.</div>
@else

@include('layouts._search', ['target'=>'mk-cpmk-cards','placeholder'=>'Cari MK, CPMK, atau Sub-CPMK...','mode'=>'hide','rowSelector'=>'[data-search-card]'])
<div id="mk-cpmk-cards" class="space-y-4">
@foreach ($mkList as $mk)
    @php $cpmks = $mk->cpmk; @endphp
    <div class="bg-white rounded-xl border border-amber-200 shadow-sm overflow-hidden" data-search-card="true" data-search-text="{{ $mk->kode_mk }} {{ $mk->nama_mk }} {{ $mk->semester }}">

        {{-- MK Header --}}
        <div class="flex items-center gap-3 px-5 py-3" style="background:#F59E0B">
            <span class="font-mono font-bold text-white text-sm">{{ $mk->kode_mk }}</span>
            <span class="text-amber-100 text-sm">{{ $mk->nama_mk }}</span>
            <span class="text-amber-200 text-xs ml-1">· Smt {{ $mk->semester }} · {{ $mk->sks_total }} SKS</span>
            @if (!$kurikulum->isArsip())
                <button type="button"
                        onclick="openAddCpmkModal({{ $mk->id }}, '{{ addslashes($mk->kode_mk) }}', '{{ addslashes($mk->nama_mk) }}')"
                        class="ml-auto inline-flex items-center gap-1 bg-white/20 hover:bg-white/30 text-white text-xs font-medium px-3 py-1 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah CPMK
                </button>
            @endif
        </div>

        {{-- CPMK Table --}}
        @if ($cpmks->isEmpty())
            <div class="px-5 py-4 text-xs text-gray-400 italic">
                Belum ada CPMK untuk mata kuliah ini.
                @if (!$kurikulum->isArsip())
                    <button type="button" onclick="openAddCpmkModal({{ $mk->id }}, '{{ addslashes($mk->kode_mk) }}', '{{ addslashes($mk->nama_mk) }}')"
                            class="ml-2 text-amber-600 hover:underline font-medium">Tambah CPMK &rarr;</button>
                @endif
            </div>
        @else
        <table class="w-full text-xs border-collapse">
            <thead>
                <tr style="background:#FEF3C7">
                    <th class="px-4 py-2 text-left font-semibold text-amber-900 border border-amber-200 w-28">CPMK</th>
                    <th class="px-4 py-2 text-left font-semibold text-amber-900 border border-amber-200">Deskripsi CPMK</th>
                    <th class="px-4 py-2 text-center font-semibold text-amber-900 border border-amber-200 w-20">CPL</th>
                    <th class="px-4 py-2 text-left font-semibold text-amber-900 border border-amber-200">Sub-CPMK</th>
                    @if (!$kurikulum->isArsip())
                        <th class="px-3 py-2 text-center font-semibold text-amber-900 border border-amber-200 w-24">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($cpmks as $cpmk)
                    <tr class="border-b border-amber-100 hover:bg-amber-50/40" style="{{ $loop->even ? 'background:#fffbeb' : 'background:#fff' }}">
                        <td class="px-4 py-3 border border-amber-100 align-top">
                            <span class="font-mono font-bold text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded">{{ $cpmk->kode_cpmk }}</span>
                        </td>
                        <td class="px-4 py-3 border border-amber-100 text-gray-700 align-top">{{ $cpmk->deskripsi }}</td>
                        <td class="px-4 py-3 border border-amber-100 text-center align-top">
                            @if ($cpmk->cplProdi)
                                <span class="font-mono font-bold text-[10px] bg-violet-100 text-violet-800 px-1.5 py-0.5 rounded cursor-help"
                                      data-tooltip="{{ $cpmk->cplProdi->kode_cpl }}: {{ $cpmk->cplProdi->deskripsi }}"
                                      data-tip-label="CPL">{{ $cpmk->cplProdi->kode_cpl }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-amber-100 align-top">
                            @if ($cpmk->subCpmk->isNotEmpty())
                                <div class="space-y-1">
                                    @foreach ($cpmk->subCpmk as $sub)
                                        <div class="flex items-center gap-2 group">
                                            <span class="font-mono font-bold text-[10px] bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded shrink-0">{{ $sub->kode_sub_cpmk }}</span>
                                            <span class="text-gray-600 text-[10px] flex-1 leading-snug">{{ $sub->deskripsi }}</span>
                                            <span class="text-amber-700 text-[10px] font-bold shrink-0">{{ $sub->bobot }}%</span>
                                            @if (!$kurikulum->isArsip())
                                                <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                                                    <button type="button"
                                                            onclick="openEditSubCpmkModal({{ $sub->id }}, '{{ addslashes($sub->kode_sub_cpmk) }}', '{{ addslashes($sub->deskripsi) }}', {{ $sub->bobot }}, {{ $mk->id }}, {{ $cpmk->id }})"
                                                            class="p-0.5 text-gray-400 hover:text-blue-600 rounded">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.destroy', [$kurikulum, $mk, $cpmk, $sub]) }}"
                                                          onsubmit="return confirm('Hapus Sub-CPMK {{ $sub->kode_sub_cpmk }}?')">
                                                        @csrf @method('DELETE')
                                                        <input type="hidden" name="_redirect_to" value="{{ $thisUrl }}">
                                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-red-600 rounded">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-300 text-[10px] italic">Belum ada Sub-CPMK</span>
                            @endif
                            @if (!$kurikulum->isArsip())
                                <button type="button"
                                        onclick="openAddSubCpmkModal({{ $mk->id }}, {{ $cpmk->id }}, '{{ addslashes($cpmk->kode_cpmk) }}')"
                                        class="mt-1 inline-flex items-center gap-1 text-[10px] text-emerald-600 hover:text-emerald-700 hover:underline">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Sub-CPMK
                                </button>
                            @endif
                        </td>
                        @if (!$kurikulum->isArsip())
                            <td class="px-3 py-3 border border-amber-100 align-top">
                                <div class="flex items-center justify-center gap-1 mt-0.5">
                                    <button type="button"
                                            onclick="openEditCpmkModal({{ $cpmk->id }}, '{{ addslashes($cpmk->kode_cpmk) }}', '{{ addslashes($cpmk->deskripsi) }}', {{ $cpmk->id_cpl ?? 'null' }}, {{ $mk->id }})"
                                            class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit CPMK">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('kurikulum.mata-kuliah.cpmk.destroy', [$kurikulum, $mk, $cpmk]) }}"
                                          onsubmit="return confirm('Hapus CPMK {{ $cpmk->kode_cpmk }} beserta seluruh Sub-CPMK-nya?')">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="_redirect_to" value="{{ $thisUrl }}">
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus CPMK">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endforeach
</div>

{{-- ══ MODALS ══ --}}
@if (!$kurikulum->isArsip())

{{-- Overlay --}}
<div id="modal-overlay" class="fixed inset-0 bg-black/40 z-40 hidden" onclick="closeAllModals()"></div>

{{-- Modal: Add CPMK --}}
<div id="modal-add-cpmk" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Tambah CPMK</h3>
        <p id="add-cpmk-mk-label" class="text-xs text-gray-500 mb-4"></p>
        <form id="form-add-cpmk" method="POST" action="">
            @csrf
            <input type="hidden" name="_redirect_to" value="{{ $thisUrl }}">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">CPL Terkait <span class="text-red-500">*</span></label>
                    <select name="id_cpl" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Pilih CPL —</option>
                        @foreach ($cplList as $cpl)
                            <option value="{{ $cpl->id }}">{{ $cpl->kode_cpl }} — {{ Str::limit($cpl->deskripsi, 60) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi CPMK <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="Mampu..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-4 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium py-2 rounded-lg">Simpan</button>
                <button type="button" onclick="closeAllModals()" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit CPMK --}}
<div id="modal-edit-cpmk" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">Edit CPMK</h3>
        <form id="form-edit-cpmk" method="POST" action="">
            @csrf @method('PUT')
            <input type="hidden" name="_redirect_to" value="{{ $thisUrl }}">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">CPL Terkait <span class="text-red-500">*</span></label>
                    <select name="id_cpl" id="edit-cpmk-cpl" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Pilih CPL —</option>
                        @foreach ($cplList as $cpl)
                            <option value="{{ $cpl->id }}">{{ $cpl->kode_cpl }} — {{ Str::limit($cpl->deskripsi, 60) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi CPMK <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="edit-cpmk-deskripsi" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kode CPMK <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_cpmk" id="edit-cpmk-kode" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex gap-3 mt-4 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg">Perbarui</button>
                <button type="button" onclick="closeAllModals()" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Add Sub-CPMK --}}
<div id="modal-add-subcpmk" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Tambah Sub-CPMK</h3>
        <p id="add-sub-cpmk-label" class="text-xs text-gray-500 mb-4"></p>
        <form id="form-add-subcpmk" method="POST" action="">
            @csrf
            <input type="hidden" name="_redirect_to" value="{{ $thisUrl }}">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi Sub-CPMK <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Mampu..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Bobot (%) <span class="text-red-500">*</span></label>
                    <input type="number" name="bobot" min="0" max="100" step="0.5" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="cth: 40">
                </div>
            </div>
            <div class="flex gap-3 mt-4 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 rounded-lg">Simpan</button>
                <button type="button" onclick="closeAllModals()" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit Sub-CPMK --}}
<div id="modal-edit-subcpmk" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">Edit Sub-CPMK</h3>
        <form id="form-edit-subcpmk" method="POST" action="">
            @csrf @method('PUT')
            <input type="hidden" name="_redirect_to" value="{{ $thisUrl }}">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kode Sub-CPMK <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_sub_cpmk" id="edit-sub-kode" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="edit-sub-deskripsi" rows="3" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Bobot (%) <span class="text-red-500">*</span></label>
                    <input type="number" name="bobot" id="edit-sub-bobot" min="0" max="100" step="0.5" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="flex gap-3 mt-4 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 rounded-lg">Perbarui</button>
                <button type="button" onclick="closeAllModals()" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>

@endif
@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
<script>
// Route templates for building URLs dynamically
const routes = {
    cpmkStore:  (kurikulumId, mkId)               => `/kurikulum/${kurikulumId}/mata-kuliah/${mkId}/cpmk`,
    cpmkUpdate: (kurikulumId, mkId, cpmkId)        => `/kurikulum/${kurikulumId}/mata-kuliah/${mkId}/cpmk/${cpmkId}`,
    subStore:   (kurikulumId, mkId, cpmkId)        => `/kurikulum/${kurikulumId}/mata-kuliah/${mkId}/cpmk/${cpmkId}/sub-cpmk`,
    subUpdate:  (kurikulumId, mkId, cpmkId, subId) => `/kurikulum/${kurikulumId}/mata-kuliah/${mkId}/cpmk/${cpmkId}/sub-cpmk/${subId}`,
};
const kurikulumId = {{ $kurikulum->id }};

function openModal(id) {
    document.getElementById('modal-overlay')?.classList.remove('hidden');
    document.getElementById(id)?.classList.remove('hidden');
}
function closeAllModals() {
    ['modal-overlay','modal-add-cpmk','modal-edit-cpmk','modal-add-subcpmk','modal-edit-subcpmk']
        .forEach(id => document.getElementById(id)?.classList.add('hidden'));
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAllModals(); });

function openAddCpmkModal(mkId, mkKode, mkNama) {
    const form = document.getElementById('form-add-cpmk');
    form.action = routes.cpmkStore(kurikulumId, mkId);
    document.getElementById('add-cpmk-mk-label').textContent = `${mkKode} — ${mkNama}`;
    form.reset();
    openModal('modal-add-cpmk');
}

function openEditCpmkModal(cpmkId, kode, deskripsi, cplId, mkId) {
    const form = document.getElementById('form-edit-cpmk');
    form.action = routes.cpmkUpdate(kurikulumId, mkId, cpmkId);
    document.getElementById('edit-cpmk-kode').value = kode;
    document.getElementById('edit-cpmk-deskripsi').value = deskripsi;
    const sel = document.getElementById('edit-cpmk-cpl');
    if (sel && cplId) sel.value = cplId;
    openModal('modal-edit-cpmk');
}

function openAddSubCpmkModal(mkId, cpmkId, cpmkKode) {
    const form = document.getElementById('form-add-subcpmk');
    form.action = routes.subStore(kurikulumId, mkId, cpmkId);
    document.getElementById('add-sub-cpmk-label').textContent = `Untuk CPMK: ${cpmkKode}`;
    form.reset();
    openModal('modal-add-subcpmk');
}

function openEditSubCpmkModal(subId, kode, deskripsi, bobot, mkId, cpmkId) {
    const form = document.getElementById('form-edit-subcpmk');
    form.action = routes.subUpdate(kurikulumId, mkId, cpmkId, subId);
    document.getElementById('edit-sub-kode').value = kode;
    document.getElementById('edit-sub-deskripsi').value = deskripsi;
    document.getElementById('edit-sub-bobot').value = bobot;
    openModal('modal-edit-subcpmk');
}
</script>
@endpush
