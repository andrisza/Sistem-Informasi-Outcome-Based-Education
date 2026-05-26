<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RpsHeader;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;

class RpsApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = RpsHeader::with(['mataKuliah', 'dosenPengembang', 'semester'])
                          ->where('status', 'review')
                          ->orderByDesc('updated_at');

        if ($search = $request->search) {
            $query->whereHas('mataKuliah', fn ($q) => $q->where('nama_mk', 'like', "%{$search}%")
                                                         ->orWhere('kode_mk', 'like', "%{$search}%"))
                  ->orWhereHas('dosenPengembang', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($semesterId = $request->semester) {
            $query->where('id_semester', $semesterId);
        }

        $rpsList   = $query->paginate(20)->withQueryString();
        $semesters = SemesterAkademik::orderByDesc('id')->get();

        return view('kaprodi.rps-approval.index', compact('rpsList', 'semesters'));
    }

    public function show(RpsHeader $rps)
    {
        $rps->load(['mataKuliah', 'dosenPengembang', 'kaprodiPengesah', 'semester', 'pertemuan']);

        return view('kaprodi.rps-approval.show', compact('rps'));
    }

    public function approve(Request $request, RpsHeader $rps)
    {
        $rps->update([
            'status'             => 'disahkan',
            'disahkan_pada'      => now(),
            'id_kaprodi_pengesah'=> auth()->id(),
        ]);

        ActivityLog::record('approve_rps', RpsHeader::class, $rps->id);

        return redirect()->route('kaprodi.rps-approval.index')
                         ->with('success', "RPS {$rps->mataKuliah->nama_mk} berhasil disahkan.");
    }

    public function reject(Request $request, RpsHeader $rps)
    {
        $rps->update([
            'status'              => 'draft',
            'id_kaprodi_pengesah' => null,
        ]);

        ActivityLog::record('reject_rps', RpsHeader::class, $rps->id, [], ['catatan' => $request->catatan]);

        return redirect()->route('kaprodi.rps-approval.index')
                         ->with('success', "RPS {$rps->mataKuliah->nama_mk} dikembalikan ke draft.");
    }
}
