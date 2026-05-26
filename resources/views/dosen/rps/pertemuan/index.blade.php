@extends('layouts.app')

@section('title', 'Pertemuan RPS')
@section('header', 'Kelola Pertemuan')

@section('breadcrumb')
    <a href="{{ route('dosen.rps.index') }}" class="hover:text-blue-600">RPS Saya</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('dosen.rps.show', $rps) }}" class="hover:text-blue-600">Detail</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Pertemuan</span>
@endsection

@section('content')

{{-- Info RPS --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6 flex items-center gap-4">
    <div class="min-w-0 flex-1">
        <p class="font-semibold text-gray-800">{{ $rps->mataKuliah->nama_mk ?? '—' }}</p>
        <p class="text-xs text-gray-500">{{ $rps->semester->nama ?? '—' }} — {{ $rps->semester->tahun_akademik ?? '' }}</p>
    </div>
    @php
        $filled = collect($mingguList)->filter(fn($m) => $m['data'])->count();
    @endphp
    <div class="text-right shrink-0">
        <p class="text-sm font-bold text-emerald-600">{{ $filled }}/16</p>
        <p class="text-xs text-gray-400">pertemuan terisi</p>
    </div>
</div>

{{-- Grid 16 pertemuan --}}
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-4">
    @foreach ($mingguList as $item)
    @php
        $terisi = (bool) $item['data'];
    @endphp
    <a href="{{ route('dosen.rps.pertemuan.show', [$rps, $item['minggu_ke']]) }}"
       class="bg-white rounded-xl border shadow-sm p-4 flex flex-col gap-2 hover:shadow-md transition-shadow
              {{ $terisi ? 'border-emerald-200 hover:border-emerald-400' : 'border-gray-100 hover:border-gray-300' }}">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold {{ $terisi ? 'text-emerald-600' : 'text-gray-300' }}">
                {{ str_pad($item['minggu_ke'], 2, '0', STR_PAD_LEFT) }}
            </span>
            @if ($terisi)
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            @else
                <span class="w-2 h-2 rounded-full bg-gray-200"></span>
            @endif
        </div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Minggu ke-{{ $item['minggu_ke'] }}</p>
        @if ($item['data'])
            <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit($item['data']->materi_pembelajaran, 60) }}</p>
        @else
            <p class="text-xs text-gray-400 italic">Belum diisi</p>
        @endif
        <div class="mt-auto pt-1 border-t border-gray-100 flex justify-end">
            <span class="text-xs {{ $terisi ? 'text-emerald-600' : 'text-blue-500' }} font-medium">
                {{ $terisi ? 'Edit →' : 'Isi →' }}
            </span>
        </div>
    </a>
    @endforeach
</div>

@endsection
