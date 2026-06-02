@extends('layouts.app')
@section('title', 'Edit CPL SN-Dikti')
@section('header', 'Edit CPL SN-Dikti')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.cpl-sndikti.index', $kurikulum) }}" class="hover:text-blue-600">CPL SN-Dikti</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $cplSndikti->kode }}</span>
@endsection

@section('content')
<form method="POST" action="{{ route('kurikulum.cpl-sndikti.update', [$kurikulum, $cplSndikti]) }}" class="max-w-2xl">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kode CPL SN-Dikti</label>
                <div class="flex items-center gap-2 border border-gray-200 bg-gray-50 rounded-lg px-3.5 py-2">
                    <span class="font-mono font-bold text-amber-800 text-sm">{{ $cplSndikti->kode }}</span>
                    <span class="text-xs text-gray-400 ml-auto">Tidak dapat diubah</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($kategoriOptions as $k)
                        <option value="{{ $k }}" {{ old('kategori', $cplSndikti->kategori) === $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi', $cplSndikti->deskripsi) }}</textarea>
            </div>

        </div>
        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">Perbarui</button>
            <a href="{{ route('kurikulum.cpl-sndikti.index', $kurikulum) }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">Batal</a>
        </div>
    </div>
</form>
@endsection
