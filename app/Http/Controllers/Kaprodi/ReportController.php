<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $kurikulumList = Kurikulum::orderByDesc('tahun_mulai')->get();

        return view('kaprodi.reports.index', compact('kurikulumList'));
    }

    public function cpl(Kurikulum $kurikulum)
    {
        // hasil_cpl has no id_mk — aggregate per CPL + semester
        $hasilCpl = DB::table('hasil_cpl as hc')
            ->join('cpl_prodi as cp', 'hc.id_cpl', '=', 'cp.id')
            ->join('semester_akademik as sa', 'hc.id_semester', '=', 'sa.id')
            ->leftJoin('batas_ketercapaian as bk', function ($join) use ($kurikulum) {
                $join->on('bk.id_cpl', '=', 'hc.id_cpl')
                     ->where('bk.id_kurikulum', '=', $kurikulum->id);
            })
            ->where('hc.id_kurikulum', $kurikulum->id)
            ->select(
                'cp.id as cp_id',
                'cp.kode_cpl',
                'cp.deskripsi as cpl_deskripsi',
                'cp.urutan',
                'sa.id as sa_id',
                'sa.nama as semester_nama',
                DB::raw('AVG(hc.nilai_cpl) as nilai_rata_rata'),
                DB::raw('SUM(CASE WHEN hc.status_tercapai = 1 THEN 1 ELSE 0 END) as jumlah_tercapai'),
                DB::raw('COUNT(DISTINCT hc.id_mahasiswa) as jumlah_mahasiswa'),
                DB::raw('MAX(bk.batas_nilai) as batas_nilai')
            )
            ->groupBy('cp.id', 'cp.kode_cpl', 'cp.deskripsi', 'cp.urutan', 'sa.id', 'sa.nama')
            ->orderBy('cp.urutan')
            ->orderBy('sa.id')
            ->get();

        return view('kaprodi.reports.cpl', compact('kurikulum', 'hasilCpl'));
    }

    public function pl(Kurikulum $kurikulum)
    {
        // batas_ketercapaian only covers CPL, not PL — no batas join here
        $hasilPl = DB::table('hasil_pl as hp')
            ->join('pl as p', 'hp.id_pl', '=', 'p.id')
            ->join('semester_akademik as sa', 'hp.id_semester', '=', 'sa.id')
            ->where('hp.id_kurikulum', $kurikulum->id)
            ->select(
                'p.id as p_id',
                'p.kode_pl',
                'p.deskripsi as pl_deskripsi',
                'p.urutan',
                'sa.id as sa_id',
                'sa.nama as semester_nama',
                DB::raw('AVG(hp.nilai_pl) as nilai_rata_rata'),
                DB::raw('SUM(CASE WHEN hp.status_tercapai = 1 THEN 1 ELSE 0 END) as jumlah_tercapai'),
                DB::raw('COUNT(DISTINCT hp.id_mahasiswa) as jumlah_mahasiswa')
            )
            ->groupBy('p.id', 'p.kode_pl', 'p.deskripsi', 'p.urutan', 'sa.id', 'sa.nama')
            ->orderBy('p.urutan')
            ->orderBy('sa.id')
            ->get();

        return view('kaprodi.reports.pl', compact('kurikulum', 'hasilPl'));
    }

    public function ketercapaian()
    {
        $kurikulumAll = Kurikulum::orderByDesc('tahun_mulai')->get();

        $totalKurikulum = $kurikulumAll->count();

        // Aggregate CPL per kurikulum
        $rekapCpl = [];
        $cplTercapai = $cplBelumTercapai = 0;

        foreach ($kurikulumAll as $k) {
            // Per-CPL average, compare to batas (default 65 if none set)
            $rows = DB::table('hasil_cpl as hc')
                ->leftJoin('batas_ketercapaian as bk', function ($join) use ($k) {
                    $join->on('bk.id_cpl', '=', 'hc.id_cpl')
                         ->where('bk.id_kurikulum', '=', $k->id);
                })
                ->where('hc.id_kurikulum', $k->id)
                ->select(
                    'hc.id_cpl',
                    DB::raw('AVG(hc.nilai_cpl) as avg_nilai'),
                    DB::raw('MAX(bk.batas_nilai) as batas_nilai')
                )
                ->groupBy('hc.id_cpl')
                ->get();

            $total    = $rows->count();
            $tercapai = $rows->filter(fn ($r) => $r->avg_nilai >= ($r->batas_nilai ?? 65))->count();
            $belum    = $total - $tercapai;

            $rekapCpl[]       = ['kurikulum' => $k, 'total' => $total, 'tercapai' => $tercapai, 'belum' => $belum];
            $cplTercapai     += $tercapai;
            $cplBelumTercapai += $belum;
        }

        // Aggregate PL per kurikulum
        $rekapPl = [];
        $plTercapai = 0;

        foreach ($kurikulumAll as $k) {
            // A PL is "tercapai" if majority (≥50%) of mahasiswa have status_tercapai=1
            $rows = DB::table('hasil_pl as hp')
                ->where('hp.id_kurikulum', $k->id)
                ->select(
                    'hp.id_pl',
                    DB::raw('COUNT(DISTINCT hp.id_mahasiswa) as n_total'),
                    DB::raw('COUNT(DISTINCT CASE WHEN hp.status_tercapai = 1 THEN hp.id_mahasiswa END) as n_tercapai')
                )
                ->groupBy('hp.id_pl')
                ->get();

            $total    = $rows->count();
            $tercapai = $rows->filter(fn ($r) => $r->n_total > 0 && ($r->n_tercapai / $r->n_total) >= 0.5)->count();
            $belum    = $total - $tercapai;

            $rekapPl[]   = ['kurikulum' => $k, 'total' => $total, 'tercapai' => $tercapai, 'belum' => $belum];
            $plTercapai += $tercapai;
        }

        return view('kaprodi.reports.ketercapaian', compact(
            'totalKurikulum', 'cplTercapai', 'cplBelumTercapai',
            'plTercapai', 'rekapCpl', 'rekapPl'
        ));
    }
}
