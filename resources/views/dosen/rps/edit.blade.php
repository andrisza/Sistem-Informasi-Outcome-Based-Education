@extends('layouts.app')
@section('title', 'Edit RPS — ' . ($rps->mataKuliah->nama_mk ?? ''))
@section('header', 'Penyusunan RPS')

@section('breadcrumb')
    <a href="{{ route('dosen.rps.index') }}" class="hover:text-blue-600">RPS Saya</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700 font-medium">{{ $rps->mataKuliah->kode_mk ?? '' }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('dosen.rps.print', $rps) }}" target="_blank"
       class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak RPS
    </a>
    <a href="{{ route('dosen.rps.show', $rps) }}"
       class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        Lihat Detail
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('dosen.rps.update', $rps) }}" id="rps-form">
@csrf @method('PUT')

{{-- ═══ INFO MK (read-only) ═══════════════════════════════════════════════ --}}
<div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 mb-5">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-xs text-amber-600 font-medium">Mata Kuliah</p>
            <p class="font-bold text-gray-800">{{ $mk->nama_mk }}</p>
        </div>
        <div>
            <p class="text-xs text-amber-600 font-medium">Kode MK</p>
            <p class="font-mono font-bold text-gray-800">{{ $mk->kode_mk }}</p>
        </div>
        <div>
            <p class="text-xs text-amber-600 font-medium">SKS</p>
            <p class="font-bold text-gray-800">
                {{ ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0) }} SKS
                <span class="text-xs text-gray-500">(T={{ $mk->sks_teori ?? 0 }} P={{ $mk->sks_praktikum ?? 0 }})</span>
            </p>
        </div>
        <div>
            <p class="text-xs text-amber-600 font-medium">Semester ke-</p>
            <p class="font-bold text-gray-800">{{ $mk->semester ?? '—' }}</p>
        </div>
    </div>
</div>

{{-- ═══ SECTION 1: Header Dokumen ════════════════════════════════════════ --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">1</span>
        <h3 class="font-semibold text-gray-800 text-sm">Identitas Dokumen</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Perguruan Tinggi</label>
            <input type="text" name="nama_perguruan_tinggi"
                   value="{{ old('nama_perguruan_tinggi', $rps->nama_perguruan_tinggi) }}"
                   placeholder="Universitas …"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Fakultas</label>
            <input type="text" name="nama_fakultas"
                   value="{{ old('nama_fakultas', $rps->nama_fakultas) }}"
                   placeholder="Fakultas …"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Kode Dokumen</label>
            <p class="w-full border border-gray-100 bg-gray-50 rounded-lg px-3 py-2 text-sm font-mono text-gray-700">{{ $rps->kode_dokumen }}</p>
            <p class="text-[11px] text-gray-400 mt-0.5">Kode dokumen digenerate otomatis</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Penyusunan <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_penyusunan" required
                   value="{{ old('tanggal_penyusunan', $rps->tanggal_penyusunan?->format('Y-m-d')) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('tanggal_penyusunan') ? 'border-red-400' : '' }}">
            @error('tanggal_penyusunan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">SKS Teori <span class="text-red-500">*</span></label>
            <input type="number" name="sks_teori" min="0" max="20" required
                   value="{{ old('sks_teori', $rps->sks_teori ?? $mk->sks_teori) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('sks_teori') ? 'border-red-400' : '' }}">
            @error('sks_teori')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">SKS Praktikum <span class="text-red-500">*</span></label>
            <input type="number" name="sks_praktikum" min="0" max="20" required
                   value="{{ old('sks_praktikum', $rps->sks_praktikum ?? $mk->sks_praktikum) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('sks_praktikum') ? 'border-red-400' : '' }}">
            @error('sks_praktikum')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            <p class="text-[11px] text-gray-400 mt-0.5">Akan diperbarui ke MK setelah RPS disahkan</p>
        </div>
    </div>
</div>

{{-- ═══ SECTION 2: Pengesahan ═════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">2</span>
        <h3 class="font-semibold text-gray-800 text-sm">Pengesahan</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Dosen Pengembang RPS</label>
            <input type="text" value="{{ $rps->dosenPengembang?->name ?? auth()->user()->name }}" readonly
                   class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-500 cursor-not-allowed">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Koordinator BK <span class="text-gray-400">(jika ada)</span></label>
            <select name="id_koordinator_bk"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="">— Tidak Ada —</option>
                @foreach ($dosenList as $d)
                    <option value="{{ $d->id }}" {{ old('id_koordinator_bk', $rps->id_koordinator_bk) == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Ka Prodi Pengesah</label>
            <select name="id_kaprodi_pengesah"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="">— Belum Ditentukan —</option>
                @foreach ($kaprodiList as $kp)
                    <option value="{{ $kp->id }}" {{ old('id_kaprodi_pengesah', $rps->id_kaprodi_pengesah) == $kp->id ? 'selected' : '' }}>
                        {{ $kp->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ═══ SECTION 3: Capaian Pembelajaran (CPL, CPMK, Sub-CPMK) ═══════════ --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">3</span>
        <h3 class="font-semibold text-gray-800 text-sm">Capaian Pembelajaran</h3>
        <span class="text-xs text-gray-400">(ditarik otomatis dari data kurikulum)</span>
    </div>
    <div class="p-5 space-y-5">
        {{-- CPL Prodi --}}
        <div>
            <p class="text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">CPL-PRODI yang Dibebankan pada MK</p>
            @forelse ($mk->cplProdi ?? [] as $cpl)
                <div class="flex items-start gap-2 text-sm mb-1.5">
                    <span class="font-mono font-bold text-amber-700 shrink-0 w-16 text-xs">{{ $cpl->kode_cpl }}</span>
                    <span class="text-gray-600 text-xs leading-snug">{{ $cpl->deskripsi }}</span>
                </div>
            @empty
                <p class="text-xs text-gray-400 italic">Belum ada CPL Prodi yang dipetakan ke MK ini.</p>
            @endforelse
        </div>

        {{-- CPMK --}}
        <div>
            <p class="text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Capaian Pembelajaran Mata Kuliah (CPMK)</p>
            @forelse ($cpmkList as $cpmk)
                <div class="flex items-start gap-2 text-sm mb-1.5">
                    <span class="font-mono font-bold text-blue-700 shrink-0 w-20 text-xs">{{ $cpmk->kode_cpmk }}</span>
                    <span class="text-gray-600 text-xs leading-snug">{{ $cpmk->deskripsi }}</span>
                </div>
            @empty
                <p class="text-xs text-gray-400 italic">Belum ada CPMK untuk MK ini.</p>
            @endforelse
        </div>

        {{-- Sub-CPMK --}}
        <div>
            <p class="text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Kemampuan Akhir Tiap Tahapan Belajar (Sub-CPMK)</p>
            @forelse ($subCpmkAll as $sc)
                <div class="flex items-start gap-2 text-sm mb-1.5">
                    <span class="font-mono font-bold text-green-700 shrink-0 w-24 text-xs">{{ $sc->kode_sub_cpmk }}</span>
                    <span class="text-gray-600 text-xs leading-snug">{{ $sc->deskripsi }}</span>
                    @if($sc->bobot)<span class="ml-auto shrink-0 text-xs text-gray-400">{{ $sc->bobot }}%</span>@endif
                </div>
            @empty
                <p class="text-xs text-gray-400 italic">Belum ada Sub-CPMK.</p>
            @endforelse
        </div>

        {{-- Korelasi CPMK x Sub-CPMK --}}
        <div>
            <p class="text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Korelasi CPMK terhadap Sub-CPMK</p>
            @if ($cpmkList->isNotEmpty() && $subCpmkAll->isNotEmpty())
            <p class="text-[11px] text-gray-400 mb-2">Pilih satu CPMK untuk setiap kolom Sub-CPMK. Setiap Sub-CPMK hanya dapat berelasi dengan satu CPMK.</p>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="border-collapse text-xs w-full">
                    <thead>
                        <tr>
                            <th class="border border-gray-200 bg-gray-50 px-2 py-1.5 text-left text-[10px] font-bold text-gray-500 sticky left-0 z-10">CPMK \ Sub-CPMK</th>
                            @foreach ($subCpmkAll as $sc)
                                <th class="border border-gray-200 bg-green-50 px-2 py-1.5 text-center text-[10px] font-bold text-green-700 font-mono">{{ $sc->kode_sub_cpmk }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cpmkList as $cpmk)
                        <tr>
                            <th class="border border-gray-200 bg-blue-50 px-2 py-1.5 text-left text-[10px] font-bold text-blue-700 font-mono sticky left-0 z-10">{{ $cpmk->kode_cpmk }}</th>
                            @foreach ($subCpmkAll as $sc)
                                <td class="border border-gray-200 text-center px-2 py-1.5">
                                    <input type="radio" name="sub_cpmk_cpmk[{{ $sc->id }}]" value="{{ $cpmk->id }}"
                                           {{ $sc->id_cpmk == $cpmk->id ? 'checked' : '' }}
                                           class="accent-emerald-600 w-3.5 h-3.5 cursor-pointer">
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-xs text-gray-400 italic">Belum ada CPMK / Sub-CPMK untuk ditampilkan.</p>
            @endif
        </div>
    </div>
</div>

{{-- ═══ SECTION 4: Deskripsi & Materi ════════════════════════════════════ --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">4</span>
        <h3 class="font-semibold text-gray-800 text-sm">Deskripsi & Materi Pembelajaran</h3>
    </div>
    <div class="p-5 space-y-5">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi Singkat Mata Kuliah</label>
            <textarea name="deskripsi_mk" rows="3"
                      placeholder="Tuliskan relevansi & capaian materi pembelajaran/bahan kajian sesuai Mata Kuliah ini..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('deskripsi_mk', $rps->deskripsi_mk) }}</textarea>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Kajian: Materi Pembelajaran</label>
            <textarea name="materi_pembelajaran" rows="3"
                      placeholder="Tuliskan bahan kajian dan dijabarkan dalam materi pembelajaran dalam pokok-pokok bahasan..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('materi_pembelajaran', $rps->materi_pembelajaran) }}</textarea>
        </div>
    </div>
</div>

{{-- ═══ SECTION 5: Pustaka ════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">5</span>
        <h3 class="font-semibold text-gray-800 text-sm">Pustaka</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Pustaka Utama</label>
            <textarea name="pustaka_utama" rows="4"
                      placeholder="1. Nama Pengarang (Tahun). Judul Buku. Penerbit.&#10;2. ..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('pustaka_utama', $rps->pustaka_utama) }}</textarea>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Pustaka Pendukung</label>
            <textarea name="pustaka_pendukung" rows="4"
                      placeholder="Tuliskan pustaka pendukung jika ada, sebagai pengayaan literasi..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('pustaka_pendukung', $rps->pustaka_pendukung) }}</textarea>
        </div>
    </div>
</div>

{{-- ═══ SECTION 6: Rencana Pertemuan (16 Minggu) ════════════════════════ --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-5">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">6</span>
        <h3 class="font-semibold text-gray-800 text-sm">Rencana Pertemuan (16 Minggu)</h3>
        <span class="ml-auto text-xs text-gray-400">Isi sesuai kebutuhan, bisa dikosongkan</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs border-collapse">
            <thead>
                <tr style="background:#F59E0B">
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-center w-14">Minggu</th>
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-left w-36">Sub-CPMK</th>
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-left">Indikator Penilaian</th>
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-left">Kriteria & Teknik</th>
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-left w-32">Bentuk Luring</th>
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-left w-32">Bentuk Daring</th>
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-left">Materi Pembelajaran</th>
                    <th class="border border-amber-300 text-white font-bold px-3 py-2.5 text-center w-20">Bobot (%)</th>
                </tr>
            </thead>
            <tbody>
                @for ($minggu = 1; $minggu <= 16; $minggu++)
                @php $p = $pertemuanMap->get($minggu); @endphp
                <tr class="{{ $minggu % 2 === 0 ? 'bg-amber-50' : 'bg-white' }}">
                    <td class="border border-amber-100 text-center font-bold text-gray-700 py-2">{{ $minggu }}</td>
                    <td class="border border-amber-100 p-1">
                        <select name="pertemuan[{{ $minggu }}][id_sub_cpmk]"
                                class="w-full border-0 bg-transparent text-xs py-1 focus:outline-none focus:ring-1 focus:ring-amber-400 rounded">
                            <option value="">— pilih —</option>
                            @foreach ($subCpmkAll as $sc)
                                <option value="{{ $sc->id }}"
                                    {{ old("pertemuan.{$minggu}.id_sub_cpmk", $p?->id_sub_cpmk) == $sc->id ? 'selected' : '' }}>
                                    {{ $sc->kode_sub_cpmk }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="border border-amber-100 p-1">
                        <textarea name="pertemuan[{{ $minggu }}][indikator_penilaian]" rows="2"
                                  class="w-full border-0 bg-transparent text-xs py-1 resize-none focus:outline-none focus:ring-1 focus:ring-amber-400 rounded min-w-[120px]"
                                  placeholder="Indikator…">{{ old("pertemuan.{$minggu}.indikator_penilaian", $p?->indikator_penilaian) }}</textarea>
                    </td>
                    <td class="border border-amber-100 p-1">
                        <textarea name="pertemuan[{{ $minggu }}][kriteria_teknik]" rows="2"
                                  class="w-full border-0 bg-transparent text-xs py-1 resize-none focus:outline-none focus:ring-1 focus:ring-amber-400 rounded min-w-[100px]"
                                  placeholder="Tes tulis, presentasi…">{{ old("pertemuan.{$minggu}.kriteria_teknik", $p?->kriteria_teknik) }}</textarea>
                    </td>
                    <td class="border border-amber-100 p-1">
                        <input type="text" name="pertemuan[{{ $minggu }}][bentuk_luring]"
                               value="{{ old("pertemuan.{$minggu}.bentuk_luring", $p?->bentuk_luring) }}"
                               placeholder="Kuliah, praktikum…"
                               class="w-full border-0 bg-transparent text-xs py-1 focus:outline-none focus:ring-1 focus:ring-amber-400 rounded">
                    </td>
                    <td class="border border-amber-100 p-1">
                        <input type="text" name="pertemuan[{{ $minggu }}][bentuk_daring]"
                               value="{{ old("pertemuan.{$minggu}.bentuk_daring", $p?->bentuk_daring) }}"
                               placeholder="LMS, video, zoom…"
                               class="w-full border-0 bg-transparent text-xs py-1 focus:outline-none focus:ring-1 focus:ring-amber-400 rounded">
                    </td>
                    <td class="border border-amber-100 p-1">
                        <textarea name="pertemuan[{{ $minggu }}][materi_pembelajaran]" rows="2"
                                  class="w-full border-0 bg-transparent text-xs py-1 resize-none focus:outline-none focus:ring-1 focus:ring-amber-400 rounded min-w-[120px]"
                                  placeholder="Pokok bahasan…">{{ old("pertemuan.{$minggu}.materi_pembelajaran", $p?->materi_pembelajaran) }}</textarea>
                    </td>
                    <td class="border border-amber-100 p-1 text-center">
                        <input type="number" name="pertemuan[{{ $minggu }}][bobot_penilaian]"
                               value="{{ old("pertemuan.{$minggu}.bobot_penilaian", $p?->bobot_penilaian) }}"
                               min="0" max="100" step="0.5"
                               class="w-full border-0 bg-transparent text-xs py-1 text-center focus:outline-none focus:ring-1 focus:ring-amber-400 rounded"
                               placeholder="0">
                    </td>
                </tr>
                @endfor
            </tbody>
            <tfoot>
                <tr class="bg-amber-100">
                    <td colspan="7" class="border border-amber-200 px-3 py-2 text-xs font-bold text-right text-gray-700">Total Bobot:</td>
                    <td class="border border-amber-200 px-3 py-2 text-xs font-bold text-center text-amber-800" id="total-bobot">0%</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- ═══ ACTIONS ════════════════════════════════════════════════════════════ --}}
<div class="flex items-center gap-3 sticky bottom-4">
    <button type="submit"
            class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan RPS
    </button>
    @if ($rps->status === 'draft')
    <button type="button" onclick="submitRps()"
            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
        </svg>
        Simpan & Submit ke Kaprodi
    </button>
    @endif
    <a href="{{ route('dosen.rps.show', $rps) }}"
       class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
</div>

</form>

{{-- Hidden submit form --}}
<form id="submit-form" method="POST" action="{{ route('dosen.rps.submit', $rps) }}" class="hidden">
    @csrf
</form>

@endsection

@push('scripts')
<script>
// Total bobot counter
(function(){
    function recalc(){
        let total = 0;
        document.querySelectorAll('input[name*="[bobot_penilaian]"]').forEach(function(el){
            const v = parseFloat(el.value) || 0;
            total += v;
        });
        const el = document.getElementById('total-bobot');
        if(el){
            el.textContent = total.toFixed(1) + '%';
            el.style.color = Math.abs(total - 100) < 0.1 ? '#15803d' : (total > 100 ? '#dc2626' : '#92400e');
        }
    }
    document.querySelectorAll('input[name*="[bobot_penilaian]"]').forEach(function(el){
        el.addEventListener('input', recalc);
    });
    recalc();
})();

function submitRps(){
    if(!confirm('Simpan dulu lalu submit ke Kaprodi? RPS tidak akan bisa diedit setelah disubmit.')) return;
    // Simpan dulu via form utama, lalu redirect ke submit
    const form = document.getElementById('rps-form');
    // Tambahkan hidden input untuk after-save redirect ke submit
    const h = document.createElement('input');
    h.type = 'hidden'; h.name = '_after_save'; h.value = 'submit';
    form.appendChild(h);
    form.submit();
}
</script>
@endpush
