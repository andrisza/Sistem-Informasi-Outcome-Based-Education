<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JurnalMengajar;
use App\Models\RpsHeader;
use App\Models\RpsPertemuan;
use Illuminate\Http\Request;

class JurnalMengajarController extends Controller
{
    /**
     * List jurnal dosen yang login.
     */
    public function index(Request $request)
    {
        $dosenId = auth()->id();

        $query = JurnalMengajar::with(['pertemuan.rps.mataKuliah', 'pertemuan.rps.semester'])
            ->where('id_dosen', $dosenId);

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pelaksanaan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pelaksanaan', $request->tahun);
        }

        $jurnalList = $query->orderByDesc('tanggal_pelaksanaan')->paginate(20);

        return view('dosen.jurnal.index', compact('jurnalList'));
    }

    /**
     * Form tambah jurnal.
     */
    public function create()
    {
        $dosenId = auth()->id();

        // Ambil RPS milik dosen ini beserta pertemuannya
        $rpsList = RpsHeader::with(['mataKuliah', 'semester', 'pertemuan'])
            ->where('id_dosen_pengembang', $dosenId)
            ->whereIn('status', ['review', 'disahkan'])
            ->orderByDesc('id')
            ->get();

        return view('dosen.jurnal.create', compact('rpsList'));
    }

    /**
     * Simpan jurnal baru.
     */
    public function store(Request $request)
    {
        $dosenId = auth()->id();

        $validated = $request->validate([
            'id_rps_pertemuan'   => 'required|integer|exists:rps_pertemuan,id',
            'tanggal_pelaksanaan'=> 'required|date',
            'realisasi_materi'   => 'required|string',
            'jumlah_hadir'       => 'required|integer|min:0',
            'catatan'            => 'nullable|string',
        ]);

        // Pastikan pertemuan memang milik RPS dosen ini
        $pertemuan = RpsPertemuan::with('rps')->findOrFail($validated['id_rps_pertemuan']);
        if ($pertemuan->rps->id_dosen_pengembang !== $dosenId) {
            abort(403);
        }

        JurnalMengajar::create(array_merge($validated, [
            'id_dosen'   => $dosenId,
            'created_at' => now(),
        ]));

        return redirect()->route('dosen.jurnal.index')
            ->with('success', 'Jurnal mengajar berhasil disimpan.');
    }

    /**
     * Detail jurnal.
     */
    public function show(JurnalMengajar $jurnal)
    {
        $this->authorizeJurnal($jurnal);

        $jurnal->load(['pertemuan.rps.mataKuliah', 'pertemuan.rps.semester', 'dosen']);

        return view('dosen.jurnal.show', compact('jurnal'));
    }

    /**
     * Form edit jurnal.
     */
    public function edit(JurnalMengajar $jurnal)
    {
        $this->authorizeJurnal($jurnal);

        $dosenId = auth()->id();

        $rpsList = RpsHeader::with(['mataKuliah', 'semester', 'pertemuan'])
            ->where('id_dosen_pengembang', $dosenId)
            ->whereIn('status', ['review', 'disahkan'])
            ->orderByDesc('id')
            ->get();

        $jurnal->load('pertemuan');

        return view('dosen.jurnal.edit', compact('jurnal', 'rpsList'));
    }

    /**
     * Update jurnal.
     */
    public function update(Request $request, JurnalMengajar $jurnal)
    {
        $this->authorizeJurnal($jurnal);

        $dosenId = auth()->id();

        $validated = $request->validate([
            'id_rps_pertemuan'    => 'required|integer|exists:rps_pertemuan,id',
            'tanggal_pelaksanaan' => 'required|date',
            'realisasi_materi'    => 'required|string',
            'jumlah_hadir'        => 'required|integer|min:0',
            'catatan'             => 'nullable|string',
        ]);

        // Verifikasi pertemuan
        $pertemuan = RpsPertemuan::with('rps')->findOrFail($validated['id_rps_pertemuan']);
        if ($pertemuan->rps->id_dosen_pengembang !== $dosenId) {
            abort(403);
        }

        $jurnal->update($validated);

        return redirect()->route('dosen.jurnal.show', $jurnal)
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    // ── Helpers ────────────────────────────────────────────────

    private function authorizeJurnal(JurnalMengajar $jurnal): void
    {
        if ($jurnal->id_dosen !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }
    }
}
