<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\PengampuanMk;
use App\Models\RpsHeader;
use App\Models\RpsPertemuan;
use App\Models\SemesterAkademik;
use App\Models\User;
use Illuminate\Http\Request;

class RpsController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $dosenId      = auth()->id();
        $pengampuMkIds = PengampuanMk::where('id_dosen', $dosenId)->pluck('id_mk')->unique();

        $query = RpsHeader::with(['mataKuliah', 'semester'])
            ->where(function ($q) use ($dosenId, $pengampuMkIds) {
                $q->where('id_dosen_pengembang', $dosenId)
                  ->orWhereIn('id_mk', $pengampuMkIds);
            });

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('semester')) $query->where('id_semester', $request->semester);

        $rpsList   = $query->orderByDesc('tanggal_penyusunan')->paginate(15);
        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->get();

        return view('dosen.rps.index', compact('rpsList', 'semesters'));
    }

    // ── Create / Store ─────────────────────────────────────────────────────────

    public function create()
    {
        $dosenId    = auth()->id();
        $pengampuan = PengampuanMk::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId)
            ->get();
        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->get();

        return view('dosen.rps.create', compact('pengampuan', 'semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mk'              => 'required|integer|exists:mata_kuliah,id',
            'id_semester'        => 'required|integer|exists:semester_akademik,id',
            'tanggal_penyusunan' => 'required|date',
            'kode_dokumen'       => 'nullable|string|max:100',
        ]);

        $dosenId = auth()->id();

        $pengampuan = PengampuanMk::where('id_dosen', $dosenId)
            ->where('id_mk', $validated['id_mk'])
            ->where('id_semester', $validated['id_semester'])
            ->first();

        if (! $pengampuan) {
            return back()->withErrors(['id_mk' => 'Anda tidak mengampu MK ini di semester tersebut.'])->withInput();
        }

        if (empty($validated['kode_dokumen'])) {
            $mk  = MataKuliah::find($validated['id_mk']);
            $sem = SemesterAkademik::find($validated['id_semester']);
            $validated['kode_dokumen'] = $this->generateKodeRps($mk, $sem);
        }

        $existing = RpsHeader::where('id_mk', $validated['id_mk'])
            ->where('id_semester', $validated['id_semester'])
            ->first();

        if ($existing) {
            $statusLabel = ['draft' => 'Draft', 'review' => 'Sedang Direview', 'disahkan' => 'Sudah Disahkan'][$existing->status] ?? $existing->status;
            return redirect()->route('dosen.rps.show', $existing)
                ->with('warning', "RPS untuk MK ini di semester tersebut sudah ada (status: {$statusLabel}).");
        }

        $rps = RpsHeader::create([
            'id_mk'               => $validated['id_mk'],
            'id_semester'         => $validated['id_semester'],
            'id_dosen_pengembang' => $dosenId,
            'tanggal_penyusunan'  => $validated['tanggal_penyusunan'],
            'kode_dokumen'        => $validated['kode_dokumen'],
            'status'              => 'draft',
        ]);

        return redirect()->route('dosen.rps.edit', $rps)
            ->with('success', 'RPS berhasil dibuat. Silakan lengkapi isi RPS.');
    }

    // ── Show ───────────────────────────────────────────────────────────────────

    public function show(RpsHeader $rps)
    {
        $this->authorizeRps($rps, readOnly: true);

        $rps->load([
            'mataKuliah.cpmk.subCpmk',
            'mataKuliah.cplProdi',
            'mataKuliah.bahanKajian',
            'semester',
            'dosenPengembang',
            'koordinatorBk',
            'kaprodiPengesah',
            'pertemuan.subCpmk.cpmk',
        ]);

        $mk       = $rps->mataKuliah;
        $cpmkList = $mk?->cpmk?->sortBy('urutan') ?? collect();
        $subCpmkAll = $cpmkList->flatMap(fn($c) => $c->subCpmk)->sortBy('urutan');

        $pertemuanMap = $rps->pertemuan->keyBy('minggu_ke');
        $mingguList   = collect(range(1, 16))->map(fn($i) => [
            'minggu_ke' => $i,
            'data'      => $pertemuanMap->get($i),
        ]);

        return view('dosen.rps.show', compact('rps', 'mk', 'cpmkList', 'subCpmkAll', 'pertemuanMap', 'mingguList'));
    }

    // ── Edit / Update ──────────────────────────────────────────────────────────

    public function edit(RpsHeader $rps)
    {
        $this->authorizeRps($rps);

        $rps->load([
            'mataKuliah.cpmk.subCpmk',
            'mataKuliah.cplProdi',
            'mataKuliah.bahanKajian',
            'semester',
            'pertemuan.subCpmk',
        ]);

        $mk       = $rps->mataKuliah;
        $cpmkList = $mk?->cpmk?->sortBy('urutan') ?? collect();
        $subCpmkAll = $cpmkList->flatMap(fn($c) => $c->subCpmk)->sortBy('urutan');

        $dosenList  = User::where('role', 'dosen')->orderBy('name')->get();
        $kaprodiList = User::where('role', 'kaprodi')->orderBy('name')->get();

        $pertemuanMap = $rps->pertemuan->keyBy('minggu_ke');

        return view('dosen.rps.edit', compact(
            'rps', 'mk', 'cpmkList', 'subCpmkAll',
            'dosenList', 'kaprodiList', 'pertemuanMap'
        ));
    }

    public function update(Request $request, RpsHeader $rps)
    {
        $this->authorizeRps($rps);

        if ($rps->status === 'disahkan') {
            return redirect()->route('dosen.rps.show', $rps)
                ->with('error', 'RPS yang sudah disahkan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'tanggal_penyusunan'    => 'required|date',
            'kode_dokumen'          => 'required|string|max:100',
            'nama_perguruan_tinggi' => 'nullable|string|max:150',
            'nama_fakultas'         => 'nullable|string|max:150',
            'id_koordinator_bk'     => 'nullable|integer|exists:users,id',
            'id_kaprodi_pengesah'   => 'nullable|integer|exists:users,id',
            'deskripsi_mk'          => 'nullable|string',
            'materi_pembelajaran'   => 'nullable|string',
            'pustaka_utama'         => 'nullable|string',
            'pustaka_pendukung'     => 'nullable|string',
        ]);

        $rps->update($validated);

        // ── Simpan pertemuan (minggu 1–16) ─────────────────────────────────
        // Hanya simpan baris yang memiliki setidaknya satu field terisi.
        // Baris kosong di-skip untuk menghindari NOT NULL violation pada materi_pembelajaran.
        $pertemuanInput = $request->input('pertemuan', []);
        foreach ($pertemuanInput as $minggu => $data) {
            $minggu = (int) $minggu;
            if ($minggu < 1 || $minggu > 16) continue;

            $materi    = trim($data['materi_pembelajaran'] ?? '');
            $subCpmkId = filled($data['id_sub_cpmk'] ?? null) ? (int)$data['id_sub_cpmk'] : null;
            $bobot     = filled($data['bobot_penilaian'] ?? null) ? (float)$data['bobot_penilaian'] : null;

            // Cek apakah ada konten bermakna di baris ini
            $adaIsi = $materi !== ''
                || $subCpmkId !== null
                || trim($data['indikator_penilaian'] ?? '') !== ''
                || trim($data['kriteria_teknik'] ?? '') !== ''
                || trim($data['bentuk_luring'] ?? '') !== ''
                || trim($data['bentuk_daring'] ?? '') !== ''
                || $bobot !== null;

            if (! $adaIsi) {
                // Hapus record lama jika sebelumnya ada data, sekarang dikosongkan
                RpsPertemuan::where('id_rps', $rps->id)->where('minggu_ke', $minggu)->delete();
                continue;
            }

            RpsPertemuan::updateOrCreate(
                ['id_rps' => $rps->id, 'minggu_ke' => $minggu],
                [
                    'id_sub_cpmk'         => $subCpmkId,
                    'indikator_penilaian'  => $data['indikator_penilaian'] ?? null,
                    'kriteria_teknik'      => $data['kriteria_teknik'] ?? null,
                    'bentuk_luring'        => $data['bentuk_luring'] ?? null,
                    'bentuk_daring'        => $data['bentuk_daring'] ?? null,
                    'materi_pembelajaran'  => $materi !== '' ? $materi : '-',
                    'bobot_penilaian'      => $bobot,
                    'metode_pembelajaran'  => $data['metode_pembelajaran'] ?? null,
                    'estimasi_waktu'       => $data['estimasi_waktu'] ?? null,
                    'media_pembelajaran'   => $data['media_pembelajaran'] ?? null,
                ]
            );
        }

        return redirect()->route('dosen.rps.show', $rps)
            ->with('success', 'RPS berhasil disimpan.');
    }

    // ── Destroy ────────────────────────────────────────────────────────────────

    public function destroy(RpsHeader $rps)
    {
        $this->authorizeRps($rps);

        if ($rps->status !== 'draft') {
            return back()->with('error', 'Hanya RPS berstatus draft yang dapat dihapus.');
        }

        $rps->delete();

        return redirect()->route('dosen.rps.index')
            ->with('success', 'RPS berhasil dihapus.');
    }

    // ── Submit ─────────────────────────────────────────────────────────────────

    public function submit(RpsHeader $rps)
    {
        $this->authorizeRps($rps);

        if ($rps->status !== 'draft') {
            return back()->with('error', 'Hanya RPS berstatus draft yang dapat diajukan.');
        }

        $rps->update(['status' => 'review']);

        return redirect()->route('dosen.rps.show', $rps)
            ->with('success', 'RPS berhasil diajukan untuk ditinjau Kaprodi.');
    }

    // ── Print ──────────────────────────────────────────────────────────────────

    public function print(RpsHeader $rps)
    {
        $this->authorizeRps($rps, readOnly: true);

        $rps->load([
            'mataKuliah.cpmk.subCpmk',
            'mataKuliah.cplProdi',
            'mataKuliah.bahanKajian',
            'semester',
            'dosenPengembang',
            'koordinatorBk',
            'kaprodiPengesah',
            'pertemuan.subCpmk.cpmk',
        ]);

        $mk          = $rps->mataKuliah;
        $cpmkList    = $mk?->cpmk?->sortBy('urutan') ?? collect();
        $subCpmkAll  = $cpmkList->flatMap(fn($c) => $c->subCpmk)->sortBy('urutan');
        $pertemuanMap = $rps->pertemuan->keyBy('minggu_ke');
        $mingguList   = collect(range(1, 16))->map(fn($i) => [
            'minggu_ke' => $i,
            'data'      => $pertemuanMap->get($i),
        ]);

        return view('dosen.rps.print', compact(
            'rps', 'mk', 'cpmkList', 'subCpmkAll', 'pertemuanMap', 'mingguList'
        ));
    }

    // ── Pertemuan (legacy routes tetap berfungsi) ──────────────────────────────

    public function pertemuanIndex(RpsHeader $rps)
    {
        $this->authorizeRps($rps, readOnly: true);
        $rps->load(['mataKuliah', 'semester', 'pertemuan']);
        $pertemuanMap = $rps->pertemuan->keyBy('minggu_ke');
        $mingguList   = collect(range(1, 16))->map(fn($i) => ['minggu_ke' => $i, 'data' => $pertemuanMap->get($i)]);
        return view('dosen.rps.pertemuan.index', compact('rps', 'mingguList'));
    }

    public function pertemuanShow(RpsHeader $rps, int $minggu)
    {
        $this->authorizeRps($rps);
        if ($minggu < 1 || $minggu > 16) abort(404, 'Minggu tidak valid.');
        $rps->load(['mataKuliah', 'semester', 'mataKuliah.cpmk.subCpmk']);
        $pertemuan  = RpsPertemuan::where('id_rps', $rps->id)->where('minggu_ke', $minggu)->first();
        $subCpmkAll = $rps->mataKuliah?->cpmk?->sortBy('urutan')->flatMap(fn($c) => $c->subCpmk->sortBy('urutan')) ?? collect();
        return view('dosen.rps.pertemuan.edit', compact('rps', 'pertemuan', 'minggu', 'subCpmkAll'));
    }

    public function pertemuanUpdate(Request $request, RpsHeader $rps, int $minggu)
    {
        $this->authorizeRps($rps);

        if ($rps->status === 'disahkan') {
            return back()->with('error', 'RPS yang sudah disahkan tidak dapat diubah.');
        }
        if ($minggu < 1 || $minggu > 16) abort(404);

        $validated = $request->validate([
            'id_sub_cpmk'        => 'nullable|integer|exists:sub_cpmk,id',
            'indikator_penilaian' => 'nullable|string',
            'kriteria_teknik'     => 'nullable|string',
            'bentuk_luring'       => 'nullable|string|max:300',
            'bentuk_daring'       => 'nullable|string|max:300',
            'materi_pembelajaran' => 'required|string',
            'bobot_penilaian'     => 'nullable|numeric|min:0|max:100',
            'metode_pembelajaran' => 'nullable|string|max:300',
            'estimasi_waktu'      => 'nullable|string|max:100',
            'media_pembelajaran'  => 'nullable|string|max:200',
            'referensi'           => 'nullable|string',
        ]);

        RpsPertemuan::updateOrCreate(
            ['id_rps' => $rps->id, 'minggu_ke' => $minggu],
            $validated
        );

        if ($request->input('action') === 'next' && $minggu < 16) {
            return redirect()->route('dosen.rps.pertemuan.show', [$rps, $minggu + 1])
                ->with('success', "Pertemuan {$minggu} berhasil disimpan.");
        }

        return redirect()->route('dosen.rps.pertemuan.index', $rps)
            ->with('success', "Pertemuan {$minggu} berhasil disimpan.");
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    private function generateKodeRps(MataKuliah $mk, SemesterAkademik $semester): string
    {
        $semAbbr = match($semester->jenis) {
            'Ganjil' => 'GNJ',
            'Genap'  => 'GNP',
            'Pendek' => 'PDK',
            default  => 'SEM',
        };
        $tahunParts = explode('/', $semester->tahun_akademik);
        $yearAbbr   = substr($tahunParts[0] ?? date('Y'), 2, 2)
                    . substr($tahunParts[1] ?? date('Y'), 2, 2);
        return 'RPS/' . $mk->kode_mk . '/' . $semAbbr . '-' . $yearAbbr;
    }

    private function authorizeRps(RpsHeader $rps, bool $readOnly = false): void
    {
        $userId = auth()->id();
        if ($rps->id_dosen_pengembang == $userId) return;

        $isPengampuSamaSemester = PengampuanMk::where('id_dosen', $userId)
            ->where('id_mk', $rps->id_mk)
            ->where('id_semester', $rps->id_semester)
            ->exists();
        if ($isPengampuSamaSemester) return;

        if ($readOnly) {
            $isPengampu = PengampuanMk::where('id_dosen', $userId)->where('id_mk', $rps->id_mk)->exists();
            if ($isPengampu) return;
        }

        abort(403, 'Anda tidak memiliki akses ke RPS ini.');
    }
}
