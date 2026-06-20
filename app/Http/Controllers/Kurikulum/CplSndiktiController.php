<?php
namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\CplSndikti;
use App\Models\Kurikulum;
use App\Models\MasterKategori;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CplSndiktiController extends Controller
{
    public function index(Kurikulum $kurikulum)
    {
        $cplsnList = CplSndikti::orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get();
        return view('kurikulum.cpl-sndikti.index', compact('kurikulum', 'cplsnList'));
    }

    /**
     * Daftar kategori aktif dari master_kategori (jenis 'cpl').
     * $include opsional menambahkan nilai lama agar tetap valid saat edit.
     */
    private function kategoriOptions(?string $include = null): array
    {
        $options = MasterKategori::jenis('cpl')->aktif()->orderBy('urutan')->pluck('nama');

        if ($include && !$options->contains($include)) {
            $options->push($include);
        }

        return $options->all();
    }

    public function create(Kurikulum $kurikulum)
    {
        $kategoriOptions = $this->kategoriOptions();
        return view('kurikulum.cpl-sndikti.create', compact('kurikulum', 'kategoriOptions'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kategori'  => ['required', Rule::in($this->kategoriOptions())],
            'deskripsi' => 'required|string',
        ]);

        $prefix = match($validated['kategori']) {
            'Sikap'               => 'CPL-S',
            'Keterampilan Umum'   => 'CPL-KU',
            'Keterampilan Khusus' => 'CPL-KK',
            'Pengetahuan'         => 'CPL-P',
            default               => 'CPL-X',
        };

        $lastUrutan = CplSndikti::where('kategori', $validated['kategori'])->max('urutan') ?? 0;
        $nextUrutan = $lastUrutan + 1;

        CplSndikti::create([
            'kode'      => $prefix . str_pad($nextUrutan, 2, '0', STR_PAD_LEFT),
            'deskripsi' => $validated['deskripsi'],
            'kategori'  => $validated['kategori'],
            'urutan'    => $nextUrutan,
        ]);

        return redirect()
            ->route('kurikulum.cpl-sndikti.index', $kurikulum)
            ->with('success', 'CPL SN-Dikti berhasil ditambahkan.');
    }

    public function edit(Kurikulum $kurikulum, CplSndikti $cplSndikti)
    {
        // Sertakan nilai lama agar tetap valid walau kategorinya sudah dinonaktifkan.
        $kategoriOptions = $this->kategoriOptions($cplSndikti->kategori);
        return view('kurikulum.cpl-sndikti.edit', compact('kurikulum', 'cplSndikti', 'kategoriOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, CplSndikti $cplSndikti)
    {
        $validated = $request->validate([
            'kategori'  => ['required', Rule::in($this->kategoriOptions($cplSndikti->kategori))],
            'deskripsi' => 'required|string',
        ]);
        $cplSndikti->update($validated);

        return redirect()
            ->route('kurikulum.cpl-sndikti.index', $kurikulum)
            ->with('success', 'CPL SN-Dikti berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum, CplSndikti $cplSndikti)
    {
        $cplSndikti->delete();

        return redirect()
            ->route('kurikulum.cpl-sndikti.index', $kurikulum)
            ->with('success', 'CPL SN-Dikti berhasil dihapus.');
    }

    public function export(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $cplsnList = CplSndikti::orderByRaw("FIELD(kategori,'Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan')")
            ->orderBy('urutan')
            ->get();

        $headerRow = [
            ['label' => 'No', 'bg' => 'F59E0B'],
            ['label' => 'Kategori', 'bg' => 'F59E0B'],
            ['label' => 'Kode', 'bg' => 'F59E0B'],
            ['label' => 'Deskripsi', 'bg' => 'F59E0B'],
            ['label' => 'Status', 'bg' => 'F59E0B'],
        ];

        $rows = $cplsnList->map(fn ($cplsn, $i) => [
            $i + 1,
            $cplsn->kategori,
            $cplsn->kode,
            $cplsn->deskripsi,
            ucfirst($cplsn->status ?? 'draft'),
        ])->all();

        return $excel->download("cpl-sndikti-{$kurikulum->kode}.xlsx", [
            'CPL SN-Dikti' => [
                'headerRows' => [$headerRow],
                'rows'       => $rows,
                'colWidths'  => [5, 22, 14, 70, 12],
            ],
        ]);
    }

    public function approve(Kurikulum $kurikulum, CplSndikti $cplSndikti)
    {
        Gate::authorize('arsip-kurikulum');

        $cplSndikti->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('kurikulum.cpl-sndikti.index', $kurikulum)
            ->with('success', "CPL SN-Dikti {$cplSndikti->kode} berhasil disetujui.");
    }

    public function batchApprove(Request $request, Kurikulum $kurikulum)
    {
        Gate::authorize('arsip-kurikulum');

        $ids = array_filter(array_map('intval', $request->input('ids', [])));
        if (empty($ids)) {
            return back()->with('error', 'Pilih setidaknya satu CPL SN-Dikti.');
        }

        $count = \App\Models\CplSndikti::whereIn('id', $ids)
            ->where('status', 'draft')
            ->update(['status' => 'approved', 'approved_by' => Auth::id(), 'approved_at' => now()]);

        return back()->with('success', "$count CPL SN-Dikti berhasil disetujui.");
    }
}
