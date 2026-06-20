<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Kurikulum;
use App\Models\MasterKategori;
use App\Models\MataKuliah;
use App\Services\ExcelExportService;
use App\Traits\GeneratesObeKode;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    use GeneratesObeKode;

    public function index(Kurikulum $kurikulum)
    {
        $mkList = $kurikulum->mataKuliah()
            ->withCount('cpmk')
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        $bySmester = $mkList->groupBy('semester');

        return view('kurikulum.mata-kuliah.index', compact('kurikulum', 'mkList', 'bySmester'));
    }

    public function create(Kurikulum $kurikulum)
    {
        // Kategori MK tidak diisi di susunan MK — dipetakan di Organisasi Mata Kuliah.
        $nextKode = $this->generateKodeMk($kurikulum);

        return view('kurikulum.mata-kuliah.create', compact('kurikulum', 'nextKode'));
    }

    public function store(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kode_mk'        => 'nullable|string|max:50|unique:mata_kuliah,kode_mk,NULL,id,id_kurikulum,' . $kurikulum->id,
            'nama_mk'        => 'required|string|max:255|unique:mata_kuliah,nama_mk,NULL,id,id_kurikulum,' . $kurikulum->id,
            'sks_teori'      => 'required|integer|min:0',
            'sks_praktikum'  => 'required|integer|min:0',
            'semester'       => 'required|integer|min:1|max:14',
            'kategori_mk'    => 'nullable|string|max:50',
            'kompetensi_mk'  => 'nullable|in:Utama,Pendukung',
            'kode_prasyarat' => 'nullable|string|max:20',
        ], [
            'nama_mk.unique' => 'Mata Kuliah dengan nama ini sudah ada dalam kurikulum yang sama.',
            'kode_mk.unique' => 'Kode MK ini sudah digunakan dalam kurikulum yang sama.',
        ]);

        if (empty($validated['kode_mk'])) {
            $validated['kode_mk'] = $this->generateKodeMk($kurikulum);
        }

        $validated['id_kurikulum'] = $kurikulum->id;
        // sks_total is a MySQL generated column (sks_teori + sks_praktikum) — must NOT be set explicitly

        $mk = MataKuliah::create($validated);
        // Observer MataKuliahObserver::saved() akan otomatis sync distribusi_semester

        ActivityLog::record('create', MataKuliah::class, $mk->id, [], $mk->only(['kode_mk', 'nama_mk', 'semester', 'sks_teori', 'sks_praktikum']));

        return redirect()
            ->route('kurikulum.mata-kuliah.index', $kurikulum)
            ->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $mataKuliah->load(['cpmk.cplProdi', 'cpmk.subCpmk']);

        return view('kurikulum.mata-kuliah.show', compact('kurikulum', 'mataKuliah'));
    }

    public function edit(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $kategoriOptions = MasterKategori::jenis('mk')->aktif()->orderBy('urutan')->pluck('nama');
        return view('kurikulum.mata-kuliah.edit', compact('kurikulum', 'mataKuliah', 'kategoriOptions'));
    }

    public function update(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'nama_mk'       => 'required|string|max:255|unique:mata_kuliah,nama_mk,' . $mataKuliah->id . ',id,id_kurikulum,' . $kurikulum->id,
            'sks_teori'     => 'required|integer|min:0',
            'sks_praktikum' => 'required|integer|min:0',
            'semester'      => 'required|integer|min:1|max:14',
            'kompetensi_mk' => 'nullable|in:Utama,Pendukung',
        ], [
            'nama_mk.unique' => 'Mata Kuliah dengan nama ini sudah ada dalam kurikulum yang sama.',
        ]);

        // sks_total is a MySQL generated column — must NOT be set explicitly
        $old = $mataKuliah->only(array_keys($validated));
        $mataKuliah->update($validated);
        // Observer MataKuliahObserver::saved() akan otomatis sync distribusi_semester

        ActivityLog::record('update', MataKuliah::class, $mataKuliah->id, $old, $validated);

        return redirect()
            ->route('kurikulum.mata-kuliah.index', $kurikulum)
            ->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function updateSemester(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        abort_unless($mataKuliah->id_kurikulum === $kurikulum->id, 403);

        $validated = $request->validate([
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $mataKuliah->update(['semester' => $validated['semester']]);

        return response()->json(['success' => true, 'semester' => $validated['semester']]);
    }

    public function updateKategori(Request $request, Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        abort_unless($mataKuliah->id_kurikulum === $kurikulum->id, 403);

        $validated = $request->validate([
            'kategori_mk' => 'nullable|string|in:Wajib,Pilihan,MKWK,MKDU',
        ]);

        $mataKuliah->update(['kategori_mk' => $validated['kategori_mk'] ?? null]);

        return response()->json(['success' => true]);
    }

    public function batchUpdateKategori(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'kategori'   => 'required|array',
            'kategori.*' => 'nullable|string|in:Wajib,Pilihan,MKWK,MKDU',
        ]);

        foreach ($validated['kategori'] as $mkId => $kategori) {
            MataKuliah::where('id', $mkId)
                ->where('id_kurikulum', $kurikulum->id)
                ->update(['kategori_mk' => $kategori ?: null]);
        }

        return redirect()
            ->route('kurikulum.organisasi-mk', $kurikulum)
            ->with('success', 'Pemetaan kategori MK berhasil disimpan.');
    }

    public function destroy(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
        $old = $mataKuliah->only(['kode_mk', 'nama_mk', 'semester']);

        $mataKuliah->delete();
        // Observer MataKuliahObserver::deleted() akan otomatis sync distribusi_semester

        ActivityLog::record('delete', MataKuliah::class, $mataKuliah->id, $old, []);

        return redirect()
            ->route('kurikulum.mata-kuliah.index', $kurikulum)
            ->with('success', 'Mata Kuliah berhasil dihapus.');
    }

    public function export(Kurikulum $kurikulum, ExcelExportService $excel)
    {
        $mkList = $kurikulum->mataKuliah()
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        $headerRow = [
            ['label' => 'No', 'bg' => 'F59E0B'],
            ['label' => 'Kode MK', 'bg' => 'F59E0B'],
            ['label' => 'Nama MK', 'bg' => 'F59E0B'],
            ['label' => 'Semester', 'bg' => 'F59E0B'],
            ['label' => 'SKS Teori', 'bg' => 'F59E0B'],
            ['label' => 'SKS Praktikum', 'bg' => 'F59E0B'],
            ['label' => 'Kompetensi', 'bg' => 'F59E0B'],
        ];

        $rows = $mkList->map(fn ($mk, $i) => [
            $i + 1,
            $mk->kode_mk,
            $mk->nama_mk,
            $mk->semester,
            $mk->sks_teori,
            $mk->sks_praktikum,
            $mk->kompetensi_mk,
        ])->all();

        return $excel->download("mata-kuliah-{$kurikulum->kode}.xlsx", [
            'Mata Kuliah' => [
                'headerRows' => [$headerRow],
                'rows'       => $rows,
                'colWidths'  => [5, 14, 50, 12, 12, 14, 14],
            ],
        ]);
    }

    private function generateKodeMk(Kurikulum $kurikulum): string
    {
        // Format: MK{nn} — contoh: MK01, MK02, MK51
        $count = $kurikulum->mataKuliah()->withTrashed()->count() + 1;
        return 'MK' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }
}
