@extends('layouts.app')

@section('title', 'Kurikulum')
@section('header', 'Daftar Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Kurikulum</span>
@endsection

@section('header-actions')
    <a href="{{ route('kurikulum.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kurikulum
    </a>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('kurikulum.index') }}" class="flex flex-wrap items-center gap-3 p-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama, kode, program studi..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="draft"  {{ request('status') === 'draft'  ? 'selected' : '' }}>Draft</option>
            <option value="aktif"  {{ request('status') === 'aktif'  ? 'selected' : '' }}>Aktif</option>
            <option value="arsip"  {{ request('status') === 'arsip'  ? 'selected' : '' }}>Arsip</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['search','status']))
            <a href="{{ route('kurikulum.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kurikulum</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenjang</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Komponen</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($kurikulumList as $k)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-gray-800">{{ $k->nama_kurikulum }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $k->kode }} · {{ $k->program_studi }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $jColor = match($k->jenjang) {
                                'S1' => 'bg-blue-100 text-blue-700',
                                'S2' => 'bg-violet-100 text-violet-700',
                                'S3' => 'bg-purple-100 text-purple-700',
                                'D4' => 'bg-emerald-100 text-emerald-700',
                                'D3' => 'bg-teal-100 text-teal-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jColor }}">
                            {{ $k->jenjang }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600 text-xs">
                        {{ $k->tahun_mulai }}{{ $k->tahun_selesai ? ' – ' . $k->tahun_selesai : '' }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3 text-xs text-gray-500">
                            <span><strong class="text-gray-700">{{ $k->pl_count }}</strong> PL</span>
                            <span><strong class="text-gray-700">{{ $k->cpl_prodi_count }}</strong> CPL</span>
                            <span><strong class="text-gray-700">{{ $k->mata_kuliah_count }}</strong> MK</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        @php
                            $sColor = match($k->status) {
                                'aktif' => 'bg-green-100 text-green-700',
                                'arsip' => 'bg-gray-100 text-gray-500',
                                'draft' => 'bg-amber-100 text-amber-700',
                                default => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sColor }}">
                            {{ ucfirst($k->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('kurikulum.show', $k) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9.75 0a9.75 9.75 0 0119.5 0 9.75 9.75 0 01-19.5 0z"/>
                                </svg>
                            </a>
                            @if (!$k->isArsip())
                                <a href="{{ route('kurikulum.edit', $k) }}"
                                   class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            @endif
                            @if (auth()->user()->isKaprodi())
                                @if ($k->status === 'aktif')
                                    {{-- Aktif → bisa diarsipkan --}}
                                    <form method="POST" action="{{ route('kurikulum.arsip', $k) }}"
                                          onsubmit="return confirm('Arsipkan kurikulum {{ $k->kode }}?\n\nSetelah diarsipkan, semua data menjadi read-only dan tidak dapat diedit.')">
                                        @csrf
                                        <button type="submit"
                                                title="Arsipkan kurikulum ini"
                                                class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/>
                                            </svg>
                                        </button>
                                    </form>
                                @elseif ($k->status === 'arsip')
                                    {{-- Arsip → bisa diaktifkan kembali --}}
                                    <form method="POST" action="{{ route('kurikulum.aktifkan', $k) }}"
                                          onsubmit="return confirm('Aktifkan kembali kurikulum {{ $k->kode }}?\n\nKurikulum akan dapat diedit kembali.')">
                                        @csrf
                                        <button type="submit"
                                                title="Aktifkan kembali kurikulum ini"
                                                class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                @elseif ($k->status === 'draft')
                                    {{-- Draft → harus diaktifkan dulu; tampilkan tombol aktifkan --}}
                                    <form method="POST" action="{{ route('kurikulum.aktifkan', $k) }}"
                                          onsubmit="return confirm('Aktifkan kurikulum {{ $k->kode }}?\n\nKurikulum akan berlaku aktif dan siap digunakan.')">
                                        @csrf
                                        <button type="submit"
                                                title="Aktifkan kurikulum (dari Draft → Aktif)"
                                                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @endif
                            @if ($k->isDraft())
                                <form method="POST" action="{{ route('kurikulum.destroy', $k) }}"
                                      onsubmit="return confirm('Hapus kurikulum {{ $k->nama_kurikulum }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
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
                        Tidak ada kurikulum ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($kurikulumList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $kurikulumList->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
