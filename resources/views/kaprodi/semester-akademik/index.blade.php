@extends('layouts.app')

@section('title', 'Semester Akademik')
@section('header', 'Semester Akademik')

@section('breadcrumb')
    <span>Dashboard</span>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Semester Akademik</span>
@endsection

@section('header-actions')
    <a href="{{ route('kaprodi.semester-akademik.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
              px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Semester
    </a>
@endsection

@section('content')

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Semester</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($semesters as $semester)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-gray-800">
                        {{ $semester->nama }}
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">
                        {{ $semester->tahun_akademik ?: '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">
                        {{ $semester->tanggal_mulai?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">
                        {{ $semester->tanggal_selesai?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($semester->is_aktif)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            @unless ($semester->is_aktif)
                                <form method="POST" action="{{ route('kaprodi.semester-akademik.aktifkan', $semester) }}">
                                    @csrf
                                    <button type="submit"
                                            class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="Aktifkan">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endunless
                            <a href="{{ route('kaprodi.semester-akademik.edit', $semester) }}"
                               class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('kaprodi.semester-akademik.destroy', $semester) }}"
                                  onsubmit="return confirm('Hapus semester {{ $semester->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Belum ada semester akademik.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($semesters->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $semesters->links() }}
        </div>
    @endif
</div>

@endsection
