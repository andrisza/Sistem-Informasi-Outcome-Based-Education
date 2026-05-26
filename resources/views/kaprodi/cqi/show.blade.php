@extends('layouts.app')

@section('title', 'Detail CQI')
@section('header', 'Detail Evaluasi CQI')

@section('breadcrumb')
    <a href="{{ route('kaprodi.cqi.index') }}" class="hover:text-blue-600">Evaluasi CQI</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Detail</span>
@endsection

@section('content')

<div class="max-w-3xl space-y-5">

    {{-- Detail Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $log->mataKuliah->nama_mk ?? '—' }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $log->semester->nama ?? '—' }} · {{ $log->kurikulum->nama ?? '—' }}
                </p>
            </div>
            @php
                $statusColor = match($log->status) {
                    'belum'  => 'bg-red-100 text-red-700',
                    'proses' => 'bg-amber-100 text-amber-700',
                    'selesai'=> 'bg-green-100 text-green-700',
                    default  => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                {{ ucfirst($log->status) }}
            </span>
        </div>

        <div class="space-y-4 text-sm">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Temuan</p>
                <p class="text-gray-800 leading-relaxed bg-gray-50 rounded-lg px-4 py-3">{{ $log->temuan }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Akar Masalah</p>
                <p class="text-gray-800 leading-relaxed bg-gray-50 rounded-lg px-4 py-3">
                    {{ $log->akar_masalah ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Rekomendasi</p>
                <p class="text-gray-800 leading-relaxed bg-gray-50 rounded-lg px-4 py-3">{{ $log->rekomendasi }}</p>
            </div>

            <dl class="grid grid-cols-2 gap-x-8 gap-y-3 pt-2">
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">CPL Terdampak</dt>
                    <dd class="text-gray-800">{{ $log->cplTerdampak->kode_cpl ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Target Implementasi</dt>
                    <dd class="text-gray-800">{{ $log->target_implementasi ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Dilaporkan Oleh</dt>
                    <dd class="text-gray-800">{{ $log->dilaporkanOleh->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Disetujui Oleh</dt>
                    <dd class="text-gray-800">{{ $log->disetujuiOleh->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Dibuat</dt>
                    <dd class="text-gray-800">{{ $log->created_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Aksi --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h4 class="text-sm font-semibold text-gray-800 mb-4">Ubah Status &amp; Persetujuan</h4>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Update Status --}}
            <form method="POST" action="{{ route('kaprodi.cqi.update', $log) }}">
                @csrf @method('PATCH')
                <div class="flex items-center gap-2">
                    <select name="status"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="belum"  {{ $log->status === 'belum'  ? 'selected' : '' }}>Belum</option>
                        <option value="proses" {{ $log->status === 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai"{{ $log->status === 'selesai'? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        Update Status
                    </button>
                </div>
            </form>

            {{-- Setujui --}}
            @if (!$log->disetujui_oleh)
                <form method="POST" action="{{ route('kaprodi.cqi.setujui', $log) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Setujui evaluasi CQI ini?')"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Setujui
                    </button>
                </form>
            @else
                <span class="inline-flex items-center gap-1.5 text-sm text-green-700 bg-green-50 px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Telah disetujui oleh {{ $log->disetujuiOleh->name }}
                </span>
            @endif

            <a href="{{ route('kaprodi.cqi.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">← Kembali</a>
        </div>
    </div>

</div>

@endsection
