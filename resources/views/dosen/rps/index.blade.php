@extends('layouts.app')

@section('title', 'RPS Saya')
@section('header', 'Rencana Pembelajaran Semester')

@section('breadcrumb')
    <a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">RPS Saya</span>
@endsection

@section('header-actions')
    <a href="{{ route('dosen.rps.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Buat RPS
    </a>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('dosen.rps.index') }}"
          class="flex flex-wrap items-center gap-3 p-4">
        <select name="status"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Status</option>
            <option value="draft"    {{ request('status') === 'draft'    ? 'selected' : '' }}>Draft</option>
            <option value="review"   {{ request('status') === 'review'   ? 'selected' : '' }}>Review</option>
            <option value="disahkan" {{ request('status') === 'disahkan' ? 'selected' : '' }}>Disahkan</option>
        </select>
        <select name="semester"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Semester</option>
            @foreach ($semesters as $sem)
                <option value="{{ $sem->id }}" {{ request('semester') == $sem->id ? 'selected' : '' }}>
                    {{ $sem->nama }} — {{ $sem->tahun_akademik }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['status','semester']))
            <a href="{{ route('dosen.rps.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Dokumen</th>
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
                <td class="px-5 py-3.5 text-gray-600 text-xs">
                    {{ $rps->semester->nama ?? '—' }}<br>
                    <span class="text-gray-400">{{ $rps->semester->tahun_akademik ?? '' }}</span>
                </td>
                <td class="px-5 py-3.5 font-mono text-xs text-gray-600">{{ $rps->kode_dokumen }}</td>
                <td class="px-5 py-3.5 text-gray-600 text-xs">
                    {{ $rps->tanggal_penyusunan?->format('d M Y') ?? '—' }}
                </td>
                <td class="px-5 py-3.5">
                    @php
                        $badgeClass = match($rps->status) {
                            'draft'    => 'bg-gray-100 text-gray-600',
                            'review'   => 'bg-amber-100 text-amber-700',
                            'disahkan' => 'bg-green-100 text-green-700',
                            default    => 'bg-gray-100 text-gray-600',
                        };
                        $badgeLabel = match($rps->status) {
                            'draft'    => 'Draft',
                            'review'   => 'Review',
                            'disahkan' => 'Disahkan',
                            default    => $rps->status,
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                        {{ $badgeLabel }}
                    </span>
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('dosen.rps.show', $rps) }}"
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9.75 0a9.75 9.75 0 0119.5 0 9.75 9.75 0 01-19.5 0z"/>
                            </svg>
                        </a>
                        @if ($rps->status !== 'disahkan')
                        <a href="{{ route('dosen.rps.edit', $rps) }}"
                           class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @endif
                        @if ($rps->status === 'draft')
                        <form method="POST" action="{{ route('dosen.rps.submit', $rps) }}"
                              onsubmit="return confirm('Ajukan RPS ini untuk ditinjau Kaprodi?')">
                            @csrf
                            <button type="submit"
                                    class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Submit ke Kaprodi">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('dosen.rps.destroy', $rps) }}"
                              onsubmit="return confirm('Hapus RPS ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                    Belum ada RPS yang dibuat.
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
