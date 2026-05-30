@extends('layouts.app')

@section('title', 'Upload Materi')
@section('header', 'Upload Materi Pembelajaran')

@section('breadcrumb')
    <a href="{{ route('dosen.materi.index') }}" class="hover:text-blue-600">Repositori Materi</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Upload</span>
@endsection

@section('content')

{{-- enctype="multipart/form-data" wajib untuk upload file --}}
<form method="POST"
      action="{{ route('dosen.materi.store') }}"
      enctype="multipart/form-data"
      class="max-w-2xl"
      id="upload-form">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-5">

            {{-- Baris: Mata Kuliah + Semester --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <select name="id_mk"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_mk') border-red-400 @enderror">
                        <option value="">-- Pilih MK --</option>
                        @foreach ($pengampuan as $p)
                            <option value="{{ $p->id_mk }}"
                                    {{ old('id_mk') == $p->id_mk ? 'selected' : '' }}>
                                {{ $p->mataKuliah->kode_mk }} – {{ $p->mataKuliah->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_mk')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="id_semester"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_semester') border-red-400 @enderror">
                        <option value="">-- Pilih Semester --</option>
                        @foreach ($semesters as $sem)
                            {{-- Field yang benar: $sem->nama (bukan nama_semester) --}}
                            <option value="{{ $sem->id }}"
                                    {{ old('id_semester') == $sem->id ? 'selected' : '' }}>
                                {{ $sem->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_semester')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama / Judul Materi --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Nama / Judul Materi <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama_file"
                       value="{{ old('nama_file') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_file') border-red-400 @enderror"
                       placeholder="cth: Modul 1 – Pengantar OBE">
                @error('nama_file')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis File --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Jenis Materi <span class="text-red-500">*</span>
                </label>
                <select name="jenis_file"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_file') border-red-400 @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($jenisOptions as $j)
                        <option value="{{ $j }}"
                                {{ old('jenis_file') == $j ? 'selected' : '' }}>
                            {{ ucfirst($j) }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_file')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload File — zona drag & drop --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    File <span class="text-red-500">*</span>
                </label>

                {{-- Drop zone --}}
                <label for="file-input"
                       id="drop-zone"
                       class="group relative flex flex-col items-center justify-center w-full min-h-36 border-2 border-dashed rounded-xl cursor-pointer transition-colors duration-150
                              @error('file') border-red-400 bg-red-50 @else border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50/40 @enderror">

                    {{-- Ikon upload --}}
                    <div id="upload-icon" class="flex flex-col items-center gap-1 text-center pointer-events-none">
                        <svg class="w-9 h-9 text-gray-300 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <p class="text-sm text-gray-500 group-hover:text-blue-600">
                            <span class="font-semibold">Klik untuk pilih file</span>
                            <span class="hidden sm:inline"> atau seret ke sini</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            PDF, Word, PowerPoint, Excel, Gambar, Video, ZIP
                            <span class="mx-1">·</span> Maks. 50 MB
                        </p>
                    </div>

                    {{-- Preview file yang dipilih (tersembunyi sampai ada file) --}}
                    <div id="file-preview" class="hidden flex-col items-center gap-2 text-center pointer-events-none">
                        <div id="file-icon-wrap" class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p id="file-name" class="text-sm font-semibold text-gray-800 max-w-xs truncate"></p>
                            <p id="file-size" class="text-xs text-gray-500 mt-0.5"></p>
                        </div>
                        <p class="text-xs text-blue-500 underline">Ganti file</p>
                    </div>
                </label>

                {{-- Input file tersembunyi —  accept menentukan filter di browser (tidak menggantikan validasi server) --}}
                <input type="file"
                       id="file-input"
                       name="file"
                       class="sr-only"
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar,.7z,.mp4,.avi,.mov,.mkv,.jpg,.jpeg,.png,.gif,.svg">

                @error('file')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror

                {{-- Info tipe file yang diterima --}}
                <p class="text-[11px] text-gray-400 mt-1.5 leading-relaxed">
                    Tipe diterima: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR, 7Z, MP4, AVI, MOV, MKV, JPG, PNG, GIF, SVG
                </p>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi"
                          rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Keterangan singkat tentang materi ini...">{{ old('deskripsi') }}</textarea>
            </div>

        </div>

        {{-- Footer form --}}
        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    id="submit-btn"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                <svg id="submit-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                </svg>
                <span id="submit-label">Upload Materi</span>
            </button>
            <a href="{{ route('dosen.materi.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
(function () {
    const input    = document.getElementById('file-input');
    const dropZone = document.getElementById('drop-zone');
    const icon     = document.getElementById('upload-icon');
    const preview  = document.getElementById('file-preview');
    const nameEl   = document.getElementById('file-name');
    const sizeEl   = document.getElementById('file-size');
    const form     = document.getElementById('upload-form');
    const submitBtn= document.getElementById('submit-btn');
    const spinner  = document.getElementById('submit-spinner');
    const label    = document.getElementById('submit-label');

    const MAX_BYTES = 50 * 1024 * 1024; // 50 MB

    // ── Format bytes menjadi string yang mudah dibaca ──────────────────────
    function formatBytes(bytes) {
        if (bytes < 1024)       return bytes + ' B';
        if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // ── Tampilkan preview setelah file dipilih ─────────────────────────────
    function showPreview(file) {
        if (!file) return;

        nameEl.textContent = file.name;
        sizeEl.textContent = formatBytes(file.size);

        icon.classList.add('hidden');
        preview.classList.remove('hidden');
        preview.classList.add('flex');

        // Warna drop-zone berubah → biru jika valid, merah jika terlalu besar
        dropZone.classList.remove('border-gray-300', 'border-red-400', 'bg-red-50', 'border-blue-500');
        if (file.size > MAX_BYTES) {
            dropZone.classList.add('border-red-400', 'bg-red-50');
            sizeEl.classList.add('text-red-500');
            sizeEl.textContent += ' — melebihi batas 50 MB';
        } else {
            dropZone.classList.add('border-blue-500', 'bg-blue-50/40');
            sizeEl.classList.remove('text-red-500');
        }
    }

    // ── Reset ke tampilan awal ─────────────────────────────────────────────
    function resetPreview() {
        icon.classList.remove('hidden');
        preview.classList.add('hidden');
        preview.classList.remove('flex');
        dropZone.classList.remove('border-blue-500', 'bg-blue-50/40', 'border-red-400', 'bg-red-50');
        dropZone.classList.add('border-gray-300', 'bg-gray-50');
    }

    // ── Event: file dipilih via dialog ─────────────────────────────────────
    input.addEventListener('change', function () {
        const file = this.files[0];
        file ? showPreview(file) : resetPreview();
    });

    // ── Drag & Drop ────────────────────────────────────────────────────────
    ['dragenter', 'dragover'].forEach(evt => {
        dropZone.addEventListener(evt, function (e) {
            e.preventDefault();
            dropZone.classList.add('border-blue-400', 'bg-blue-50/60');
        });
    });

    ['dragleave', 'dragend'].forEach(evt => {
        dropZone.addEventListener(evt, function () {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50/60');
        });
    });

    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50/60');

        const file = e.dataTransfer?.files[0];
        if (!file) return;

        // Masukkan file ke input element via DataTransfer
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showPreview(file);
    });

    // ── Submit: tampilkan spinner & disable tombol saat upload berjalan ────
    form.addEventListener('submit', function (e) {
        const file = input.files[0];

        // Validasi ukuran di sisi client sebelum kirim
        if (file && file.size > MAX_BYTES) {
            e.preventDefault();
            alert('Ukuran file melebihi batas maksimal 50 MB.');
            return;
        }

        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
        label.textContent = 'Mengunggah…';
    });
})();
</script>
@endpush
