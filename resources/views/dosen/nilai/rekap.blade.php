@extends('layouts.app')

@section('title', 'Rekap Nilai – ' . $mataKuliah->kode_mk)
@section('header', 'Rekap Nilai – ' . $mataKuliah->nama_mk)

@section('breadcrumb')
    <a href="{{ route('dosen.nilai.index') }}" class="hover:text-blue-600">Nilai</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $mataKuliah->kode_mk }} – Rekap</span>
@endsection

@section('header-actions')
    <a href="{{ route('dosen.nilai.form', [$mataKuliah->id, $semesterModel->id]) }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        Edit Nilai
    </a>
@endsection

@section('content')

{{-- Info bar --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-4 flex items-center gap-6 text-sm">
    <div>
        <span class="text-gray-500 text-xs">MK</span>
        <p class="font-semibold text-gray-800">{{ $mataKuliah->kode_mk }} – {{ $mataKuliah->nama_mk }}</p>
    </div>
    <div>
        <span class="text-gray-500 text-xs">Semester</span>
        <p class="font-semibold text-gray-800">{{ $semesterModel->nama_semester }}</p>
    </div>
    <div>
        <span class="text-gray-500 text-xs">Mahasiswa</span>
        <p class="font-semibold text-gray-800">{{ $rekap->count() }}</p>
    </div>
    @php
        $lulus  = $rekap->where('lulus', true)->count();
        $persen = $rekap->count() > 0 ? round($lulus / $rekap->count() * 100) : 0;
    @endphp
    <div>
        <span class="text-gray-500 text-xs">Kelulusan</span>
        <p class="font-semibold {{ $persen >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $lulus }}/{{ $rekap->count() }} ({{ $persen }}%)</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
    <table class="text-sm border-collapse w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Mahasiswa</th>
                @foreach ($komponen as $k)
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <div>{{ $k->nama_komponen }}</div>
                        <div class="text-gray-400 font-normal normal-case">{{ number_format($k->bobot_persen, 0) }}%</div>
                    </th>
                @endforeach
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach ($rekap as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 sticky left-0 bg-white z-10 border-r border-gray-50">
                        <p class="font-medium text-gray-800">{{ $item['mahasiswa']->name }}</p>
                        <p class="text-xs text-gray-400">{{ $item['mahasiswa']->nim ?? $item['mahasiswa']->email }}</p>
                    </td>
                    @foreach ($komponen as $k)
                        @php $n = $item['nilai'][$k->id] ?? null; @endphp
                        <td class="px-4 py-3 text-center text-gray-700 text-sm">{{ $n !== null ? number_format($n, 1) : '–' }}</td>
                    @endforeach
                    <td class="px-4 py-3 text-center font-bold text-gray-900">
                        {{ $item['nilai_akhir'] !== null ? number_format($item['nilai_akhir'], 2) : '–' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($item['nilai_akhir'] === null)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Belum</span>
                        @elseif ($item['lulus'])
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Lulus</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Tidak Lulus</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
