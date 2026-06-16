@extends('layouts.app')

@section('title', 'Edit Arsip Rapat')
@section('header', 'Edit Arsip Rapat')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}" class="hover:text-blue-600">Arsip Rapat</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.arsip-rapat.update', [$kurikulum, $arsipRapat]) }}"
      enctype="multipart/form-data" class="max-w-2xl">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-5">

            {{-- Judul --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">
                    Judul Rapat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul_rapat" required
                       value="{{ old('judul_rapat', $arsipRapat->judul_rapat) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('judul_rapat') border-red-400 @enderror">
                @error('judul_rapat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tanggal & Tempat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        Tanggal Rapat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_rapat" required
                           value="{{ old('tanggal_rapat', $arsipRapat->tanggal_rapat?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_rapat') border-red-400 @enderror">
                    @error('tanggal_rapat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Tempat</label>
                    <input type="text" name="tempat"
                           value="{{ old('tempat', $arsipRapat->tempat) }}"
                           placeholder="contoh: Ruang Rapat / Zoom"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Catatan Rapat</label>
                <textarea name="notulen" rows="5"
                          placeholder="Agenda, notulen, kesimpulan, tindak lanjut..."
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y">{{ old('notulen', $arsipRapat->notulen) }}</textarea>
            </div>

            {{-- File yang sudah ada --}}
            @php $existingFiles = $arsipRapat->file_lampiran ?? []; @endphp
            @if ($existingFiles)
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">File Terlampir</label>
                <div class="space-y-2">
                    @foreach ($existingFiles as $i => $file)
                        @php
                            $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            $isImg = in_array($ext, ['png','jpg','jpeg','heic','heif']);
                            $isPdf = $ext === 'pdf';
                        @endphp
                        <div class="flex items-center gap-3 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                            @if ($isImg)
                                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                </svg>
                            @elseif ($isPdf)
                                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            @endif
                            <span class="text-xs text-gray-700 flex-1 truncate">{{ $file['name'] }}</span>
                            <a href="{{ Storage::url($file['path']) }}" target="_blank"
                               class="text-[10px] text-blue-600 hover:underline shrink-0">Buka</a>
                            <label class="flex items-center gap-1.5 text-[10px] text-red-500 cursor-pointer shrink-0">
                                <input type="checkbox" name="delete_files[]" value="{{ $i }}"
                                       class="accent-red-500 w-3.5 h-3.5">
                                Hapus
                            </label>
                        </div>
                    @endforeach
                </div>
                <p class="text-[10px] text-gray-400 mt-1.5">Centang "Hapus" pada file yang ingin dihapus, lalu klik Perbarui.</p>
            </div>
            @endif

            {{-- Tambah file baru --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">
                    {{ $existingFiles ? 'Tambah Bukti Rapat' : 'Bukti Rapat' }}
                </label>
                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition-colors group">
                    <div class="flex flex-col items-center gap-1 text-center pointer-events-none">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-500 group-hover:text-blue-600">Klik untuk pilih file</span>
                    </div>
                    <input type="file" name="bukti_rapat[]" multiple class="hidden"
                           accept=".pdf,.docx,.png,.jpg,.jpeg,.heic,.heif"
                           onchange="previewFiles(this)">
                </label>
                @error('bukti_rapat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('bukti_rapat.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <ul id="file-preview" class="mt-2 space-y-1"></ul>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                Perbarui
            </button>
            <a href="{{ route('kurikulum.arsip-rapat.show', [$kurikulum, $arsipRapat]) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function previewFiles(input) {
    const list = document.getElementById('file-preview');
    list.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const li = document.createElement('li');
        li.className = 'flex items-center gap-2 text-xs text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2';
        li.innerHTML = `<svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg> ${file.name} <span class="ml-auto text-gray-400">${(file.size/1024/1024).toFixed(2)} MB</span>`;
        list.appendChild(li);
    });
}
</script>
@endpush
