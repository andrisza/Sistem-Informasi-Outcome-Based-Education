@extends('layouts.app')

@section('title', 'Input Nilai')
@section('header', 'Input Nilai Mahasiswa')

@section('breadcrumb')
    <a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Input Nilai</span>
@endsection

@section('content')

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <p class="text-sm text-gray-500">
            Pilih mata kuliah untuk memasukkan nilai mahasiswa per komponen asesmen.
        </p>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode MK</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
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
                <td class="px-5 py-3.5 text-gray-600">
                    {{ $p->semester->nama ?? '—' }}
                    <span class="text-xs text-gray-400">— {{ $p->semester->tahun_akademik ?? '' }}</span>
                </td>
                <td class="px-5 py-3.5">
                    @if ($p->is_koordinator)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                            Koordinator
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Pengajar
                        </span>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('dosen.nilai.form', [$p->id_mk, $p->id_semester]) }}"
                           class="inline-flex items-center gap-1 text-xs bg-emerald-100 text-emerald-700 hover:bg-emerald-200 font-medium px-3 py-1 rounded-full transition-colors">
                            Input Nilai
                        </a>
                        <a href="{{ route('dosen.nilai.rekap', [$p->id_mk, $p->id_semester]) }}"
                           class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 hover:bg-blue-200 font-medium px-3 py-1 rounded-full transition-colors">
                            Rekap
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400 text-sm">
                    Belum ada mata kuliah yang diampu.
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
