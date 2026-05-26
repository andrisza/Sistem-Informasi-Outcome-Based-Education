@extends('layouts.app')

@section('title', 'Matriks CPL Prodi ↔ Profil Lulusan')
@section('header', 'Matriks CPL Prodi ↔ Profil Lulusan')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Matriks CPL ↔ PL</span>
@endsection

@section('content')

@if ($plList->isEmpty() || $cplList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">Profil Lulusan dan CPL Prodi harus diisi terlebih dahulu.</p>
        </div>
    </div>
@else

{{-- Legend (collapsible) --}}
<details class="mb-3 bg-white border border-gray-100 rounded-xl shadow-sm">
    <summary class="px-4 py-2 text-xs font-semibold text-gray-600 cursor-pointer hover:text-gray-900 select-none">
        Lihat deskripsi kode (CPL Prodi &amp; Profil Lulusan)
    </summary>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 py-3 border-t border-gray-100">
        <div>
            <p class="text-[10px] font-bold text-violet-700 uppercase tracking-wider mb-1.5">CPL Prodi</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($cplList as $cpl)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-violet-700 shrink-0 w-14">{{ $cpl->kode_cpl }}</span>
                        <span class="text-gray-500 leading-snug">{{ Str::limit($cpl->deskripsi ?? '', 80) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div>
            <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1.5">Profil Lulusan</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($plList as $pl)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-amber-700 shrink-0 w-12">{{ $pl->kode_pl }}</span>
                        <span class="text-gray-500 leading-snug">{{ Str::limit($pl->deskripsi ?? '', 80) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</details>

<div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
    <span class="flex items-center gap-1.5">
        <span class="inline-block w-5 h-5 rounded bg-amber-100 border border-amber-300 text-amber-700 font-bold text-sm text-center leading-5">✓</span>
        Terpetakan
    </span>
    <span class="text-emerald-600 font-medium">· Auto-save: setiap klik langsung tersimpan</span>
    <span class="text-gray-400">· Hover kode untuk deskripsi</span>
</div>

<div>
    <div class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="border-collapse">
                <thead>
                    <tr>
                        <th style="background:#F59E0B;min-width:120px" class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                            CPL Prodi \ PL
                        </th>
                        @foreach ($plList as $pl)
                            <th style="background:#F59E0B;min-width:72px" class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">
                                <span class="font-mono cursor-help block"
                                      data-tooltip="{{ $pl->kode_pl }}: {{ $pl->deskripsi ?? '' }}"
                                      data-tip-label="Profil Lulusan">{{ $pl->kode_pl }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cplList as $cpl)
                        @php $rowBg = $loop->even ? '#fffbeb' : '#fff'; @endphp
                        <tr style="background:{{ $rowBg }}">
                            <td style="min-width:120px;background:{{ $loop->even ? '#fef3c7' : '#fffbeb' }}"
                                class="px-4 py-2.5 border border-amber-200 sticky left-0 z-10">
                                <span class="font-mono font-bold text-violet-800 text-xs cursor-help"
                                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi ?? '' }}"
                                      data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                            </td>
                            @foreach ($plList as $pl)
                                @php $checked = in_array($pl->id, $existing[$cpl->id] ?? []); @endphp
                                <td class="border border-amber-100 text-center align-middle pivot-cell {{ $checked ? 'is-checked' : '' }}"
                                    style="min-width:72px;height:42px"
                                    @unless ($kurikulum->isArsip())
                                        data-table="pivot_pl_cpl"
                                        data-keys='{"id_pl":{{ $pl->id }},"id_cpl":{{ $cpl->id }}}'
                                    @endunless>
                                    <input type="checkbox"
                                           class="pivot-cb"
                                           {{ $checked ? 'checked' : '' }}
                                           {{ $kurikulum->isArsip() ? 'disabled' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex items-center gap-3 text-xs text-gray-500">
        <span id="pivot-changed-info"></span>
    </div>
</div>

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@unless ($kurikulum->isArsip())
    @include('layouts._pivot-autosave')
@endunless
<script>
(function () {
    var info = document.getElementById('pivot-changed-info');
    function refresh() { if (info) info.textContent = document.querySelectorAll('.pivot-cb:checked').length + ' relasi aktif'; }
    document.querySelectorAll('.pivot-cell').forEach(c => c.addEventListener('click', () => setTimeout(refresh, 0)));
    refresh();
})();
</script>
@endpush
