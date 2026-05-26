@extends('layouts.app')

@section('title', 'Distribusi Semester')
@section('header', 'Distribusi Semester')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Distribusi Semester</span>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Summary cards --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
            <p class="text-xs text-gray-500 mb-1">Total MK</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalMk }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
            <p class="text-xs text-gray-500 mb-1">Total SKS</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalSks }}</p>
        </div>
    </div>

    {{-- Per semester table --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah MK</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total SKS</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Distribusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($distribusi as $row)
                        @php
                            $persen = $totalSks > 0 ? round(($row->total_sks / $totalSks) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3.5 font-semibold text-gray-800">Semester {{ $row->semester }}</td>
                            <td class="px-5 py-3.5 text-center text-gray-600">{{ $row->jumlah_mk }}</td>
                            <td class="px-5 py-3.5 text-center font-semibold text-blue-700">{{ $row->total_sks }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $persen }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 w-8 text-right">{{ $persen }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">
                                Belum ada data distribusi. Tambahkan mata kuliah terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($distribusi->isNotEmpty())
                    <tfoot>
                        <tr class="bg-gray-50 border-t border-gray-200">
                            <td class="px-5 py-3 text-xs font-bold text-gray-700 uppercase">Total</td>
                            <td class="px-5 py-3 text-center text-xs font-bold text-gray-700">{{ $totalMk }}</td>
                            <td class="px-5 py-3 text-center text-xs font-bold text-blue-700">{{ $totalSks }}</td>
                            <td class="px-5 py-3"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>

@endsection
