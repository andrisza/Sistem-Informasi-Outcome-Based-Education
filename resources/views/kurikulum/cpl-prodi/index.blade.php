@extends('layouts.app')

@section('title', 'CPL Prodi – ' . $kurikulum->kode)
@section('header', 'CPL Prodi')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">CPL Prodi</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.cpl-prodi.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah CPL
        </a>
    @endif
@endsection

@section('content')

{{-- Info banner --}}
<div class="flex items-center gap-3 bg-violet-50 border border-violet-200 rounded-xl px-4 py-3 mb-4 text-xs text-violet-800">
    <svg class="w-4 h-4 text-violet-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Capaian Pembelajaran Lulusan (CPL) Prodi adalah kemampuan spesifik yang harus dikuasai lulusan, mengacu pada CPL SN-Dikti.</span>
    <span class="ml-auto shrink-0 font-semibold">{{ $cplList->count() }} CPL terdaftar</span>
</div>

@php
    $katOrder = ['Sikap', 'Keterampilan Umum', 'Keterampilan Khusus', 'Pengetahuan'];
    $katLabels = [
        'Sikap'               => 'A. SIKAP',
        'Keterampilan Umum'   => 'B. KETERAMPILAN UMUM (KU)',
        'Keterampilan Khusus' => 'C. KETERAMPILAN KHUSUS (KK)',
        'Pengetahuan'         => 'D. PENGETAHUAN (P)',
    ];
    $katColors = [
        'Sikap'               => ['header' => '#3B82F6', 'badgeBg' => '#DBEAFE', 'badgeTxt' => '#1e40af', 'rowA' => '#eff6ff', 'rowB' => '#fff'],
        'Keterampilan Umum'   => ['header' => '#10B981', 'badgeBg' => '#D1FAE5', 'badgeTxt' => '#065f46', 'rowA' => '#f0fdf4', 'rowB' => '#fff'],
        'Keterampilan Khusus' => ['header' => '#7C3AED', 'badgeBg' => '#EDE9FE', 'badgeTxt' => '#4c1d95', 'rowA' => '#f5f3ff', 'rowB' => '#fff'],
        'Pengetahuan'         => ['header' => '#D97706', 'badgeBg' => '#FEF3C7', 'badgeTxt' => '#78350f', 'rowA' => '#fffbeb', 'rowB' => '#fff'],
    ];
    $grouped = $cplList->groupBy('kategori');
    $cols = $kurikulum->isArsip() ? 4 : 5;
@endphp

@if ($cplList->isEmpty())
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm px-5 py-14 text-center text-gray-400 text-sm">
        <div class="flex flex-col items-center gap-2">
            <svg class="w-10 h-10 text-violet-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Belum ada CPL Prodi.</span>
            @if (!$kurikulum->isArsip())
                <a href="{{ route('kurikulum.cpl-prodi.create', $kurikulum) }}" class="text-violet-600 hover:underline font-medium">Tambah CPL pertama →</a>
            @endif
        </div>
    </div>
@else

@include('layouts._search', ['target'=>'cpl-table-wrap','placeholder'=>'Cari CPL (kode atau deskripsi)...','mode'=>'hide','rowSelector'=>'tbody tr'])
<div id="cpl-table-wrap" class="rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr style="background:#F59E0B">
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-10">No</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-28">Kode CPL</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300">Deskripsi CPL</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300 w-44">Referensi</th>
                    @if (!$kurikulum->isArsip())
                        <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-20">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($katOrder as $kat)
                    @php $items = $grouped->get($kat, collect()); @endphp
                    @if ($items->isEmpty()) @continue @endif
                    @php $cc = $katColors[$kat] ?? ['header'=>'#6B7280','badgeBg'=>'#F3F4F6','badgeTxt'=>'#374151','rowA'=>'#f9fafb','rowB'=>'#fff']; @endphp

                    {{-- Kategori header --}}
                    <tr>
                        <td colspan="{{ $cols }}"
                            class="px-4 py-2 text-xs font-bold uppercase tracking-wider border border-gray-200"
                            style="background:{{ $cc['header'] }};color:#fff">
                            {{ $katLabels[$kat] ?? strtoupper($kat) }}
                            <span class="ml-2 font-normal opacity-80">({{ $items->count() }} CPL)</span>
                        </td>
                    </tr>

                    @foreach ($items as $i => $cpl)
                        <tr class="hover:opacity-90 transition-opacity border-b border-gray-100"
                            style="background:{{ $i % 2 === 0 ? $cc['rowA'] : $cc['rowB'] }}">
                            <td class="px-3 py-3 text-center text-xs text-gray-500 border border-gray-100 font-medium">
                                {{ $i + 1 }}
                            </td>
                            <td class="px-4 py-3 text-center border border-gray-100">
                                <span class="font-mono font-bold text-xs px-2 py-0.5 rounded cursor-help"
                                      style="background:{{ $cc['badgeBg'] }};color:{{ $cc['badgeTxt'] }}"
                                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                      data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-sm border border-gray-100 leading-relaxed">
                                {{ $cpl->deskripsi }}
                            </td>
                            <td class="px-4 py-3 border border-gray-100 text-xs text-gray-500 leading-snug">
                                {{ $cpl->referensi ?: '—' }}
                            </td>
                            @if (!$kurikulum->isArsip())
                                <td class="px-3 py-3 border border-gray-100">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('kurikulum.cpl-prodi.edit', [$kurikulum, $cpl]) }}"
                                           class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('kurikulum.cpl-prodi.destroy', [$kurikulum, $cpl]) }}"
                                              onsubmit="return confirm('Hapus CPL {{ $cpl->kode_cpl }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach

                {{-- Kategori lain yang tidak masuk katOrder --}}
                @foreach ($grouped as $kat => $items)
                    @if (in_array($kat, $katOrder)) @continue @endif
                    <tr>
                        <td colspan="{{ $cols }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider border border-gray-200" style="background:#6B7280;color:#fff">
                            {{ strtoupper($kat) }}
                        </td>
                    </tr>
                    @foreach ($items as $i => $cpl)
                        <tr class="hover:bg-gray-50 border-b border-gray-100" style="background:{{ $i % 2 === 0 ? '#f9fafb' : '#fff' }}">
                            <td class="px-3 py-3 text-center text-xs text-gray-500 border border-gray-100">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 text-center border border-gray-100">
                                <span class="font-mono font-bold text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ $cpl->kode_cpl }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-sm border border-gray-100">{{ $cpl->deskripsi }}</td>
                            <td class="px-4 py-3 border border-gray-100 text-xs text-gray-500">{{ $cpl->referensi ?: '—' }}</td>
                            @if (!$kurikulum->isArsip())
                                <td class="px-3 py-3 border border-gray-100">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('kurikulum.cpl-prodi.edit', [$kurikulum, $cpl]) }}" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('kurikulum.cpl-prodi.destroy', [$kurikulum, $cpl]) }}" onsubmit="return confirm('Hapus CPL {{ $cpl->kode_cpl }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
