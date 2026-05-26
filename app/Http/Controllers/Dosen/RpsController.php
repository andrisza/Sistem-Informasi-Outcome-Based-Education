<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengampuanMk;
use App\Models\RpsHeader;
use App\Models\RpsPertemuan;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;

class RpsController extends Controller
{
    /**
     * List RPS milik dosen yang login.
     */
    public function index(Request $request)
    {
        $dosenId = auth()->id();

        // Tampilkan RPS yang dikembangkan dosen ini ATAU MK yang ia ampu (apapun perannya)
        $pengampuMkIds = PengampuanMk::where('id_dosen', $dosenId)->pluck('id_mk')->unique();

        $query = RpsHeader::with(['mataKuliah', 'semester'])
            ->where(function ($q) use ($dosenId, $pengampuMkIds) {
                $q->where('id_dosen_pengembang', $dosenId)
                  ->orWhereIn('id_mk', $pengampuMkIds);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('semester')) {
            $query->where('id_semester', $request->semester);
        }

        $rpsList  = $query->orderByDesc('tanggal_penyusunan')->paginate(15);
        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->get();

        return view('dosen.rps.index', compact('rpsList', 'semesters'));
    }

    /**
     * Form buat RPS baru.
     */
    public function create()
    {
        $dosenId = auth()->id();

        $pengampuan = PengampuanMk::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId)
            ->get();

        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->get();

        return view('dosen.rps.create', compact('pengampuan', 'semesters'));
    }

    /**
     * Simpan RPS baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mk'               => 'required|integer|exists:mata_kuliah,id',
            'id_semester'         => 'required|integer|exists:semester_akademik,id',
            'tanggal_penyusunan'  => 'required|date',
            'kode_dokumen'        => 'required|string|max:100',
        ]);

        $dosenId = auth()->id();

        // Pastikan dosen mengampu MK di semester tersebut
        $pengampuan = PengampuanMk::where('id_dosen', $dosenId)
            ->where('id_mk', $validated['id_mk'])
            ->where('id_semester', $validated['id_semester'])
            ->first();

        if (! $pengampuan) {
            return back()->withErrors(['id_mk' => 'Anda tidak mengampu MK ini di semester tersebut.'])->withInput();
        }

        // Cek duplikat — unique key uk_rps_mk_smt (id_mk, id_semester)
        $existing = RpsHeader::where('id_mk', $validated['id_mk'])
            ->where('id_semester', $validated['id_semester'])
            ->first();

        if ($existing) {
            $statusLabel = ['draft' => 'Draft', 'review' => 'Sedang Direview', 'disahkan' => 'Sudah Disahkan'][$existing->status] ?? $existing->status;
            return redirect()->route('dosen.rps.show', $existing)
                ->with('warning', "RPS untuk MK ini di semester tersebut sudah ada (status: {$statusLabel}). Berikut adalah RPS yang ada.");
        }

        RpsHeader::create([
            'id_mk'               => $validated['id_mk'],
            'id_semester'         => $validated['id_semester'],
            'id_dosen_pengembang' => $dosenId,
            'tanggal_penyusunan'  => $validated['tanggal_penyusunan'],
            'kode_dokumen'        => $validated['kode_dokumen'],
            'status'              => 'draft',
        ]);

        return redirect()->route('dosen.rps.index')
            ->with('success', 'RPS berhasil dibuat.');
    }

    /**
     * Detail RPS.
     */
    public function show(RpsHeader $rps)
    {
        $this->authorizeRps($rps, readOnly: true);

        $rps->load(['mataKuliah', 'semester', 'pertemuan']);

        // Buat array 16 minggu dengan data yang sudah ada
        $pertemuanMap = $rps->pertemuan->keyBy('minggu_ke');
        $mingguList   = collect(range(1, 16))->map(fn($i) => [
            'minggu_ke' => $i,
            'data'      => $pertemuanMap->get($i),
        ]);

        return view('dosen.rps.show', compact('rps', 'mingguList'));
    }

    /**
     * Form edit RPS.
     */
    public function edit(RpsHeader $rps)
    {
        $this->authorizeRps($rps);

        if ($rps->status === 'disahkan') {
            return redirect()->route('dosen.rps.show', $rps)
                ->with('error', 'RPS yang sudah disahkan tidak dapat diedit.');
        }

        $dosenId    = auth()->id();
        $pengampuan = PengampuanMk::with(['mataKuliah', 'semester'])
            ->where('id_dosen', $dosenId)
            ->get();

        $semesters = SemesterAkademik::orderByDesc('tahun_akademik')->get();

        return view('dosen.rps.edit', compact('rps', 'pengampuan', 'semesters'));
    }

    /**
     * Update RPS.
     */
    public function update(Request $request, RpsHeader $rps)
    {
        $this->authorizeRps($rps);

        if ($rps->status === 'disahkan') {
            return redirect()->route('dosen.rps.show', $rps)
                ->with('error', 'RPS yang sudah disahkan tidak dapat diedit.');
        }

        $validated = $request->validate([
            'id_mk'              => 'required|integer|exists:mata_kuliah,id',
            'id_semester'        => 'required|integer|exists:semester_akademik,id',
            'tanggal_penyusunan' => 'required|date',
            'kode_dokumen'       => 'required|string|max:100',
        ]);

        $rps->update($validated);

        return redirect()->route('dosen.rps.show', $rps)
            ->with('success', 'RPS berhasil diperbarui.');
    }

    /**
     * Hapus RPS (hanya draft).
     */
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

    /**
     * Ubah status draft → review (submit ke kaprodi).
     */
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

    /**
     * List 16 pertemuan RPS.
     */
    public function pertemuanIndex(RpsHeader $rps)
    {
        $this->authorizeRps($rps, readOnly: true);

        $rps->load(['mataKuliah', 'semester', 'pertemuan']);

        $pertemuanMap = $rps->pertemuan->keyBy('minggu_ke');
        $mingguList   = collect(range(1, 16))->map(fn($i) => [
            'minggu_ke' => $i,
            'data'      => $pertemuanMap->get($i),
        ]);

        return view('dosen.rps.pertemuan.index', compact('rps', 'mingguList'));
    }

    /**
     * Form detail/edit satu pertemuan.
     */
    public function pertemuanShow(RpsHeader $rps, int $minggu)
    {
        $this->authorizeRps($rps);

        if ($minggu < 1 || $minggu > 16) {
            abort(404, 'Minggu tidak valid.');
        }

        $rps->load(['mataKuliah', 'semester']);

        $pertemuan = RpsPertemuan::where('id_rps', $rps->id)
            ->where('minggu_ke', $minggu)
            ->first();

        return view('dosen.rps.pertemuan.edit', compact('rps', 'pertemuan', 'minggu'));
    }

    /**
     * Upsert satu pertemuan (by id_rps + minggu_ke).
     */
    public function pertemuanUpdate(Request $request, RpsHeader $rps, int $minggu)
    {
        $this->authorizeRps($rps);

        if ($rps->status === 'disahkan') {
            return back()->with('error', 'RPS yang sudah disahkan tidak dapat diubah.');
        }

        if ($minggu < 1 || $minggu > 16) {
            abort(404, 'Minggu tidak valid.');
        }

        $validated = $request->validate([
            'materi_pembelajaran' => 'required|string',
            'metode_pembelajaran' => 'nullable|string|max:255',
            'indikator_penilaian' => 'nullable|string',
            'estimasi_waktu'      => 'nullable|string|max:100',
            'media_pembelajaran'  => 'nullable|string|max:255',
            'referensi'           => 'nullable|string',
        ]);

        RpsPertemuan::updateOrCreate(
            ['id_rps' => $rps->id, 'minggu_ke' => $minggu],
            $validated
        );

        // Tentukan ke mana redirect setelah simpan
        if ($request->input('action') === 'next' && $minggu < 16) {
            return redirect()->route('dosen.rps.pertemuan.show', [$rps, $minggu + 1])
                ->with('success', "Pertemuan {$minggu} berhasil disimpan.");
        }

        return redirect()->route('dosen.rps.pertemuan.index', $rps)
            ->with('success', "Pertemuan {$minggu} berhasil disimpan.");
    }

    // ── Helpers ────────────────────────────────────────────────

    /**
     * Otorisasi akses RPS.
     *
     * Aturan akses:
     *   - Dosen pengembang RPS         → akses penuh (lihat/edit/submit/hapus)
     *   - Koordinator pengampu MK ini  → akses penuh (boleh submit/edit)
     *   - Pengampu biasa (non-koord)   → hanya boleh lihat ($readOnly = true)
     *
     * @param bool $readOnly true → dosen pengampu biasa juga boleh lihat.
     */
    private function authorizeRps(RpsHeader $rps, bool $readOnly = false): void
    {
        $userId = auth()->id();

        // 1. Dosen pengembang selalu memiliki akses penuh
        if ($rps->id_dosen_pengembang === $userId) {
            return;
        }

        // 2. Koordinator pengampu MK ini di semester yang sama → akses penuh
        $isKoordinator = PengampuanMk::where('id_dosen', $userId)
            ->where('id_mk', $rps->id_mk)
            ->where('id_semester', $rps->id_semester)
            ->where('is_koordinator', 1)
            ->exists();

        if ($isKoordinator) {
            return;
        }

        // 3. Untuk aksi baca saja, pengampu biasa juga boleh lihat
        if ($readOnly) {
            $isPengampu = PengampuanMk::where('id_dosen', $userId)
                ->where('id_mk', $rps->id_mk)
                ->where('id_semester', $rps->id_semester)
                ->exists();

            if ($isPengampu) {
                return;
            }
        }

        abort(403, 'Anda tidak memiliki akses ke RPS ini.');
    }
}
