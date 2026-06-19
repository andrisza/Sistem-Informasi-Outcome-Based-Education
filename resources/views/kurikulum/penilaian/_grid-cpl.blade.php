@php
// Warna rotasi untuk setiap kelompok MK — setiap indeks MK mendapat warna berbeda
$mkPalette = [
    ['h' => '#FFD700', 'm' => '#FFE966', 'l' => '#FFFDE7', 't' => '#1a0a00'], // Kuning
    ['h' => '#70AD47', 'm' => '#A9D18E', 'l' => '#E2EFDA', 't' => '#0d2b00'], // Hijau
    ['h' => '#FF7BAC', 'm' => '#FFA8CA', 'l' => '#FFE4EE', 't' => '#5c0024'], // Pink
    ['h' => '#4472C4', 'm' => '#9DC3E6', 'l' => '#DDEEFF', 't' => '#00214d'], // Biru
    ['h' => '#9966CC', 'm' => '#C8A8E8', 'l' => '#EDE7F6', 't' => '#2e0060'], // Ungu
    ['h' => '#2AAFAB', 'm' => '#80CFCC', 'l' => '#E0F5F4', 't' => '#003d3b'], // Teal
];
@endphp

<div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden bg-white">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs w-full">
            <thead>
                {{-- Baris 1: Nama Mahasiswa + header MK + header agregasi CPL --}}
                <tr>
                    <th rowspan="3" style="background:#374151;min-width:200px"
                        class="px-3 py-2 text-left text-xs font-bold text-white border border-gray-500 sticky left-0 z-20 align-middle">
                        Nama Mahasiswa
                    </th>
                    @foreach ($mkGroups as $mkGroup)
                        @php
                            $mkColCount = collect($mkGroup['cpl_groups'])->sum(fn ($g) => count($g['cpmks'])) + 1;
                            $pal = $mkPalette[$loop->index % count($mkPalette)];
                        @endphp
                        <th colspan="{{ $mkColCount }}"
                            style="background:{{ $pal['h'] }};color:{{ $pal['t'] }}"
                            class="px-2 py-2 text-center text-xs font-bold border border-gray-400">
                            <span class="cursor-help" data-tooltip="{{ $mkGroup['mk']->nama_mk }}" data-tip-label="Mata Kuliah">
                                {{ $mkGroup['mk']->kode_mk }}
                            </span>
                        </th>
                    @endforeach
                    @foreach ($cplAggGroups as $aggGroup)
                        <th rowspan="3" style="background:#FFD700;color:#1a0a00;min-width:110px"
                            class="px-2 py-2 text-center text-xs font-bold border border-yellow-500 align-middle">
                            <span class="cursor-help"
                                  data-tooltip="Total skor maksimal: {{ $fmtNum($aggGroup['total_skor_maks']) }}. Dihitung dari MK: {{ implode(', ', $aggGroup['mk_codes']) }}."
                                  data-tip-label="Nilai CPL">
                                Nilai {{ $aggGroup['cpl']->kode_cpl }}<br>({{ implode(' & ', $aggGroup['mk_codes']) }})
                            </span>
                        </th>
                        <th rowspan="3" style="background:#E2EFDA;color:#0d2b00;min-width:110px"
                            class="px-2 py-2 text-center text-xs font-bold border border-green-400 align-middle">
                            <span class="cursor-help"
                                  data-tooltip="{{ $aggGroup['cpl']->deskripsi }}"
                                  data-tip-label="Capaian CPL (Skor / {{ $fmtNum($aggGroup['total_skor_maks']) }} * 100%)">
                                Capaian {{ $aggGroup['cpl']->kode_cpl }}
                            </span>
                        </th>
                    @endforeach
                </tr>

                {{-- Baris 2: Header CPL per MK + Nilai MK --}}
                <tr>
                    @foreach ($mkGroups as $mkGroup)
                        @php $pal = $mkPalette[$loop->index % count($mkPalette)]; @endphp
                        @foreach ($mkGroup['cpl_groups'] as $cplGroup)
                            <th colspan="{{ count($cplGroup['cpmks']) }}"
                                style="background:{{ $pal['m'] }};color:{{ $pal['t'] }}"
                                class="px-2 py-1.5 text-center text-xs font-bold border border-gray-300">
                                <span class="font-mono cursor-help"
                                      data-tooltip="{{ $cplGroup['cpl']?->deskripsi ?? 'CPMK tanpa CPL Prodi terkait' }}"
                                      data-tip-label="CPL Prodi">
                                    {{ $cplGroup['cpl']?->kode_cpl ?? 'Lainnya' }}
                                </span>
                            </th>
                        @endforeach
                        <th rowspan="2" style="background:{{ $pal['m'] }};color:{{ $pal['t'] }};min-width:110px"
                            class="px-2 py-1.5 text-center text-xs font-bold border border-gray-300 align-middle">
                            Nilai MK {{ $mkGroup['mk']->kode_mk }}<br>({{ $fmtNum($mkGroup['total_skor_maks']) }})
                        </th>
                    @endforeach
                </tr>

                {{-- Baris 3: Header CPMK --}}
                <tr>
                    @foreach ($mkGroups as $mkGroup)
                        @php $pal = $mkPalette[$loop->index % count($mkPalette)]; @endphp
                        @foreach ($mkGroup['cpl_groups'] as $cplGroup)
                            @foreach ($cplGroup['cpmks'] as $c)
                                <th style="background:{{ $pal['l'] }};color:{{ $pal['t'] }};min-width:80px"
                                    class="px-2 py-1.5 text-center text-[10px] font-semibold border border-gray-200">
                                    <span class="font-mono cursor-help"
                                          data-tooltip="{{ $c['cpmk']->deskripsi }}"
                                          data-tip-label="CPMK">{{ $c['cpmk']->kode_cpmk }}</span>
                                    <div class="font-normal" style="color:{{ $pal['t'] }};opacity:0.65">maks {{ $fmtNum($c['skor_maks']) }}</div>
                                </th>
                            @endforeach
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr class="font-semibold">
                    <td style="background:#F3F4F6;min-width:200px"
                        class="px-3 py-2 border border-gray-200 sticky left-0 z-10 text-gray-700">
                        Nilai Total
                    </td>
                    @foreach ($columnLayout as $i => $col)
                        <td style="background:#F9FAFB" class="border border-gray-200 text-center p-1.5 text-gray-700">
                            {{ $totalRow[$i] ?? '-' }}
                        </td>
                    @endforeach
                </tr>
                @foreach ($mahasiswaList as $enrollment)
                    @php $mhs = $enrollment->mahasiswa; @endphp
                    <tr class="hover:bg-gray-50/60">
                        <td style="min-width:200px;background:#ffffff"
                            class="px-3 py-2 border border-gray-200 sticky left-0 z-10 align-middle">
                            <p class="font-medium text-gray-800">{{ $mhs->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ $mhs->identifier ?? '-' }}</p>
                        </td>
                        @foreach ($columnLayout as $i => $col)
                            @php
                                $val = $valueGrid[$mhs->id][$i] ?? null;
                                $cellColor = '';
                                if ($val !== null) {
                                    if ($col['type'] === 'cpl_capaian') {
                                        $cellColor = $val >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                    } elseif ($col['type'] === 'cpmk') {
                                        $pct = $col['skor_maks'] > 0 ? ($val / $col['skor_maks'] * 100) : 0;
                                        $cellColor = $pct >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                    }
                                }
                            @endphp
                            <td class="border border-gray-200 text-center p-1.5 {{ $cellColor ?: 'text-gray-700' }}">
                                {{ $val !== null ? $fmtNum($val) : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
