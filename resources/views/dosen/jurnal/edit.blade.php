@extends('layouts.app')

@section('title', 'Edit Jurnal Mengajar')
@section('header', 'Edit Jurnal Mengajar')

@section('breadcrumb')
    <a href="{{ route('dosen.jurnal.index') }}" class="hover:text-blue-600">Jurnal Mengajar</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('dosen.jurnal.show', $jurnal) }}" class="hover:text-blue-600">Detail</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('dosen.jurnal.update', $jurnal) }}" class="space-y-5">
            @csrf @method('PUT')

            {{-- Pilih Pertemuan RPS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Pertemuan RPS <span class="text-red-500">*</span>
                </label>
                <select name="id_rps_pertemuan" required
                        class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                               {{ $errors->has('id_rps_pertemuan') ? 'border-red-400' : 'border-gray-300' }}">
                    <option value="">— Pilih Pertemuan —</option>
                    @foreach ($rpsList as $rps)
                        @if ($rps->pertemuan->isNotEmpty())
                        <optgroup label="{{ $rps->mataKuliah->nama_mk ?? 'MK' }} — {{ $rps->semester->nama ?? '' }}">
                            @foreach ($rps->pertemuan->sortBy('minggu_ke') as $pt)
                                <option value="{{ $pt->id }}"
                                        {{ old('id_rps_pertemuan', $jurnal->id_rps_pertemuan) == $pt->id ? 'selected' : '' }}>
                                    Minggu ke-{{ $pt->minggu_ke }}
                                    @if($pt->materi_pembelajaran)
                                        — {{ Str::limit($pt->materi_pembelajaran, 50) }}
                                    @endif
                                </option>
                            @endforeach
                        </optgroup>
                        @endif
                    @endforeach
                </select>
                @error('id_rps_pertemuan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Tanggal Pelaksanaan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Tanggal Pelaksanaan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_pelaksanaan"
                       value="{{ old('tanggal_pelaksanaan', $jurnal->tanggal_pelaksanaan?->format('Y-m-d')) }}" required
                       class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                              {{ $errors->has('tanggal_pelaksanaan') ? 'border-red-400' : 'border-gray-300' }}">
                @error('tanggal_pelaksanaan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Jumlah Hadir --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Jumlah Mahasiswa Hadir <span class="text-red-500">*</span>
                </label>
                <input type="number" name="jumlah_hadir" min="0"
                       value="{{ old('jumlah_hadir', $jurnal->jumlah_hadir) }}" required
                       class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                              {{ $errors->has('jumlah_hadir') ? 'border-red-400' : 'border-gray-300' }}">
                @error('jumlah_hadir')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Realisasi Materi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Realisasi Materi <span class="text-red-500">*</span>
                </label>
                <textarea name="realisasi_materi" rows="4" required
                          class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                                 {{ $errors->has('realisasi_materi') ? 'border-red-400' : 'border-gray-300' }}">{{ old('realisasi_materi', $jurnal->realisasi_materi) }}</textarea>
                @error('realisasi_materi')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan (opsional)</label>
                <textarea name="catatan" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('catatan', $jurnal->catatan) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('dosen.jurnal.show', $jurnal) }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
