@extends('layouts.app')

@section('title', 'Master Kategori')
@section('header', 'Master Kategori')

@section('breadcrumb')
    <span class="text-gray-700 font-medium">Master Kategori</span>
@endsection

@section('content')

@php
    $jenisLabels = [
        'pl'  => 'PL (Profil Lulusan)',
        'cpl' => 'CPL Prodi',
        'bk'  => 'Bahan Kajian',
        'mk'  => 'Mata Kuliah',
    ];
    $defaultJenis = array_keys($jenisLabels);
    $customJenis  = $grouped->keys()->diff($defaultJenis)->sort()->values()->toArray();
    $allJenis     = array_merge($defaultJenis, $customJenis);
    $activeTab    = request()->get('tab', $allJenis[0] ?? 'pl');
    if (!in_array($activeTab, $allJenis)) $activeTab = $allJenis[0] ?? 'pl';
@endphp

@if ($errors->any())
    <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-800">
        <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Tab Bar --}}
<div class="flex items-end gap-2 mb-5 border-b border-gray-200">
    <div class="flex gap-1 flex-1 flex-wrap">
        @foreach ($allJenis as $tabKey)
            @php $tabLabel = $jenisLabels[$tabKey] ?? ucwords(str_replace('_', ' ', $tabKey)); @endphp
            <button type="button"
                    onclick="switchTab('{{ $tabKey }}')"
                    id="tab-btn-{{ $tabKey }}"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors duration-150
                           {{ $tabKey === $activeTab ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                {{ $tabLabel }}
                <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-bold
                             {{ $tabKey === $activeTab ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}"
                      id="tab-count-{{ $tabKey }}">
                    {{ $grouped->get($tabKey, collect())->count() }}
                </span>
            </button>
        @endforeach
    </div>
    {{-- Tambah Jenis Baru --}}
    <button type="button" onclick="openAddJenisModal()"
            style="background:#4F46E5;color:#fff;flex-shrink:0;margin-bottom:4px;display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:600;padding:6px 12px;border-radius:8px;border:none;cursor:pointer">
        <svg style="width:14px;height:14px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Jenis Baru
    </button>
</div>

{{-- Tab Panels --}}
@foreach ($allJenis as $tabKey)
    @php $tabLabel = $jenisLabels[$tabKey] ?? ucwords(str_replace('_', ' ', $tabKey)); @endphp
    <div id="tab-panel-{{ $tabKey }}" class="{{ $tabKey === $activeTab ? '' : 'hidden' }}">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">

            {{-- Table header --}}
            <div style="background:#F59E0B" class="px-5 py-3 flex items-center justify-between">
                <span style="color:#fff" class="font-bold text-sm">Kategori — {{ $tabLabel }}</span>
                <span class="text-amber-100 text-xs">{{ $grouped->get($tabKey, collect())->count() }} kategori</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr style="background:#FEF3C7">
                            <th class="px-3 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-14">Urutan</th>
                            <th class="px-4 py-2.5 text-left text-xs font-bold text-amber-900 border border-amber-200">Nama Kategori</th>
                            <th class="px-4 py-2.5 text-left text-xs font-bold text-amber-900 border border-amber-200">Deskripsi</th>
                            <th class="px-3 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-24">Status</th>
                            <th class="px-3 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grouped->get($tabKey, collect()) as $kat)
                            {{-- View row --}}
                            <tr id="row-{{ $kat->id }}" class="border-b border-amber-100 hover:bg-amber-50 transition-colors"
                                style="{{ $loop->even ? 'background:#fffbeb' : 'background:#fff' }}">
                                <td class="px-3 py-3 text-center text-xs text-gray-600 border border-amber-100 font-medium">{{ $kat->urutan }}</td>
                                <td class="px-4 py-3 border border-amber-100 font-medium text-gray-800">{{ $kat->nama }}</td>
                                <td class="px-4 py-3 border border-amber-100 text-gray-500 text-xs">{{ $kat->deskripsi ?: '—' }}</td>
                                <td class="px-3 py-3 text-center border border-amber-100">
                                    @if ($kat->is_aktif)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 border border-amber-100">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Edit button --}}
                                        <button type="button"
                                                onclick="startEdit({{ $kat->id }})"
                                                class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Toggle aktif/nonaktif --}}
                                        <form method="POST" action="{{ route('admin.master-kategori.toggle', $kat) }}"
                                              onsubmit="return confirm('{{ $kat->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }} kategori \"{{ addslashes($kat->nama) }}\"?')">
                                            @csrf
                                            <button type="submit"
                                                    class="p-1.5 rounded transition-colors {{ $kat->is_aktif ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50' }}"
                                                    title="{{ $kat->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                @if ($kat->is_aktif)
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Inline edit row (hidden by default) --}}
                            <tr id="edit-row-{{ $kat->id }}" class="hidden bg-blue-50 border-b border-blue-200">
                                <td colspan="5" class="px-4 py-3 border border-blue-200">
                                    <form method="POST" action="{{ route('admin.master-kategori.update', $kat) }}" class="flex items-end gap-3 flex-wrap">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex-shrink-0 w-20">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Urutan</label>
                                            <input type="number" name="urutan" value="{{ $kat->urutan }}" min="0"
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div class="flex-1 min-w-40">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama <span class="text-red-500">*</span></label>
                                            <input type="text" name="nama" value="{{ $kat->nama }}" required
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div class="flex-2 min-w-52">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                                            <input type="text" name="deskripsi" value="{{ $kat->deskripsi }}"
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div class="flex gap-2 shrink-0">
                                            <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-4 py-1.5 rounded-lg transition-colors">
                                                Simpan
                                            </button>
                                            <button type="button" onclick="cancelEdit({{ $kat->id }})"
                                                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium px-4 py-1.5 rounded-lg transition-colors">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">
                                    Belum ada kategori untuk jenis ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Add new form --}}
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tambah Kategori Baru</p>
                <form method="POST" action="{{ route('admin.master-kategori.store') }}" class="flex items-end gap-3 flex-wrap">
                    @csrf
                    <input type="hidden" name="jenis" value="{{ $tabKey }}">
                    <div class="flex-shrink-0 w-20">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" value="{{ $grouped->get($tabKey, collect())->max('urutan') + 1 }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div class="flex-1 min-w-40">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" required placeholder="Nama kategori baru..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div class="flex-2 min-w-52">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                        <input type="text" name="deskripsi" placeholder="Deskripsi singkat (opsional)..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div class="shrink-0">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium px-4 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
@endforeach

{{-- ═══ Modal Tambah Jenis Baru ═══ --}}
<div id="modal-add-jenis"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5)"
     onclick="if(event.target===this)closeAddJenisModal()">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                background:#fff;border-radius:16px;width:440px;max-width:95vw;
                padding:24px;box-shadow:0 20px 60px rgba(0,0,0,.25)">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-800">Tambah Jenis Kategori Baru</h3>
                <p class="text-xs text-gray-500 mt-0.5">Tab baru akan muncul otomatis di sebelah "Mata Kuliah"</p>
            </div>
            <button type="button" onclick="closeAddJenisModal()"
                    class="text-gray-400 hover:text-gray-600 w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-lg font-bold leading-none">
                ×
            </button>
        </div>
        <form method="POST" action="{{ route('admin.master-kategori.store-jenis') }}">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Jenis (Label Tab) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="label" id="jenis-label-input-admin" required
                           placeholder="contoh: Mata Kuliah Khusus, MK Pilihan Lanjut"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <p class="text-[10px] text-gray-400 mt-1">Nama tab yang akan muncul di halaman ini</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Kategori Pertama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" required
                           placeholder="contoh: Kategori A"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <p class="text-[10px] text-gray-400 mt-1">Setiap jenis harus memiliki minimal satu kategori</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <input type="text" name="deskripsi"
                           placeholder="Deskripsi singkat (opsional)"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>
            <div class="mt-5">
                <button type="submit"
                        style="width:100%;background:#4F46E5;color:#fff;padding:10px;border-radius:8px;border:none;font-size:14px;font-weight:600;cursor:pointer">
                    + Buat Jenis Baru
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    var allTabs    = @json($allJenis);
    var activeTab  = @json($activeTab);
    var STORAGE_KEY = 'master-kategori:tab';

    function switchTab(key) {
        allTabs.forEach(function (t) {
            var panel = document.getElementById('tab-panel-' + t);
            var btn   = document.getElementById('tab-btn-' + t);
            var count = document.getElementById('tab-count-' + t);
            if (!panel || !btn) return;

            if (t === key) {
                panel.classList.remove('hidden');
                btn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                btn.classList.add('border-amber-500', 'text-amber-700');
                if (count) {
                    count.classList.remove('bg-gray-100', 'text-gray-600');
                    count.classList.add('bg-amber-100', 'text-amber-800');
                }
            } else {
                panel.classList.add('hidden');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                btn.classList.remove('border-amber-500', 'text-amber-700');
                if (count) {
                    count.classList.add('bg-gray-100', 'text-gray-600');
                    count.classList.remove('bg-amber-100', 'text-amber-800');
                }
            }
        });
        try { localStorage.setItem(STORAGE_KEY, key); } catch (e) {}
    }

    // If URL has ?tab=, honour it (PHP already rendered correct tab, just sync localStorage)
    var urlParams  = new URLSearchParams(window.location.search);
    var tabFromUrl = urlParams.get('tab');
    if (tabFromUrl && allTabs.indexOf(tabFromUrl) !== -1) {
        try { localStorage.setItem(STORAGE_KEY, tabFromUrl); } catch (e) {}
    } else {
        // Restore from localStorage only if no URL param
        try {
            var saved = localStorage.getItem(STORAGE_KEY);
            if (saved && allTabs.indexOf(saved) !== -1 && saved !== activeTab) {
                switchTab(saved);
            }
        } catch (e) {}
    }

    window.switchTab = switchTab;

    window.startEdit = function (id) {
        document.getElementById('row-' + id).classList.add('hidden');
        document.getElementById('edit-row-' + id).classList.remove('hidden');
    };

    window.cancelEdit = function (id) {
        document.getElementById('edit-row-' + id).classList.add('hidden');
        document.getElementById('row-' + id).classList.remove('hidden');
    };

    window.openAddJenisModal = function () {
        document.getElementById('modal-add-jenis').style.display = 'block';
    };

    window.closeAddJenisModal = function () {
        document.getElementById('modal-add-jenis').style.display = 'none';
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAddJenisModal();
    });
})();
</script>
@endpush
