<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Kurikulum;
use App\Traits\GeneratesObeKode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KurikulumController extends Controller
{
    use GeneratesObeKode;

    public function index()
    {
        $status = request('status');
        $search = request('search');

        $kurikulumList = Kurikulum::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('nama_kurikulum', 'like', "%{$search}%")
                   ->orWhere('kode', 'like', "%{$search}%")
                   ->orWhere('program_studi', 'like', "%{$search}%");
            }))
            ->withCount(['mataKuliah', 'cplProdi', 'pl'])
            ->orderBy('tahun_mulai', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('kurikulum.index', compact('kurikulumList', 'status', 'search'));
    }

    public function create()
    {
        // Kode akan diisi via JS real-time di form berdasarkan program_studi + jenjang + tahun
        $currentYear    = now()->year;
        $activeSemester = \App\Models\SemesterAkademik::where('is_aktif', 1)->first();
        return view('kurikulum.create', compact('currentYear', 'activeSemester'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'           => 'nullable|string|max:50|unique:kurikulum,kode',
            'nama_kurikulum' => 'required|string|max:255',
            'program_studi'  => 'required|string|max:255',
            'jenjang'        => 'required|in:D3,D4,S1,S2,S3',
            'tahun_mulai'    => 'required|integer|min:2000|max:2100',
            'tahun_selesai'  => 'nullable|integer|min:2000|max:2100|gte:tahun_mulai',
            'visi'           => 'nullable|string',
            'misi'           => 'nullable|string',
            'tujuan'         => 'nullable|string',
            'sasaran'        => 'nullable|string',
        ]);

        // Auto-generate kode jika tidak diisi (atau dikosongkan)
        if (empty($validated['kode'])) {
            $validated['kode'] = $this->generateKodeKurikulum(
                $validated['program_studi'],
                $validated['jenjang'],
                (int) $validated['tahun_mulai']
            );
        }

        $validated['status']      = 'draft';
        $validated['dibuat_oleh'] = Auth::id();

        $kurikulum = Kurikulum::create($validated);

        ActivityLog::record('create', Kurikulum::class, $kurikulum->id, [], $kurikulum->only(['kode', 'nama_kurikulum', 'status']));

        return redirect()
            ->route('kurikulum.show', $kurikulum)
            ->with('success', 'Kurikulum berhasil dibuat.');
    }

    public function show(Kurikulum $kurikulum)
    {
        $kurikulum->loadCount(['pl', 'cplProdi', 'bahanKajian', 'mataKuliah', 'periode', 'arsipRapat']);

        return view('kurikulum.show', compact('kurikulum'));
    }

    public function edit(Kurikulum $kurikulum)
    {
        return view('kurikulum.edit', compact('kurikulum'));
    }

    public function update(Request $request, Kurikulum $kurikulum)
    {
        // Hanya anggota tim kurikulum yang terdaftar atau kaprodi yang boleh mengubah
        Gate::authorize('modify-kurikulum', $kurikulum);

        // Kode kurikulum TIDAK diupdate — generated sekali saat create, immutable setelahnya
        $validated = $request->validate([
            'nama_kurikulum' => 'required|string|max:255',
            'program_studi'  => 'required|string|max:255',
            'jenjang'        => 'required|in:D3,D4,S1,S2,S3',
            'tahun_mulai'    => 'required|integer|min:2000|max:2100',
            'tahun_selesai'  => 'nullable|integer|min:2000|max:2100|gte:tahun_mulai',
            'visi'           => 'nullable|string',
            'misi'           => 'nullable|string',
            'tujuan'         => 'nullable|string',
            'sasaran'        => 'nullable|string',
        ]);

        $old = $kurikulum->only(array_keys($validated));
        $kurikulum->update($validated);

        ActivityLog::record('update', Kurikulum::class, $kurikulum->id, $old, $validated);

        return redirect()
            ->route('kurikulum.show', $kurikulum)
            ->with('success', 'Kurikulum berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum)
    {
        Gate::authorize('modify-kurikulum', $kurikulum);

        if ($kurikulum->status !== 'draft') {
            return back()->with('error', 'Hanya kurikulum berstatus Draft yang dapat dihapus.');
        }

        ActivityLog::record('delete', Kurikulum::class, $kurikulum->id, $kurikulum->only(['kode', 'nama_kurikulum']), []);

        $kurikulum->delete();

        return redirect()
            ->route('kurikulum.index')
            ->with('success', 'Kurikulum berhasil dihapus.');
    }

    public function arsip(Kurikulum $kurikulum)
    {
        Gate::authorize('arsip-kurikulum');

        // Hanya kurikulum berstatus 'aktif' yang dapat diarsipkan.
        // Draft belum layak diarsipkan; yang sudah arsip tidak perlu diproses ulang.
        if ($kurikulum->status !== 'aktif') {
            $pesan = match ($kurikulum->status) {
                'draft' => 'Kurikulum harus diaktifkan terlebih dahulu sebelum dapat diarsipkan.',
                'arsip' => 'Kurikulum sudah berstatus Arsip.',
                default => 'Status kurikulum tidak valid untuk diarsipkan.',
            };
            return back()->with('error', $pesan);
        }

        $old = $kurikulum->only(['status']);

        $kurikulum->update([
            'status'    => 'arsip',
            'locked_at' => now(),
            'locked_by' => Auth::id(),
        ]);

        ActivityLog::record('arsip', Kurikulum::class, $kurikulum->id, $old, ['status' => 'arsip']);

        return back()->with('success', "Kurikulum {$kurikulum->kode} berhasil diarsipkan dan dikunci dari perubahan.");
    }

    public function aktifkan(Kurikulum $kurikulum)
    {
        Gate::authorize('arsip-kurikulum');

        // Hanya status 'draft' atau 'arsip' yang bisa diaktifkan.
        if ($kurikulum->status === 'aktif') {
            return back()->with('error', 'Kurikulum sudah berstatus Aktif.');
        }

        $old = $kurikulum->only(['status']);

        $kurikulum->update([
            'status'    => 'aktif',
            'locked_at' => null,
            'locked_by' => null,
        ]);

        ActivityLog::record('aktifkan', Kurikulum::class, $kurikulum->id, $old, ['status' => 'aktif']);

        return back()->with('success', "Kurikulum {$kurikulum->kode} berhasil diaktifkan.");
    }

    /**
     * Clone kurikulum beserta data PL, CPL, BK, MK (tanpa pivot dan CPMK detail).
     */
    public function clone(Request $request, Kurikulum $kurikulum)
    {
        Gate::authorize('arsip-kurikulum');

        $validated = $request->validate([
            'kode'        => 'required|string|max:50|unique:kurikulum,kode',
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
        ]);

        $newKurikulum = $kurikulum->replicate();
        $newKurikulum->kode        = $validated['kode'];
        $newKurikulum->tahun_mulai = $validated['tahun_mulai'];
        $newKurikulum->tahun_selesai = null;
        $newKurikulum->status      = 'draft';
        $newKurikulum->dibuat_oleh = Auth::id();
        $newKurikulum->locked_at   = null;
        $newKurikulum->locked_by   = null;
        $newKurikulum->save();

        ActivityLog::record('clone', Kurikulum::class, $newKurikulum->id, [], [
            'kode' => $newKurikulum->kode,
            'sumber_id' => $kurikulum->id,
        ]);

        return redirect()
            ->route('kurikulum.show', $newKurikulum)
            ->with('success', "Kurikulum berhasil di-clone dari {$kurikulum->kode}.");
    }
}
