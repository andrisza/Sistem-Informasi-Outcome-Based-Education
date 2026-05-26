<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentMk;
use App\Models\MataKuliah;
use App\Models\RepositoriMateri;
use App\Models\SemesterAkademik;

class MateriController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user();

        // Ambil semua MK yang pernah diikuti mahasiswa
        $enrolledMkIds = EnrollmentMk::where('id_mahasiswa', $mahasiswa->id)
            ->pluck('id_mk')
            ->unique();

        // Query materi dari MK yang diikuti
        $query = RepositoriMateri::with(['mataKuliah', 'semester', 'dosen'])
            ->whereIn('id_mk', $enrolledMkIds)
            ->orderByDesc('created_at');

        // Filter by MK
        $filterMk = request('mk');
        if ($filterMk) {
            $query->where('id_mk', $filterMk);
        }

        // Filter by jenis_file
        $filterJenis = request('jenis');
        if ($filterJenis) {
            $query->where('jenis_file', $filterJenis);
        }

        $materiList = $query->get();

        // Daftar MK untuk filter dropdown
        $mkList = MataKuliah::whereIn('id', $enrolledMkIds)->orderBy('nama_mk')->get();

        // Daftar jenis_file unik untuk filter dropdown
        $jenisList = RepositoriMateri::whereIn('id_mk', $enrolledMkIds)
            ->distinct('jenis_file')
            ->pluck('jenis_file')
            ->filter()
            ->sort()
            ->values();

        return view('mahasiswa.materi.index', compact(
            'materiList',
            'mkList',
            'jenisList',
            'filterMk',
            'filterJenis',
        ));
    }

    public function show(RepositoriMateri $materi)
    {
        $mahasiswa = auth()->user();

        // Verifikasi mahasiswa terdaftar di MK materi ini
        $enrolled = EnrollmentMk::where('id_mahasiswa', $mahasiswa->id)
            ->where('id_mk', $materi->id_mk)
            ->exists();

        if (! $enrolled) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $materi->load(['mataKuliah', 'semester', 'dosen', 'pertemuan']);

        return view('mahasiswa.materi.show', compact('materi'));
    }
}
