@extends('layouts.app')

@section('title', $mataKuliah->kode_mk . ' – ' . $mataKuliah->nama_mk)
@section('header', $mataKuliah->nama_mk)

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}" class="hover:text-blue-600">Mata Kuliah</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $mataKuliah->kode_mk }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah]) }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        Kelola CPMK
    </a>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Info MK --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700">Informasi Mata Kuliah</h3>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-xs text-gray-500">Kode MK</dt>
                    <dd class="font-mono font-semibold text-blue-700 mt-0.5">{{ $mataKuliah->kode_mk }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Nama MK</dt>
                    <dd class="font-medium text-gray-800 mt-0.5">{{ $mataKuliah->nama_mk }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">SKS</dt>
                    <dd class="font-bold text-gray-900 mt-0.5">{{ $mataKuliah->sks_total }} SKS</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Semester</dt>
                    <dd class="font-medium text-gray-800 mt-0.5">Semester {{ $mataKuliah->semester }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Kompetensi</dt>
                    <dd class="mt-0.5">
                        @php
                            $kompColor = $mataKuliah->kompetensi_mk === 'Pendukung'
                                ? 'bg-violet-100 text-violet-700'
                                : 'bg-blue-100 text-blue-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kompColor }}">
                            {{ $mataKuliah->kompetensi_mk ?? 'Utama' }}
                        </span>
                    </dd>
                </div>
                @if ($mataKuliah->kategori_mk)
                <div>
                    <dt class="text-xs text-gray-500">Kategori</dt>
                    <dd class="text-xs text-gray-500 mt-0.5">
                        {{ $mataKuliah->kategori_mk }}
                        <a href="{{ route('kurikulum.organisasi-mk', $kurikulum) }}"
                           class="text-blue-500 hover:underline ml-1">(ubah di Organisasi MK)</a>
                    </dd>
                </div>
                @else
                <div class="rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700">
                    Kategori MK belum dipetakan. <a href="{{ route('kurikulum.organisasi-mk', $kurikulum) }}" class="underline font-medium">Atur di Organisasi MK</a>
                </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- CPMK list --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">CPMK ({{ $mataKuliah->cpmk->count() }})</h3>
                @if (!$kurikulum->isArsip())
                    <a href="{{ route('kurikulum.mata-kuliah.cpmk.create', [$kurikulum, $mataKuliah]) }}"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">+ Tambah CPMK</a>
                @endif
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">CPL</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sub-CPMK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($mataKuliah->cpmk as $cpmk)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk]) }}"
                                   class="font-mono text-xs font-semibold text-violet-700 hover:underline">{{ $cpmk->kode_cpmk }}</a>
                            </td>
                            <td class="px-5 py-3.5 text-gray-700 text-xs max-w-xs">{{ Str::limit($cpmk->deskripsi, 100) }}</td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">
                                {{ $cpmk->cplProdi?->kode_cpl ?? '–' }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs font-medium text-gray-600">{{ $cpmk->subCpmk->count() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada CPMK.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
