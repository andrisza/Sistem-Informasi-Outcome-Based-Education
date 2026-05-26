@extends('layouts.app')

@section('title', 'Input Nilai – ' . $mataKuliah->kode_mk)
@section('header', 'Input Nilai – ' . $mataKuliah->nama_mk)

@section('breadcrumb')
    <a href="{{ route('dosen.nilai.index') }}" class="hover:text-blue-600">Nilai</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $mataKuliah->kode_mk }} – {{ $semesterModel->nama_semester }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('dosen.nilai.rekap', [$mataKuliah->id, $semesterModel->id]) }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        Lihat Rekap
    </a>
@endsection

@section('content')

@if ($komponen->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-sm text-amber-800">
        Belum ada komponen asesmen untuk mata kuliah dan semester ini. Hubungi koordinator MK.
    </div>
@elseif ($enrollments->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-sm text-amber-800">
        Belum ada mahasiswa yang terdaftar di mata kuliah ini.
    </div>
@else

<form method="POST" action="{{ route('dosen.nilai.store', [$mataKuliah->id, $semesterModel->id]) }}">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
        <table class="text-sm border-collapse w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Mahasiswa</th>
                    @foreach ($komponen as $k)
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[130px]">
                            <div>{{ $k->nama_komponen }}</div>
                            <div class="text-gray-400 font-normal normal-case mt-0.5">{{ number_format($k->bobot_persen, 0) }}% / maks {{ $k->skor_maks }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($enrollments as $enrollment)
                    @php $mhs = $enrollment->mahasiswa; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 sticky left-0 bg-white z-10 border-r border-gray-50">
                            <p class="font-medium text-gray-800 text-sm">{{ $mhs->name }}</p>
                            <p class="text-xs text-gray-400">{{ $mhs->nim ?? $mhs->email }}</p>
                        </td>
                        @foreach ($komponen as $k)
                            @php $val = $nilaiExisting[$mhs->id][$k->id] ?? ''; @endphp
                            <td class="px-4 py-3 text-center">
                                <input type="number"
                                       name="nilai[{{ $mhs->id }}][{{ $k->id }}]"
                                       value="{{ $val }}"
                                       min="0" max="{{ $k->skor_maks }}" step="0.01"
                                       class="w-20 text-center border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="–">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center gap-3">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
            Simpan Nilai
        </button>
        <a href="{{ route('dosen.nilai.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
            Kembali
        </a>
    </div>
</form>

@endif

@endsection
