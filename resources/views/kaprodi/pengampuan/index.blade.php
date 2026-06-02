@extends('layouts.app')

@section('title', 'Pengampuan Mata Kuliah')
@section('header', 'Pengampuan Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kaprodi.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Pengampuan MK</span>
@endsection

@section('header-actions')
    <a href="{{ route('kaprodi.pengampuan.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengampuan
    </a>
@endsection

@section('content')

{{-- Info Box --}}
<div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-blue-800 flex items-start gap-3">
    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        <p class="font-semibold">Prasyarat agar Dosen bisa membuat RPS</p>
        <p class="text-xs mt-0.5 text-blue-700">
            Dosen hanya bisa membuat dan mengajukan RPS untuk MK yang <strong>sudah ditugaskan</strong> di sini.
            Pastikan setiap MK memiliki minimal <strong>1 dosen pengampu</strong> dan <strong>1 koordinator</strong> per semester.
        </p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('kaprodi.pengampuan.index') }}"
          class="flex flex-wrap items-end gap-3 p-4">
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-medium text-gray-600 mb-1">Semester</label>
            <select name="id_semester"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Semester</option>
                @foreach ($semesters as $sem)
                    <option value="{{ $sem->id }}"
                            {{ $selectedSemester == $sem->id ? 'selected' : '' }}>
                        {{ $sem->nama }}{{ $sem->is_aktif ? ' ★' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-medium text-gray-600 mb-1">Dosen</label>
            <select name="id_dosen"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Dosen</option>
                @foreach ($dosenList as $d)
                    <option value="{{ $d->id }}"
                            {{ request('id_dosen') == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-medium text-gray-600 mb-1">Mata Kuliah</label>
            <select name="id_mk"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua MK</option>
                @foreach ($mkList as $mk)
                    <option value="{{ $mk->id }}"
                            {{ request('id_mk') == $mk->id ? 'selected' : '' }}>
                        {{ $mk->kode_mk }} — {{ Str::limit($mk->nama_mk, 30) }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['id_semester','id_dosen','id_mk']))
            <a href="{{ route('kaprodi.pengampuan.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Batch Assign Form --}}
<details class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <summary class="px-5 py-3 cursor-pointer text-sm font-semibold text-gray-700 hover:text-gray-900 select-none flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Assign Batch — Satu Dosen ke Banyak MK Sekaligus
    </summary>
    <div class="border-t border-gray-100 px-5 py-4">
        <form method="POST" action="{{ route('kaprodi.pengampuan.store-batch') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                    <select name="id_semester" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Pilih Semester —</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $selectedSemester == $sem->id ? 'selected' : '' }}>
                                {{ $sem->nama }}{{ $sem->is_aktif ? ' ★ (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Dosen <span class="text-red-500">*</span></label>
                    <select name="id_dosen" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Pilih Dosen —</option>
                        @foreach ($dosenList as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Koordinator untuk MK</label>
                    <select name="id_koordinator"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Tidak ada koordinator —</option>
                        @foreach ($mkList as $mk)
                            <option value="{{ $mk->id }}">{{ $mk->kode_mk }} — {{ Str::limit($mk->nama_mk, 30) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1.5">
                    Pilih Mata Kuliah <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal ml-1">(pilih satu atau lebih)</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                    @foreach ($mkList->groupBy('semester') as $smt => $mks)
                        <div class="col-span-full">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Semester {{ $smt }}</p>
                        </div>
                        @foreach ($mks as $mk)
                            <label class="flex items-start gap-2 text-xs cursor-pointer hover:bg-gray-50 rounded px-1 py-0.5">
                                <input type="checkbox" name="mk_ids[]" value="{{ $mk->id }}"
                                       class="mt-0.5 rounded border-gray-300 text-blue-600">
                                <span>
                                    <span class="font-mono font-semibold text-gray-700">{{ $mk->kode_mk }}</span>
                                    <span class="text-gray-500 block leading-tight">{{ Str::limit($mk->nama_mk, 25) }}</span>
                                </span>
                            </label>
                        @endforeach
                    @endforeach
                </div>
            </div>
            <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Assign Semua MK Terpilih
            </button>
        </form>
    </div>
</details>

{{-- Tabel Data Pengampuan --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800">
            Daftar Pengampuan
            @if ($selectedSemester)
                — {{ $semesters->firstWhere('id', $selectedSemester)?->nama ?? '' }}
            @endif
        </h3>
        <span class="text-xs text-gray-400">{{ $pengampuanList->total() }} pengampuan</span>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen Pengampu</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($pengampuanList as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="font-mono font-semibold text-gray-800 text-xs">{{ $p->mataKuliah->kode_mk }}</span>
                        <span class="text-gray-500 text-xs ml-1.5">{{ $p->mataKuliah->nama_mk }}</span>
                        <span class="text-gray-400 text-[10px] block">Smt {{ $p->mataKuliah->semester }} · {{ $p->mataKuliah->sks_total }} SKS</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-sm font-medium text-gray-800">{{ $p->dosen->name }}</span>
                        <span class="text-xs text-gray-400 block">{{ $p->dosen->email }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-sm text-gray-700">{{ $p->semester->nama }}</span>
                        @if ($p->semester->is_aktif)
                            <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-700">Aktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if ($p->is_koordinator)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Koordinator
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                Pengampu
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">
                            {{-- Toggle Koordinator --}}
                            <form method="POST" action="{{ route('kaprodi.pengampuan.toggle-koord', $p) }}">
                                @csrf
                                <button type="submit"
                                        title="{{ $p->is_koordinator ? 'Turunkan dari Koordinator' : 'Jadikan Koordinator' }}"
                                        class="p-1.5 rounded-lg transition-colors
                                               {{ $p->is_koordinator
                                                   ? 'text-amber-500 hover:text-amber-700 hover:bg-amber-50'
                                                   : 'text-gray-400 hover:text-amber-600 hover:bg-amber-50' }}">
                                    <svg class="w-4 h-4" fill="{{ $p->is_koordinator ? 'currentColor' : 'none' }}" viewBox="0 0 20 20">
                                        <path {{ $p->is_koordinator ? '' : 'stroke="currentColor" stroke-width="1.5"' }} d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            </form>
                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('kaprodi.pengampuan.destroy', $p) }}"
                                  onsubmit="return confirm('Hapus pengampuan {{ $p->dosen->name }} dari {{ $p->mataKuliah->kode_mk }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        title="Hapus pengampuan"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center text-gray-400 text-sm">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="font-medium text-gray-500">Belum ada pengampuan</p>
                        <p class="text-xs text-gray-400 mt-1">
                            Gunakan tombol <strong>Tambah Pengampuan</strong> atau form <strong>Assign Batch</strong> di atas.
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($pengampuanList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $pengampuanList->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
