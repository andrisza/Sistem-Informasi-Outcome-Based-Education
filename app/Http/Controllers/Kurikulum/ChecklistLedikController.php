<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\ChecklistLedik;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistLedikController extends Controller
{
    /** Indikator default LEDIK */
    private static array $defaults = [
        'PLAN' => [
            'P1' => 'Ketersediaan dokumen visi misi',
            'P2' => 'Kesesuaian PL dengan visi misi',
            'P3' => 'Kelengkapan CPL terhadap SNDIKTI',
            'P4' => 'Pemetaan CPL-PL',
            'P5' => 'Kelengkapan BK',
            'P6' => 'Pemetaan CPL-BK',
            'P7' => 'Pemetaan BK-MK',
        ],
        'DO' => [
            'D1' => 'Kelengkapan MK dalam kurikulum',
            'D2' => 'Distribusi SKS per semester',
            'D3' => 'Kesesuaian CPMK dengan CPL',
            'D4' => 'Kelengkapan RPS',
            'D5' => 'RPS sesuai format LAMINFOKOM',
            'D6' => 'Bobot penilaian tervalidasi',
        ],
        'CHECK' => [
            'C1' => 'Ketercapaian CPL rata-rata',
            'C2' => 'Nilai CPMK terdokumentasi',
            'C3' => 'Evaluasi CQI tersedia',
            'C4' => 'Laporan ketercapaian PL tersedia',
        ],
        'ACTION' => [
            'A1' => 'Tindak lanjut CQI terdokumentasi',
            'A2' => 'Lesson learned tersedia',
            'A3' => 'Rekomendasi siklus berikutnya tersedia',
        ],
    ];

    public function index(Kurikulum $kurikulum)
    {
        // Seed default indicators if not exist
        foreach (self::$defaults as $elemen => $items) {
            foreach ($items as $kode => $deskripsi) {
                ChecklistLedik::firstOrCreate(
                    ['id_kurikulum' => $kurikulum->id, 'kode_indikator' => $kode],
                    [
                        'elemen'              => $elemen,
                        'deskripsi_indikator' => $deskripsi,
                    ]
                );
            }
        }

        $checklist = ChecklistLedik::where('id_kurikulum', $kurikulum->id)
            ->get()
            ->groupBy('elemen');

        return view('kurikulum.ledik.index', compact('kurikulum', 'checklist'));
    }

    public function update(Request $request, Kurikulum $kurikulum)
    {
        $items = $request->input('items', []);

        foreach ($items as $kode => $data) {
            ChecklistLedik::where('id_kurikulum', $kurikulum->id)
                ->where('kode_indikator', $kode)
                ->update([
                    'status'     => $data['status'] ?? null,
                    'catatan'    => $data['catatan'] ?? null,
                    'updated_by' => Auth::id(),
                    'updated_at' => now(),
                ]);
        }

        return redirect()
            ->route('kurikulum.ledik.index', $kurikulum)
            ->with('success', 'Checklist LEDIK berhasil disimpan.');
    }
}
