<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MataKuliah;
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
            'status'              => 'disahkan',
            'disahkan_pada'       => now(),
            'id_kaprodi_pengesah' => auth()->id(),
        ]);

        // Sinkronisasi SKS teori/praktikum ke mata kuliah jika diisi di RPS
        if ($rps->sks_teori !== null || $rps->sks_praktikum !== null) {
            $updates = [];
            if ($rps->sks_teori !== null)     $updates['sks_teori']     = $rps->sks_teori;
            if ($rps->sks_praktikum !== null)  $updates['sks_praktikum'] = $rps->sks_praktikum;
            MataKuliah::where('id', $rps->id_mk)->update($updates);
        }

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

    /**
     * Sahkan semua RPS yang dipilih (status review → disahkan).
     */
    public function batchApprove(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')), 'is_numeric');
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada RPS yang dipilih.');
        }

        $rpsList = RpsHeader::with('mataKuliah')
            ->whereIn('id', $ids)
            ->where('status', 'review')
            ->get();

        $count = 0;
        foreach ($rpsList as $rps) {
            $rps->update([
                'status'              => 'disahkan',
                'disahkan_pada'       => now(),
                'id_kaprodi_pengesah' => auth()->id(),
            ]);
            ActivityLog::record('approve_rps', RpsHeader::class, $rps->id);
            $count++;
        }

        return redirect()->route('kaprodi.rps-approval.index')
                         ->with('success', "{$count} RPS berhasil disahkan.");
    }

    /**
     * Kembalikan semua RPS yang dipilih ke draft.
     */
    public function batchDraft(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')), 'is_numeric');
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada RPS yang dipilih.');
        }

        $count = RpsHeader::whereIn('id', $ids)
            ->whereIn('status', ['review', 'disahkan'])
            ->update([
                'status'              => 'draft',
                'id_kaprodi_pengesah' => null,
                'disahkan_pada'       => null,
            ]);

        ActivityLog::record('batch_draft_rps', RpsHeader::class, 0, [], ['ids' => $ids, 'count' => $count]);

        return redirect()->route('kaprodi.rps-approval.index')
                         ->with('success', "{$count} RPS dikembalikan ke draft.");
    }
}
