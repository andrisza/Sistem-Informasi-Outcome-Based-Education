@extends('layouts.app')

@section('title', 'Pengampuan MK')
@section('header', 'Pengampuan Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Pengampuan MK</span>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('dosen.pengampuan') }}"
          class="flex flex-wrap items-center gap-3 p-4">
        <select name="semester"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Semester</option>
            @foreach ($semesters as $sem)
                <option value="{{ $sem->id }}" {{ request('semester') == $sem->id ? 'selected' : '' }}>
                    {{ $sem->nama }} — {{ $sem->tahun_akademik }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['semester']))
            <a href="{{ route('dosen.pengampuan') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode MK</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">SKS</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($pengampuan as $p)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5 font-mono text-xs text-gray-600">{{ $p->mataKuliah->kode_mk ?? '—' }}</td>
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $p->mataKuliah->nama_mk ?? '—' }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $p->mataKuliah->sks_total ?? '—' }}</td>
                <td class="px-5 py-3.5 text-gray-600">
                    {{ $p->semester->nama ?? '—' }}
                    @if ($p->semester)
                        <span class="text-xs text-gray-400">— {{ $p->semester->tahun_akademik }}</span>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    @if ($p->is_koordinator)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                            Koordinator
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Pengajar
                        </span>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('dosen.rps.index', ['semester' => $p->id_semester]) }}"
                           class="text-xs text-blue-600 hover:underline">RPS</a>
                        <a href="{{ route('dosen.nilai.form', [$p->id_mk, $p->id_semester]) }}"
                           class="text-xs text-emerald-600 hover:underline">Nilai</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                    Tidak ada data pengampuan ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($pengampuan->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $pengampuan->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
