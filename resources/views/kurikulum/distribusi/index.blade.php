@extends('layouts.app')

@section('title', 'Distribusi Dokumen – ' . $kurikulum->kode)
@section('header', 'Distribusi Dokumen')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Distribusi Dokumen</span>
@endsection

@section('content')

{{-- Form distribusi baru --}}
<div class="bg-white rounded-xl border border-blue-100 shadow-sm mb-5">
    <div class="px-5 py-4 border-b border-blue-100">
        <h3 class="font-semibold text-blue-800 text-sm">Distribusikan Dokumen</h3>
    </div>
    <div class="p-5">
        <form method="POST" action="{{ route('kurikulum.distribusi.store', $kurikulum) }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_dokumen" value="{{ old('nama_dokumen') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: Dokumen Kurikulum SI-OBE">
                    @error('nama_dokumen') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Versi</label>
                    <input type="text" name="versi" value="{{ old('versi') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: v1.0">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Penerima <span class="text-red-500">*</span></label>
                <select name="id_penerima[]" multiple size="5"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role->label() }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Tahan Ctrl/Cmd untuk memilih lebih dari satu penerima.</p>
                @error('id_penerima') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="catatan" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Catatan distribusi...">{{ old('catatan') }}</textarea>
            </div>
            <div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                    Distribusikan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Log distribusi --}}
<div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
        <h3 class="font-semibold text-gray-700 text-sm">Riwayat Distribusi</h3>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-700 text-white">
                <th class="px-4 py-3 text-left text-xs font-bold">Dokumen</th>
                <th class="px-4 py-3 text-left text-xs font-bold">Versi</th>
                <th class="px-4 py-3 text-left text-xs font-bold">Pengirim</th>
                <th class="px-4 py-3 text-left text-xs font-bold">Penerima</th>
                <th class="px-4 py-3 text-left text-xs font-bold">Tanggal</th>
                <th class="px-4 py-3 text-left text-xs font-bold">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $log->nama_dokumen }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs font-mono">{{ $log->versi ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $log->pengirim?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $log->penerima?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->created_at?->format('d M Y H:i') }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs max-w-xs">{{ Str::limit($log->catatan, 60) ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada riwayat distribusi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
