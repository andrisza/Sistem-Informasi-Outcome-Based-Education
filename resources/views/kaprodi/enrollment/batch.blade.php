@extends('layouts.app')
@section('title', 'Daftarkan Mahasiswa (Batch)')
@section('header', 'Daftarkan Mahasiswa ke Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kaprodi.enrollment.index') }}" class="hover:text-blue-600">Enrollment MK</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700 font-medium">Daftarkan Batch</span>
@endsection

@section('content')
<div class="max-w-3xl">
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6 text-sm text-amber-800">
        <strong>Daftarkan batch:</strong> pilih MK, semester, dan centang mahasiswa yang ingin didaftarkan sekaligus.
    </div>

    <form method="POST" action="{{ route('kaprodi.enrollment.store-batch') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Semester --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Semester <span class="text-red-500">*</span>
                </label>
                <select name="id_semester" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('id_semester') ? 'border-red-400' : '' }}">
                    <option value="">— Pilih Semester —</option>
                    @foreach ($semesters as $sem)
                        <option value="{{ $sem->id }}"
                            {{ old('id_semester', $semesterAktif?->id) == $sem->id ? 'selected' : '' }}>
                            {{ $sem->nama }} — {{ $sem->tahun_akademik }}
                            @if($sem->is_aktif) ★ (Aktif) @endif
                        </option>
                    @endforeach
                </select>
                @error('id_semester')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Mata Kuliah --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="id_mk" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('id_mk') ? 'border-red-400' : '' }}">
                    <option value="">— Pilih Mata Kuliah —</option>
                    @foreach ($mkList->groupBy('semester') as $smt => $mks)
                        <optgroup label="Semester {{ $smt }}">
                            @foreach ($mks as $mk)
                                <option value="{{ $mk->id }}" {{ old('id_mk') == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->kode_mk }} — {{ $mk->nama_mk }} ({{ $mk->sks_total }} SKS)
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('id_mk')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Status --}}
        <div class="max-w-xs">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
            <select name="status" required
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="aktif" selected>Aktif</option>
                <option value="mengulang">Mengulang</option>
                <option value="lulus">Lulus</option>
                <option value="tidak_lulus">Tidak Lulus</option>
            </select>
        </div>

        {{-- Pilih Mahasiswa --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-sm font-medium text-gray-700">
                    Pilih Mahasiswa <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal">({{ $mahasiswaList->count() }} tersedia)</span>
                </label>
                <div class="flex gap-3 text-xs">
                    <button type="button" onclick="selectAll(true)"
                            class="text-amber-600 hover:underline font-medium">Pilih Semua</button>
                    <button type="button" onclick="selectAll(false)"
                            class="text-gray-400 hover:underline">Hapus Pilihan</button>
                </div>
            </div>

            {{-- Search box --}}
            <input type="text" id="mhs-search" placeholder="Cari nama atau NIM..."
                   class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-amber-400">

            @error('id_mahasiswa')<p class="text-xs text-red-500 mb-2">{{ $message }}</p>@enderror

            <div class="border border-gray-200 rounded-xl overflow-hidden max-h-80 overflow-y-auto" id="mhs-list">
                @forelse ($mahasiswaList as $mhs)
                <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-amber-50 cursor-pointer border-b border-gray-50 mhs-item transition-colors"
                       data-name="{{ strtolower($mhs->name) }}" data-nim="{{ strtolower($mhs->identifier ?? '') }}">
                    <input type="checkbox" name="id_mahasiswa[]" value="{{ $mhs->id }}"
                           class="rounded border-gray-300 text-amber-500 focus:ring-amber-400"
                           {{ is_array(old('id_mahasiswa')) && in_array($mhs->id, old('id_mahasiswa')) ? 'checked' : '' }}>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $mhs->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $mhs->identifier ?? '—' }}</p>
                    </div>
                </label>
                @empty
                <div class="px-4 py-8 text-center text-sm text-gray-400">
                    Belum ada mahasiswa aktif.
                    <a href="{{ route('kaprodi.users.create') }}" class="text-amber-600 hover:underline ml-1">Tambah mahasiswa →</a>
                </div>
                @endforelse
            </div>
            <p class="text-xs text-gray-400 mt-1" id="selected-count">0 mahasiswa dipilih</p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                Daftarkan Mahasiswa
            </button>
            <a href="{{ route('kaprodi.enrollment.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const checkboxes = document.querySelectorAll('input[name="id_mahasiswa[]"]');
    const countEl    = document.getElementById('selected-count');
    const searchEl   = document.getElementById('mhs-search');

    function updateCount(){
        const n = document.querySelectorAll('input[name="id_mahasiswa[]"]:checked').length;
        countEl.textContent = n + ' mahasiswa dipilih';
        countEl.style.color = n > 0 ? '#d97706' : '#9ca3af';
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateCount));
    updateCount();

    window.selectAll = function(val){
        document.querySelectorAll('.mhs-item:not([style*="none"]) input[name="id_mahasiswa[]"]')
            .forEach(cb => cb.checked = val);
        updateCount();
    };

    searchEl.addEventListener('input', function(){
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.mhs-item').forEach(function(item){
            const match = !q || item.dataset.name.includes(q) || item.dataset.nim.includes(q);
            item.style.display = match ? '' : 'none';
        });
    });
})();
</script>
@endpush
