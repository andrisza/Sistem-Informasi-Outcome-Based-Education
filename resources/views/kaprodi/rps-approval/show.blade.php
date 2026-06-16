@extends('layouts.app')

@section('title', 'Review RPS')
@section('header', 'Review RPS')

@section('breadcrumb')
    <a href="{{ route('kaprodi.rps-approval.index') }}" class="hover:text-blue-600">Persetujuan RPS</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $rps->mataKuliah->nama_mk ?? 'Detail' }}</span>
@endsection

@section('content')

<div class="max-w-3xl space-y-5">

    {{-- Info Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $rps->mataKuliah->nama_mk ?? '—' }}</h3>
                <p class="text-sm text-gray-500 font-mono mt-0.5">{{ $rps->mataKuliah->kode_mk ?? '' }}</p>
            </div>
            @php
                $statusColor = match($rps->status) {
                    'draft'    => 'bg-gray-100 text-gray-600',
                    'review'   => 'bg-amber-100 text-amber-700',
                    'disahkan' => 'bg-green-100 text-green-700',
                    default    => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                {{ ucfirst($rps->status) }}
            </span>
        </div>

        <dl class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Dosen Pengembang</dt>
                <dd class="text-gray-800">{{ $rps->dosenPengembang->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Semester</dt>
                <dd class="text-gray-800">{{ $rps->semester->nama ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal Penyusunan</dt>
                <dd class="text-gray-800">{{ $rps->tanggal_penyusunan?->format('d M Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Kode Dokumen</dt>
                <dd class="text-gray-800 font-mono">{{ $rps->kode_dokumen ?? '—' }}</dd>
            </div>
            @if ($rps->mataKuliah)
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">SKS MK (saat ini)</dt>
                <dd class="text-gray-800">{{ $rps->mataKuliah->sks_total ?? '—' }} SKS
                    <span class="text-xs text-gray-400">(T={{ $rps->mataKuliah->sks_teori ?? 0 }} P={{ $rps->mataKuliah->sks_praktikum ?? 0 }})</span>
                </dd>
            </div>
            @endif
            @if ($rps->sks_teori !== null || $rps->sks_praktikum !== null)
            <div>
                <dt class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-0.5">SKS Diusulkan di RPS</dt>
                <dd class="text-gray-800 font-semibold">
                    {{ ($rps->sks_teori ?? 0) + ($rps->sks_praktikum ?? 0) }} SKS
                    <span class="text-xs text-gray-500">(T={{ $rps->sks_teori ?? 0 }} P={{ $rps->sks_praktikum ?? 0 }})</span>
                    <span class="ml-1 text-[11px] text-amber-600 font-medium">← akan diperbarui ke MK jika disahkan</span>
                </dd>
            </div>
            @endif
            @if ($rps->disahkan_pada)
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Disahkan Pada</dt>
                <dd class="text-gray-800">{{ $rps->disahkan_pada->format('d M Y, H:i') }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Pertemuan / Materi --}}
    @if ($rps->pertemuan && $rps->pertemuan->count())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800 text-sm">Rencana Pertemuan ({{ $rps->pertemuan->count() }} pertemuan)</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">Prt.</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kemampuan Akhir</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($rps->pertemuan->sortBy('pertemuan_ke') as $prt)
                        <tr>
                            <td class="px-4 py-2.5 text-center text-gray-500 font-mono text-xs">{{ $prt->pertemuan_ke }}</td>
                            <td class="px-4 py-2.5 text-gray-700">{{ $prt->kemampuan_akhir ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-600 text-xs">{{ $prt->metode_pembelajaran ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $prt->durasi_menit ? $prt->durasi_menit.' mnt' : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Aksi Persetujuan --}}
    @if ($rps->status === 'review')
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h4 class="text-sm font-semibold text-gray-800 mb-4">Keputusan Persetujuan</h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Setujui --}}
            <form method="POST" action="{{ route('kaprodi.rps-approval.approve', $rps) }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Sahkan RPS ini?')"
                        class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-3 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Sahkan RPS
                </button>
            </form>

            {{-- Tolak --}}
            <form method="POST" action="{{ route('kaprodi.rps-approval.reject', $rps) }}" id="form-reject">
                @csrf
                <input type="hidden" name="catatan" id="catatan-reject">
                <button type="button"
                        onclick="rejectRPS()"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-sm font-medium px-4 py-3 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Kembalikan ke Draft
                </button>
            </form>
        </div>

        @error('catatan')
            <p class="mt-3 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
    @endif

</div>

@push('scripts')
<script>
function rejectRPS() {
    const catatan = prompt('Catatan penolakan (opsional):');
    if (catatan === null) return;
    document.getElementById('catatan-reject').value = catatan;
    if (confirm('Kembalikan RPS ke draft?')) {
        document.getElementById('form-reject').submit();
    }
}
</script>
@endpush

@endsection
