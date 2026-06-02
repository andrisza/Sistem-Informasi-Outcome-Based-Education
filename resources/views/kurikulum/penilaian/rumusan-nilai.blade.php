@extends('layouts.app')
@section('title', 'Rumusan Nilai Akhir MK')
@section('header', 'Rumusan Nilai Akhir MK')
@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Rumusan Nilai Akhir MK</span>
@endsection
@section('content')

@if (!$kurikulum->isArsip())
<form method="POST" action="{{ route('kurikulum.penilaian.rumusan.save', $kurikulum) }}">
@csrf
@endif

<div class="rounded-xl border border-orange-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs w-full">
            <thead>
                <tr style="background:#EA580C">
                    <th class="px-4 py-3 text-center font-bold text-white border border-orange-400 w-20">MK</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-orange-400 w-20">CPL</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-orange-400 w-28">CPMK</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-orange-400 w-28">Skor Maks</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-orange-400 w-20">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mkList as $mk)
                    @php
                        $cpmks = $mk->cpmk;
                        $mkRows = max($cpmks->count(), 1);
                        $mkTotal = $cpmks->sum(function($c) {
                            $p = $c->penilaian;
                            if ($p && !is_null($p->skor_maks)) return (float)$p->skor_maks;
                            return (float)$c->subCpmk->sum('bobot');
                        });
                        $rowBg = $loop->even ? '#fff7ed' : '#fff';
                        $totalColor = $mkTotal == 100 ? 'text-emerald-700' : ($mkTotal > 0 ? 'text-amber-700' : 'text-gray-400');
                    @endphp

                    @if ($cpmks->isEmpty())
                        <tr style="background:{{ $rowBg }}">
                            <td class="px-4 py-3 text-center font-mono font-bold text-blue-800 border border-orange-100">{{ $mk->kode_mk }}</td>
                            <td colspan="3" class="px-4 py-3 text-gray-400 italic border border-orange-100">Belum ada CPMK</td>
                            <td class="px-4 py-3 text-center border border-orange-100 text-gray-400">—</td>
                        </tr>
                    @else
                        @foreach ($cpmks as $ci => $cpmk)
                            @php
                                $p = $cpmk->penilaian;
                                $skorMaks = $p && !is_null($p->skor_maks)
                                    ? (float)$p->skor_maks
                                    : (float)$cpmk->subCpmk->sum('bobot');
                            @endphp
                            <tr style="background:{{ $rowBg }}" class="hover:bg-orange-50/30">
                                @if ($ci === 0)
                                    <td class="px-4 py-2.5 text-center font-mono font-bold text-blue-800 border border-orange-100 align-middle"
                                        rowspan="{{ $mkRows }}"
                                        style="background:{{ $loop->parent->even ? '#fed7aa' : '#fff7ed' }}">
                                        <span class="cursor-help"
                                              data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                              data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                    </td>
                                @endif
                                <td class="px-4 py-2.5 text-center border border-orange-100 align-middle">
                                    @if ($cpmk->cplProdi)
                                        <span class="font-mono font-bold text-[10px] bg-violet-100 text-violet-800 px-1.5 py-0.5 rounded cursor-help"
                                              data-tooltip="{{ $cpmk->cplProdi->kode_cpl }}: {{ $cpmk->cplProdi->deskripsi }}"
                                              data-tip-label="CPL">{{ $cpmk->cplProdi->kode_cpl }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-center border border-orange-100 align-middle">
                                    <span class="font-mono font-bold text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded cursor-help"
                                          data-tooltip="{{ $cpmk->kode_cpmk }}: {{ $cpmk->deskripsi }}"
                                          data-tip-label="CPMK">{{ $cpmk->kode_cpmk }}</span>
                                </td>
                                <td class="px-4 py-2 text-center border border-orange-100 align-middle">
                                    @if (!$kurikulum->isArsip())
                                        <input type="number" name="skor_maks[{{ $cpmk->id }}]"
                                               value="{{ $skorMaks > 0 ? $skorMaks : '' }}"
                                               min="0" max="100" step="0.5"
                                               placeholder="0"
                                               class="w-20 border border-gray-300 rounded px-2 py-1 text-xs text-center focus:outline-none focus:ring-1 focus:ring-orange-500 skor-input"
                                               data-mk="{{ $mk->id }}">
                                    @else
                                        <span class="font-bold text-amber-700">{{ $skorMaks > 0 ? $skorMaks : '—' }}</span>
                                    @endif
                                </td>
                                @if ($ci === 0)
                                    <td class="px-4 py-2.5 text-center border border-orange-100 align-middle font-bold text-sm"
                                        rowspan="{{ $mkRows }}">
                                        <span class="{{ $totalColor }} mk-total" id="total-mk-{{ $mk->id }}">
                                            {{ $mkTotal > 0 ? $mkTotal : '—' }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 flex flex-wrap gap-4 text-xs text-gray-400 items-center">
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Total = 100 (sempurna)</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Total ≠ 100 (perlu diperiksa)</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Belum ada bobot</span>
    @if (!$kurikulum->isArsip())
        <div class="ml-auto">
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan Skor Maks
            </button>
        </div>
    @endif
</div>

@if (!$kurikulum->isArsip())
</form>
@endif

@endsection
@push('scripts')
@include('layouts._pivot-tooltip')
<script>
// Auto-recalculate total per MK on input change
(function () {
    document.querySelectorAll('.skor-input').forEach(function (inp) {
        inp.addEventListener('input', recalc);
    });
    function recalc() {
        // Group inputs by mk id
        var byMk = {};
        document.querySelectorAll('.skor-input').forEach(function (inp) {
            var mk = inp.dataset.mk;
            if (!byMk[mk]) byMk[mk] = [];
            byMk[mk].push(parseFloat(inp.value) || 0);
        });
        Object.keys(byMk).forEach(function (mkId) {
            var total = byMk[mkId].reduce(function (a, b) { return a + b; }, 0);
            var el = document.getElementById('total-mk-' + mkId);
            if (!el) return;
            el.textContent = total > 0 ? total : '—';
            el.className = 'mk-total font-bold text-sm ' + (total === 100 ? 'text-emerald-700' : total > 0 ? 'text-amber-700' : 'text-gray-400');
        });
    }
})();
</script>
@endpush
