@extends('layouts.app')

@section('title', 'Tim Kurikulum')
@section('header', 'Tim Penyusun Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tim</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.tim.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Anggota
        </a>
    @endif
@endsection

@section('content')

@forelse ($periodeList as $periode)
<div class="mb-6">
    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-1">{{ $periode->nama_periode }}</h3>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor SK</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($periode->timKurikulum as $tim)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-gray-800">{{ $tim->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $tim->user->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 text-sm">{{ $tim->jabatan_tim }}</td>
                        <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $tim->sk_nomor ?: '–' }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">
                                @if (!$kurikulum->isArsip())
                                    <form method="POST" action="{{ route('kurikulum.tim.destroy', [$kurikulum, $tim]) }}"
                                          onsubmit="return confirm('Hapus anggota {{ $tim->user->name }}?')">
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
                        <td colspan="4" class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada anggota tim di periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@empty
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-12 text-center text-gray-400 text-sm">
        Belum ada periode penyusunan. <a href="{{ route('kurikulum.periode.create', $kurikulum) }}" class="text-blue-600 hover:underline ml-1">Tambah periode dahulu</a>.
    </div>
@endforelse

@endsection
