@extends('layouts.app')

@section('title', 'Checklist LEDIK – ' . $kurikulum->kode)
@section('header', 'Checklist LEDIK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Checklist LEDIK</span>
@endsection

@section('content')

@php
    // Hitung skor: Ya=2, Parsial=1, Tidak=0
    $totalItems  = 0;
    $totalSkor   = 0;
    $maxSkor     = 0;
    $elemenSkor  = [];

    foreach ($checklist as $elemen => $items) {
        $skor = 0;
        $max  = $items->count() * 2;
        foreach ($items as $item) {
            $s = match($item->status) { 'ya' => 2, 'parsial' => 1, default => 0 };
            $skor += $s;
        }
        $elemenSkor[$elemen] = ['skor' => $skor, 'max' => $max, 'count' => $items->count()];
        $totalSkor  += $skor;
        $maxSkor    += $max;
        $totalItems += $items->count();
    }
    $persen = $maxSkor > 0 ? round($totalSkor / $maxSkor * 100) : 0;

    $elemenColor = [
        'PLAN'   => 'bg-blue-600',
        'DO'     => 'bg-emerald-600',
        'CHECK'  => 'bg-amber-500',
        'ACTION' => 'bg-violet-600',
    ];
    $elemenBorder = [
        'PLAN'   => 'border-blue-200 bg-blue-50',
        'DO'     => 'border-emerald-200 bg-emerald-50',
        'CHECK'  => 'border-amber-200 bg-amber-50',
        'ACTION' => 'border-violet-200 bg-violet-50',
    ];
    $elemenText = [
        'PLAN'   => 'text-blue-800',
        'DO'     => 'text-emerald-800',
        'CHECK'  => 'text-amber-800',
        'ACTION' => 'text-violet-800',
    ];
@endphp

{{-- Skor header --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
    <div class="flex items-center justify-between mb-3">
        <div>
            <p class="text-xs text-gray-500 mb-0.5">Skor LEDIK Total</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalSkor }} <span class="text-base font-normal text-gray-400">/ {{ $maxSkor }}</span></p>
        </div>
        <div class="text-right">
            <p class="text-4xl font-bold {{ $persen >= 70 ? 'text-green-600' : ($persen >= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ $persen }}%</p>
            <p class="text-xs text-gray-400">{{ $totalItems }} indikator</p>
        </div>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-3">
        <div class="h-3 rounded-full transition-all {{ $persen >= 70 ? 'bg-green-500' : ($persen >= 50 ? 'bg-amber-500' : 'bg-red-500') }}"
             style="width: {{ $persen }}%"></div>
    </div>
    <div class="grid grid-cols-4 gap-3 mt-4">
        @foreach ($elemenSkor as $el => $es)
            @php $ep = $es['max'] > 0 ? round($es['skor'] / $es['max'] * 100) : 0; @endphp
            <div class="text-center">
                <p class="text-xs font-bold {{ $elemenText[$el] ?? 'text-gray-700' }}">{{ $el }}</p>
                <p class="text-sm font-semibold text-gray-700">{{ $es['skor'] }}/{{ $es['max'] }}</p>
                <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                    <div class="h-1.5 rounded-full {{ $elemenColor[$el] ?? 'bg-gray-400' }}" style="width: {{ $ep }}%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-0.5">{{ $ep }}%</p>
            </div>
        @endforeach
    </div>
</div>

<form method="POST" action="{{ route('kurikulum.ledik.update', $kurikulum) }}">
    @csrf

    @foreach (['PLAN', 'DO', 'CHECK', 'ACTION'] as $elemen)
        @if ($checklist->has($elemen))
        @php $items = $checklist[$elemen]; @endphp
        <div class="mb-4 rounded-xl border {{ $elemenBorder[$elemen] ?? 'border-gray-200' }} overflow-hidden shadow-sm">
            <div class="px-4 py-3 flex items-center justify-between border-b {{ $elemenBorder[$elemen] ?? '' }}">
                <h3 class="font-bold text-sm {{ $elemenText[$elemen] ?? 'text-gray-800' }}">
                    {{ $elemen }}
                    <span class="text-xs font-normal text-gray-500 ml-2">
                        {{ $elemenSkor[$elemen]['skor'] }}/{{ $elemenSkor[$elemen]['max'] }} poin
                    </span>
                </h3>
                <span class="text-xs text-gray-500">{{ $items->count() }} indikator</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach ($items as $item)
                <div class="px-4 py-3 grid grid-cols-12 gap-3 items-start bg-white">
                    <div class="col-span-1 text-center">
                        <span class="inline-block font-mono font-bold text-xs px-1.5 py-0.5 rounded
                            {{ $elemenColor[$elemen] ?? 'bg-gray-500' }} text-white">{{ $item->kode_indikator }}</span>
                    </div>
                    <div class="col-span-5 text-sm text-gray-700 pt-1">{{ $item->deskripsi_indikator }}</div>
                    <div class="col-span-2">
                        <select name="items[{{ $item->kode_indikator }}][status]"
                                class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                @if ($kurikulum->isArsip()) disabled @endif>
                            <option value="">—</option>
                            <option value="ya"      {{ $item->status === 'ya'      ? 'selected' : '' }}>Ya (2)</option>
                            <option value="parsial" {{ $item->status === 'parsial' ? 'selected' : '' }}>Parsial (1)</option>
                            <option value="tidak"   {{ $item->status === 'tidak'   ? 'selected' : '' }}>Tidak (0)</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <textarea name="items[{{ $item->kode_indikator }}][catatan]" rows="1"
                                  class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Catatan (opsional)"
                                  @if ($kurikulum->isArsip()) disabled @endif>{{ $item->catatan }}</textarea>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    @if (!$kurikulum->isArsip())
    <div class="flex justify-end">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg transition-colors shadow-sm">
            Simpan Checklist
        </button>
    </div>
    @endif
</form>

@endsection
