@extends('layouts.app')
@section('title', 'Enrollment MK Mahasiswa')
@section('header', 'Enrollment Mata Kuliah')

@section('breadcrumb')
    <span class="text-gray-700 font-medium">Enrollment MK</span>
@endsection

@section('header-actions')
    <a href="{{ route('kaprodi.enrollment.batch') }}"
       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Daftarkan Batch
    </a>
    <a href="{{ route('kaprodi.enrollment.create') }}"
       class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Satu
    </a>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    @foreach ([
        ['label'=>'Total Enrollment','value'=>$stats['total'],'color'=>'blue'],
        ['label'=>'Mahasiswa Unik','value'=>$stats['mahasiswa'],'color'=>'violet'],
        ['label'=>'Status Aktif','value'=>$stats['aktif'],'color'=>'amber'],
        ['label'=>'Lulus','value'=>$stats['lulus'],'color'=>'green'],
    ] as $s)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-2xl font-bold text-{{ $s['color'] }}-600">{{ $s['value'] }}</p>
        <p class="text-xs text-gray-500 mt-0.5">{{ $s['label'] }}</p>
        @if ($selectedSemester)
            <p class="text-[10px] text-gray-400">{{ $selectedSemester->nama }}</p>
        @endif
    </div>
    @endforeach
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Semester</label>
            <select name="semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="">Semua Semester</option>
                @foreach ($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ request('semester', $selectedSemester?->id) == $sem->id ? 'selected' : '' }}>
                        {{ $sem->nama }}{{ $sem->is_aktif ? ' ★' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Mata Kuliah</label>
            <select name="mk" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="">Semua MK</option>
                @foreach ($mkList as $mk)
                    <option value="{{ $mk->id }}" {{ request('mk') == $mk->id ? 'selected' : '' }}>
                        {{ $mk->kode_mk }} — {{ Str::limit($mk->nama_mk, 35) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="">Semua Status</option>
                @foreach (['aktif'=>'Aktif','mengulang'=>'Mengulang','lulus'=>'Lulus','tidak_lulus'=>'Tidak Lulus'] as $val=>$lab)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lab }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Mahasiswa</label>
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama / NIM..."
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Cari
                </button>
            </div>
        </div>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    @if ($enrollments->isEmpty())
        <div class="px-5 py-14 text-center">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm text-gray-400">Belum ada data enrollment.</p>
            <a href="{{ route('kaprodi.enrollment.batch') }}"
               class="mt-3 inline-flex items-center gap-1.5 text-sm text-amber-600 hover:underline font-medium">
                Daftarkan mahasiswa sekarang →
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Daftar</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($enrollments as $e)
                    @php
                        $badgeClass = match($e->status) {
                            'aktif'       => 'bg-blue-100 text-blue-700',
                            'mengulang'   => 'bg-orange-100 text-orange-700',
                            'lulus'       => 'bg-green-100 text-green-700',
                            'tidak_lulus' => 'bg-red-100 text-red-700',
                            default       => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $e->mahasiswa?->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $e->mahasiswa?->identifier ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-700">{{ $e->mataKuliah?->nama_mk ?? '—' }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $e->mataKuliah?->kode_mk ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $e->semester?->nama ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_',' ',$e->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-gray-500">
                            {{ $e->tanggal_daftar?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kaprodi.enrollment.edit', $e) }}"
                                   class="text-xs text-blue-600 hover:underline font-medium">Edit</a>
                                <form method="POST" action="{{ route('kaprodi.enrollment.destroy', $e) }}"
                                      onsubmit="return confirm('Hapus enrollment ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $enrollments->links() }}
        </div>
    @endif
</div>

@endsection
