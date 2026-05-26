@extends('layouts.app')

@section('title', 'Detail RPS')
@section('header', 'Detail RPS')

@section('breadcrumb')
    <a href="{{ route('dosen.rps.index') }}" class="hover:text-blue-600">RPS Saya</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Detail</span>
@endsection

@section('header-actions')
    @if ($rps->status === 'draft' && $rps->id_dosen_pengembang === auth()->id())
        <a href="{{ route('dosen.rps.edit', $rps) }}"
           class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('dosen.rps.submit', $rps) }}"
              onsubmit="return confirm('Ajukan RPS ini untuk ditinjau Kaprodi?')" class="inline">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Submit ke Kaprodi
            </button>
        </form>
    @elseif ($rps->id_dosen_pengembang !== auth()->id())
        {{-- Dosen pengampu (bukan pengembang) hanya bisa melihat --}}
        <span class="inline-flex items-center gap-1.5 text-xs text-gray-400 bg-gray-100 px-3 py-2 rounded-lg">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Mode Baca
        </span>
    @endif
@endsection

@section('content')

{{-- Info header RPS --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">{{ $rps->mataKuliah->nama_mk ?? '—' }}</h2>
            <p class="text-sm text-gray-500 font-mono mt-0.5">{{ $rps->mataKuliah->kode_mk ?? '' }}</p>
        </div>
        @php
            $badgeClass = match($rps->status) {
                'draft'    => 'bg-gray-100 text-gray-600',
                'review'   => 'bg-amber-100 text-amber-700',
                'disahkan' => 'bg-green-100 text-green-700',
                default    => 'bg-gray-100 text-gray-600',
            };
        @endphp
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClass }}">
            {{ ucfirst($rps->status) }}
        </span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Semester</p>
            <p class="font-medium text-gray-700">{{ $rps->semester->nama ?? '—' }}</p>
            <p class="text-xs text-gray-400">{{ $rps->semester->tahun_akademik ?? '' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Kode Dokumen</p>
            <p class="font-medium text-gray-700 font-mono text-xs">{{ $rps->kode_dokumen }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Tanggal Penyusunan</p>
            <p class="font-medium text-gray-700">{{ $rps->tanggal_penyusunan?->format('d M Y') ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">SKS</p>
            <p class="font-medium text-gray-700">
                {{ $rps->mataKuliah->sks_total ?? '—' }}
                <span class="text-xs text-gray-400">
                    ({{ $rps->mataKuliah->sks_teori ?? 0 }}T + {{ $rps->mataKuliah->sks_praktikum ?? 0 }}P)
                </span>
            </p>
        </div>
    </div>

    @if ($rps->status === 'disahkan' && $rps->disahkan_pada)
    <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="text-xs text-gray-500">
            Disahkan pada: <span class="font-medium text-gray-700">{{ $rps->disahkan_pada->format('d M Y H:i') }}</span>
            @if ($rps->kaprodiPengesah)
                oleh <span class="font-medium text-gray-700">{{ $rps->kaprodiPengesah->name }}</span>
            @endif
        </p>
    </div>
    @endif
</div>

{{-- Pertemuan --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">Daftar Pertemuan (16 Minggu)</h3>
        <a href="{{ route('dosen.rps.pertemuan.index', $rps) }}"
           class="text-xs text-emerald-600 hover:underline font-medium">Kelola Pertemuan →</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">Minggu</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Materi Pembelajaran</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($mingguList as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 text-center font-semibold text-gray-700">{{ $item['minggu_ke'] }}</td>
                    <td class="px-5 py-3.5 text-gray-700 max-w-xs">
                        @if ($item['data'])
                            <span class="line-clamp-2">{{ Str::limit($item['data']->materi_pembelajaran, 80) }}</span>
                        @else
                            <span class="text-gray-400 italic text-xs">Belum diisi</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-gray-600 text-xs">
                        {{ $item['data']?->metode_pembelajaran ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($item['data'])
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Terisi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                Kosong
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('dosen.rps.pertemuan.show', [$rps, $item['minggu_ke']]) }}"
                           class="text-xs text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
