<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LogEvaluasiCqi;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CqiController extends Controller
{
    public function index(Request $request)
    {
        $query = LogEvaluasiCqi::with(['mataKuliah', 'kurikulum', 'semester'])
                               ->orderByDesc('created_at');

        if ($search = $request->search) {
            $query->whereHas('mataKuliah', fn ($q) => $q->where('nama_mk', 'like', "%{$search}%"));
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($semesterId = $request->semester) {
            $query->where('id_semester', $semesterId);
        }

        $cqiLogs   = $query->paginate(20)->withQueryString();
        $semesters = SemesterAkademik::orderByDesc('id')->get();

        return view('kaprodi.cqi.index', compact('cqiLogs', 'semesters'));
    }

    public function show(LogEvaluasiCqi $log)
    {
        $log->load(['mataKuliah', 'kurikulum', 'semester', 'cplTerdampak', 'dilaporkanOleh', 'disetujuiOleh']);

        return view('kaprodi.cqi.show', compact('log'));
    }

    public function setujui(Request $request, LogEvaluasiCqi $log)
    {
        $log->update(['disetujui_oleh' => auth()->id()]);

        ActivityLog::record('setujui_cqi', LogEvaluasiCqi::class, $log->id);

        return back()->with('success', 'Evaluasi CQI berhasil disetujui.');
    }

    public function update(Request $request, LogEvaluasiCqi $log)
    {
        $request->validate([
            'status' => ['required', Rule::in(['belum', 'proses', 'selesai'])],
        ]);

        $old = $log->only(['status']);

        $log->update(['status' => $request->status]);

        ActivityLog::record('update_cqi_status', LogEvaluasiCqi::class, $log->id, $old, ['status' => $request->status]);

        return back()->with('success', 'Status CQI diperbarui.');
    }
}
