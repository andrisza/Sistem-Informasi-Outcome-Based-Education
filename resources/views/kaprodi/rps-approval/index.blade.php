@extends('layouts.app')

@section('title', 'Persetujuan RPS')
@section('header', 'Persetujuan RPS')

@section('breadcrumb')
    <span>Dashboard</span>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Persetujuan RPS</span>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('kaprodi.rps-approval.index') }}"
          class="flex flex-wrap items-center gap-3 p-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari mata kuliah atau dosen..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="semester"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Semester</option>
            @foreach ($semesters as $sem)
                <option value="{{ $sem->id }}" {{ request('semester') == $sem->id ? 'selected' : '' }}>
                    {{ $sem->nama }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['search', 'semester']))
            <a href="{{ route('kaprodi.rps-approval.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen Pengembang</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Penyusunan</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($rpsList as $rps)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-gray-800">{{ $rps->mataKuliah->nama_mk ?? '—' }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $rps->mataKuliah->kode_mk ?? '' }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-gray-700">
                        {{ $rps->dosenPengembang->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">
                        {{ $rps->semester->nama ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-gray-600 text-xs">
                        {{ $rps->tanggal_penyusunan?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $statusColor = match($rps->status) {
                                'draft'    => 'bg-gray-100 text-gray-600',
                                'review'   => 'bg-amber-100 text-amber-700',
                                'disahkan' => 'bg-green-100 text-green-700',
                                default    => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                            {{ ucfirst($rps->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('kaprodi.rps-approval.show', $rps) }}"
                           class="inline-flex items-center gap-1.5 text-xs bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9.75 0a9.75 9.75 0 0119.5 0 9.75 9.75 0 01-19.5 0z"/>
                            </svg>
                            Review
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Tidak ada RPS yang menunggu persetujuan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($rpsList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $rpsList->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
