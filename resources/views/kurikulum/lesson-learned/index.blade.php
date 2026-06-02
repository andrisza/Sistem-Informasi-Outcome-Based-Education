@extends('layouts.app')

@section('title', 'Lesson Learned – ' . $kurikulum->kode)
@section('header', 'Lesson Learned')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Lesson Learned</span>
@endsection

@section('content')

{{-- Filter bar --}}
<div class="flex flex-wrap items-center gap-3 mb-4">
    <select id="filter-kategori" onchange="applyFilter()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Kategori</option>
        @foreach ($kategoriList as $k)
            <option value="{{ $k }}">{{ $k }}</option>
        @endforeach
    </select>
    <select id="filter-prioritas" onchange="applyFilter()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Prioritas</option>
        <option value="high">High</option>
        <option value="medium">Medium</option>
        <option value="low">Low</option>
    </select>
    <select id="filter-status" onchange="applyFilter()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        <option value="open">Open</option>
        <option value="ditangani">Ditangani</option>
    </select>
    <span class="ml-auto text-xs text-gray-400">{{ $lessons->count() }} entri</span>
</div>

{{-- Tambah form (collapsible) --}}
@if (!$kurikulum->isArsip())
<details class="mb-4 bg-white border border-blue-100 rounded-xl shadow-sm">
    <summary class="px-5 py-3 text-sm font-semibold text-blue-700 cursor-pointer hover:bg-blue-50 rounded-xl select-none flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Lesson Learned
    </summary>
    <div class="px-5 pb-5 pt-3 border-t border-blue-100">
        <form method="POST" action="{{ route('kurikulum.lesson-learned.store', $kurikulum) }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}" list="kategori-list"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: Kurikulum, RPS, Penilaian">
                    <datalist id="kategori-list">
                        @foreach ($kategoriList as $k) <option value="{{ $k }}"> @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
                    <select name="prioritas"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="high" {{ old('prioritas') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ old('prioritas', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ old('prioritas') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Temuan <span class="text-red-500">*</span></label>
                <textarea name="temuan" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Deskripsi temuan / masalah yang dijumpai...">{{ old('temuan') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Rekomendasi <span class="text-red-500">*</span></label>
                <textarea name="rekomendasi" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Langkah perbaikan yang direkomendasikan...">{{ old('rekomendasi') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</details>
@endif

{{-- Tabel --}}
<div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm border-collapse" id="ll-table">
        <thead>
            <tr class="bg-slate-700 text-white">
                <th class="px-4 py-3 text-left text-xs font-bold">Kategori</th>
                <th class="px-4 py-3 text-left text-xs font-bold">Temuan</th>
                <th class="px-4 py-3 text-left text-xs font-bold">Rekomendasi</th>
                <th class="px-4 py-3 text-center text-xs font-bold w-24">Prioritas</th>
                <th class="px-4 py-3 text-center text-xs font-bold w-24">Status</th>
                @if (!$kurikulum->isArsip())
                    <th class="px-4 py-3 text-center text-xs font-bold w-24">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($lessons as $lesson)
                @php
                    $prioClass = match($lesson->prioritas) {
                        'high'   => 'bg-red-100 text-red-700',
                        'medium' => 'bg-amber-100 text-amber-700',
                        'low'    => 'bg-gray-100 text-gray-600',
                        default  => 'bg-gray-100 text-gray-600',
                    };
                    $statusClass = $lesson->status === 'ditangani'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-amber-100 text-amber-700';
                @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors ll-row"
                    data-kategori="{{ strtolower($lesson->kategori) }}"
                    data-prioritas="{{ $lesson->prioritas }}"
                    data-status="{{ $lesson->status }}">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">{{ $lesson->kategori }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700 max-w-xs">{{ Str::limit($lesson->temuan, 100) }}</td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">{{ Str::limit($lesson->rekomendasi, 100) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $prioClass }}">
                            {{ ucfirst($lesson->prioritas) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if (!$kurikulum->isArsip())
                        <form method="POST" action="{{ route('kurikulum.lesson-learned.update', [$kurikulum, $lesson]) }}" class="inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="{{ $lesson->status === 'open' ? 'ditangani' : 'open' }}">
                            <button type="submit" title="Toggle Status"
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold cursor-pointer {{ $statusClass }} hover:opacity-80 transition-opacity border-0">
                                {{ $lesson->status === 'open' ? 'Open' : 'Ditangani' }}
                            </button>
                        </form>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $statusClass }}">
                                {{ $lesson->status === 'open' ? 'Open' : 'Ditangani' }}
                            </span>
                        @endif
                    </td>
                    @if (!$kurikulum->isArsip())
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('kurikulum.lesson-learned.destroy', [$kurikulum, $lesson]) }}"
                              onsubmit="return confirm('Hapus entri ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $kurikulum->isArsip() ? 5 : 6 }}" class="px-5 py-12 text-center text-sm text-gray-400">
                        Belum ada lesson learned untuk kurikulum ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
function applyFilter() {
    var kat  = document.getElementById('filter-kategori').value.toLowerCase();
    var prio = document.getElementById('filter-prioritas').value;
    var stat = document.getElementById('filter-status').value;

    document.querySelectorAll('.ll-row').forEach(function (row) {
        var match = true;
        if (kat  && !row.dataset.kategori.includes(kat))  match = false;
        if (prio && row.dataset.prioritas !== prio)        match = false;
        if (stat && row.dataset.status !== stat)           match = false;
        row.style.display = match ? '' : 'none';
    });
}
</script>
@endpush
