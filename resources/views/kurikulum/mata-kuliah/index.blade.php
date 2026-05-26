@extends('layouts.app')

@section('title', 'Mata Kuliah – ' . $kurikulum->kode)
@section('header', 'Daftar Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Mata Kuliah</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.mata-kuliah.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah MK
        </a>
    @endif
@endsection

@section('content')

@php
    $katColors = [
        'Wajib'  => ['bg' => '#eff6ff', 'badge' => 'bg-blue-100 text-blue-800',    'dot' => '#3b82f6'],
        'Pilihan'=> ['bg' => '#f5f3ff', 'badge' => 'bg-violet-100 text-violet-800','dot' => '#7c3aed'],
        'MKWK'   => ['bg' => '#f0fdf4', 'badge' => 'bg-emerald-100 text-emerald-800','dot'=> '#059669'],
        'MKDU'   => ['bg' => '#fffbeb', 'badge' => 'bg-amber-100 text-amber-800',  'dot' => '#d97706'],
    ];
    $totalSks = $mkList->sum('sks_total');
@endphp

{{-- Summary bar --}}
<div class="flex flex-wrap items-center gap-4 bg-white border border-gray-100 rounded-xl px-5 py-3 mb-4 shadow-sm text-xs">
    <span class="text-gray-500">Total: <strong class="text-gray-800">{{ $mkList->count() }} MK</strong></span>
    <span class="text-gray-300">|</span>
    <span class="text-gray-500">Total SKS: <strong class="text-gray-800">{{ $totalSks }}</strong></span>
    <span class="text-gray-300">|</span>
    @foreach ($katColors as $kat => $c)
        @php $cnt = $mkList->where('kategori_mk', $kat)->count(); @endphp
        @if ($cnt)
            <span class="inline-flex items-center gap-1.5 {{ $c['badge'] }} px-2.5 py-0.5 rounded-full text-xs font-medium">
                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $c['dot'] }}"></span>
                {{ $kat }}: {{ $cnt }}
            </span>
        @endif
    @endforeach
</div>

@if ($mkList->isEmpty())
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm px-5 py-14 text-center text-gray-400 text-sm">
        <div class="flex flex-col items-center gap-2">
            <svg class="w-10 h-10 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span>Belum ada Mata Kuliah.</span>
            @if (!$kurikulum->isArsip())
                <a href="{{ route('kurikulum.mata-kuliah.create', $kurikulum) }}" class="text-amber-600 hover:underline font-medium">Tambah MK pertama →</a>
            @endif
        </div>
    </div>
@else

@foreach ($bySmester as $smt => $mks)
@php
    $smtSks = $mks->sum('sks_total');
    $smtCount = $mks->count();
@endphp

<div class="mb-5 rounded-xl border border-amber-200 shadow-sm overflow-hidden">
    {{-- Semester header --}}
    <div style="background:#F59E0B" class="flex items-center justify-between px-5 py-2.5">
        <span class="text-white font-bold text-sm">Semester {{ $smt }}</span>
        <div class="flex items-center gap-3 text-xs text-amber-100">
            <span>{{ $smtCount }} MK</span>
            <span class="text-amber-200">·</span>
            <span>{{ $smtSks }} SKS</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr style="background:#FEF3C7">
                    <th class="px-4 py-2.5 text-left text-xs font-bold text-amber-900 border border-amber-200 w-28">Kode MK</th>
                    <th class="px-4 py-2.5 text-left text-xs font-bold text-amber-900 border border-amber-200">Nama Mata Kuliah</th>
                    <th class="px-3 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-14">T</th>
                    <th class="px-3 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-14">P</th>
                    <th class="px-3 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-16">SKS</th>
                    <th class="px-4 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-24">Kategori</th>
                    <th class="px-3 py-2.5 text-center text-xs font-bold text-amber-900 border border-amber-200 w-16">CPMK</th>
                    <th class="px-3 py-2.5 border border-amber-200 w-24"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mks as $mk)
                    @php
                        $kc = $katColors[$mk->kategori_mk] ?? ['bg'=>'#fff','badge'=>'bg-gray-100 text-gray-700'];
                        $rowBg = $loop->even ? '#fffbeb' : '#fff';
                    @endphp
                    <tr class="hover:bg-amber-50 transition-colors border-b border-amber-100" style="background:{{ $rowBg }}">
                        <td class="px-4 py-3 border border-amber-100">
                            <span class="font-mono font-bold text-blue-800 text-xs bg-blue-50 px-2 py-0.5 rounded"
                                  data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }}, {{ $mk->sks_total }} SKS)">{{ $mk->kode_mk }}</span>
                        </td>
                        <td class="px-4 py-3 border border-amber-100">
                            <a href="{{ route('kurikulum.mata-kuliah.show', [$kurikulum, $mk]) }}"
                               class="font-medium text-gray-800 hover:text-blue-700 hover:underline text-sm">{{ $mk->nama_mk }}</a>
                        </td>
                        <td class="px-3 py-3 text-center text-gray-600 text-xs border border-amber-100">{{ $mk->sks_teori }}</td>
                        <td class="px-3 py-3 text-center text-gray-600 text-xs border border-amber-100">{{ $mk->sks_praktikum }}</td>
                        <td class="px-3 py-3 text-center border border-amber-100">
                            <span class="font-bold text-gray-800 text-sm">{{ $mk->sks_total }}</span>
                        </td>
                        <td class="px-4 py-3 text-center border border-amber-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium {{ $kc['badge'] }}">{{ $mk->kategori_mk }}</span>
                        </td>
                        <td class="px-3 py-3 text-center border border-amber-100">
                            @if ($mk->cpmk_count > 0)
                                <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mk]) }}"
                                   class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 text-amber-800 text-xs font-bold hover:bg-amber-200 transition-colors"
                                   title="Lihat CPMK">{{ $mk->cpmk_count }}</a>
                            @else
                                <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mk]) }}"
                                   class="inline-flex items-center justify-center w-7 h-7 rounded-full border border-dashed border-gray-300 text-gray-400 text-xs hover:border-amber-300 hover:text-amber-600 transition-colors"
                                   title="Tambah CPMK">+</a>
                            @endif
                        </td>
                        <td class="px-3 py-3 border border-amber-100">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mk]) }}"
                                   class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="CPMK">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </a>
                                @if (!$kurikulum->isArsip())
                                    <a href="{{ route('kurikulum.mata-kuliah.edit', [$kurikulum, $mk]) }}"
                                       class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('kurikulum.mata-kuliah.destroy', [$kurikulum, $mk]) }}"
                                          onsubmit="return confirm('Hapus MK {{ $mk->kode_mk }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
