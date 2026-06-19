<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HasilCpl;
use App\Models\Kurikulum;
use App\Models\RpsHeader;
use App\Models\SemesterAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers     = User::count();
        $kurikulumAktif = Kurikulum::where('status', 'aktif')->count();
        $rpsPending     = RpsHeader::where('status', 'review')->count();

        $rpsPendingList = RpsHeader::with(['mataKuliah', 'dosenPengembang', 'semester'])
                                   ->where('status', 'review')
                                   ->orderByDesc('updated_at')
                                   ->take(5)
                                   ->get();

        $recentActivity = ActivityLog::with('user')
                                     ->orderByDesc('created_at')
                                     ->take(10)
                                     ->get();

        // ── Radar Ketercapaian CPL ──────────────────────────────────────────
        $allKurikulum = Kurikulum::where('status', 'aktif')
            ->orderBy('nama_kurikulum')
            ->get(['id', 'kode', 'nama_kurikulum']);

        $radarKurikulumId = $request->filled('radar_kurikulum')
            ? (int) $request->radar_kurikulum
            : $allKurikulum->first()?->id;

        $radarKurikulum = $allKurikulum->firstWhere('id', $radarKurikulumId);

        $semesterList    = SemesterAkademik::orderByDesc('id')->get();
        $radarSemesterId = $request->filled('radar_semester') ? (int) $request->radar_semester : null;
        $radarSemester   = $radarSemesterId
            ? $semesterList->firstWhere('id', $radarSemesterId)
            : ($semesterList->firstWhere('is_aktif', true) ?? $semesterList->first());

        $cplList = $radarKurikulum
            ? $radarKurikulum->cplProdi()->orderBy('urutan')->get(['id', 'kode_cpl', 'deskripsi'])
            : collect();

        $angkatanDatasets  = [];
        $mahasiswaDatasets = [];
        $mahasiswaList     = collect();

        if ($radarKurikulum && $radarSemester && $cplList->isNotEmpty()) {
            $cplIds = $cplList->pluck('id');

            $hasilCplAll = HasilCpl::whereIn('id_cpl', $cplIds)
                ->where('id_semester', $radarSemester->id)
                ->get(['id_mahasiswa', 'id_cpl', 'nilai_cpl', 'status_tercapai']);

            if ($hasilCplAll->isNotEmpty()) {
                $mhsIds = $hasilCplAll->pluck('id_mahasiswa')->unique();
                $mahasiswaList = User::whereIn('id', $mhsIds)
                    ->orderBy('tahun_masuk')
                    ->orderBy('name')
                    ->get(['id', 'name', 'identifier', 'tahun_masuk']);

                $hasilByMhs = $hasilCplAll->groupBy('id_mahasiswa');

                // Rata-rata per angkatan
                foreach ($mahasiswaList->groupBy('tahun_masuk') as $tahun => $group) {
                    $values = [];
                    foreach ($cplList as $cpl) {
                        $total = 0;
                        $count = 0;
                        foreach ($group->pluck('id') as $mhsId) {
                            $h = $hasilByMhs->get($mhsId)?->firstWhere('id_cpl', $cpl->id);
                            if ($h) { $total += $h->nilai_cpl; $count++; }
                        }
                        $values[] = $count > 0 ? round($total / $count, 1) : null;
                    }
                    $angkatanDatasets[(string) $tahun] = $values;
                }

                // Data per mahasiswa
                foreach ($mahasiswaList as $mhs) {
                    $values = [];
                    foreach ($cplList as $cpl) {
                        $h = $hasilByMhs->get($mhs->id)?->firstWhere('id_cpl', $cpl->id);
                        $values[] = $h ? round((float) $h->nilai_cpl, 1) : null;
                    }
                    $mahasiswaDatasets[$mhs->id] = [
                        'name'       => $mhs->name,
                        'identifier' => $mhs->identifier,
                        'angkatan'   => $mhs->tahun_masuk,
                        'values'     => $values,
                    ];
                }
            }
        }

        // ── Profil Lulusan (PL) radar ──────────────────────────────────────────
        $plList = $radarKurikulum
            ? $radarKurikulum->pl()->orderBy('urutan')->get(['id', 'kode_pl', 'deskripsi'])
            : collect();

        $angkatanDatasetsPerPl = [];

        if ($plList->isNotEmpty() && !empty($angkatanDatasets)) {
            $plCplLinks = DB::table('pivot_pl_cpl')
                ->whereIn('id_pl', $plList->pluck('id'))
                ->get(['id_pl', 'id_cpl']);

            // Map PL id → CPL indices in $cplList
            $plCplIndices = [];
            foreach ($plList as $pl) {
                $linkedCplIds = $plCplLinks->where('id_pl', $pl->id)->pluck('id_cpl')->toArray();
                $indices = [];
                foreach ($cplList as $idx => $cpl) {
                    if (in_array($cpl->id, $linkedCplIds)) $indices[] = $idx;
                }
                $plCplIndices[$pl->id] = $indices;
            }

            $avgFromIndices = function (array $cplVals, array $indices): ?float {
                $filtered = array_values(array_filter(
                    array_map(fn($i) => $cplVals[$i] ?? null, $indices),
                    fn($v) => $v !== null
                ));
                return count($filtered) > 0 ? round(array_sum($filtered) / count($filtered), 1) : null;
            };

            foreach ($angkatanDatasets as $tahun => $cplVals) {
                $plVals = [];
                foreach ($plList as $pl) {
                    $plVals[] = $avgFromIndices($cplVals, $plCplIndices[$pl->id] ?? []);
                }
                $angkatanDatasetsPerPl[(string) $tahun] = $plVals;
            }

            foreach ($mahasiswaDatasets as $mhsId => &$data) {
                $plVals = [];
                foreach ($plList as $pl) {
                    $plVals[] = $avgFromIndices($data['values'], $plCplIndices[$pl->id] ?? []);
                }
                $data['pl_values'] = $plVals;
            }
            unset($data);
        }

        return view('kaprodi.dashboard', compact(
            'totalUsers', 'kurikulumAktif', 'rpsPending',
            'rpsPendingList', 'recentActivity',
            'allKurikulum', 'radarKurikulum', 'radarSemester', 'semesterList',
            'cplList', 'angkatanDatasets', 'mahasiswaDatasets', 'mahasiswaList',
            'plList', 'angkatanDatasetsPerPl',
        ));
    }
}
