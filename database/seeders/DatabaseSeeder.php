<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\ActivityLog;
use App\Models\ArsipRapat;
use App\Models\BahanKajian;
use App\Models\BatasKetercapaian;
use App\Models\Cpmk;
use App\Models\CplProdi;
use App\Models\CplSndikti;
use App\Models\DistribusiSemester;
use App\Models\EnrollmentMk;
use App\Models\HasilCpl;
use App\Models\HasilCpmk;
use App\Models\KomponenAsesmen;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\PengampuanMk;
use App\Models\PeriodeKurikulum;
use App\Models\Pl;
use App\Models\RpsHeader;
use App\Models\RpsPertemuan;
use App\Models\SemesterAkademik;
use App\Models\SubCpmk;
use App\Models\TimKurikulum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. USERS ──────────────────────────────────────────────────────────
        $kaprodi = User::updateOrCreate(['email' => 'kaprodi@si-obe.id'], [
            'name'          => 'Dr. Ahmad Kaprodi, M.Kom',
            'password'      => Hash::make('password'),
            'role'          => Role::Kaprodi,
            'identifier'    => '197001012000011001',
            'program_studi' => 'Sistem Informasi',
            'status_aktif'  => 'aktif',
        ]);

        $timKur1 = User::updateOrCreate(['email' => 'kurikulum@si-obe.id'], [
            'name'          => 'Siti Kurikulum, M.T',
            'password'      => Hash::make('password'),
            'role'          => Role::TimKurikulum,
            'identifier'    => '198001012005012001',
            'program_studi' => 'Sistem Informasi',
            'status_aktif'  => 'aktif',
        ]);

        $dosen1 = User::updateOrCreate(['email' => 'dosen1@si-obe.id'], [
            'name'          => 'Dr. Budi Santoso, M.Kom',
            'password'      => Hash::make('password'),
            'role'          => Role::Dosen,
            'identifier'    => '198505152010011005',
            'program_studi' => 'Sistem Informasi',
            'status_aktif'  => 'aktif',
        ]);

        $dosen2 = User::updateOrCreate(['email' => 'dosen2@si-obe.id'], [
            'name'          => 'Dewi Rahayu, M.Si',
            'password'      => Hash::make('password'),
            'role'          => Role::Dosen,
            'identifier'    => '199002202015012003',
            'program_studi' => 'Sistem Informasi',
            'status_aktif'  => 'aktif',
        ]);

        $mhs1 = User::updateOrCreate(['email' => 'mahasiswa1@si-obe.id'], [
            'name'          => 'Andi Pratama',
            'password'      => Hash::make('password'),
            'role'          => Role::Mahasiswa,
            'identifier'    => '21523001',
            'program_studi' => 'Sistem Informasi',
            'tahun_masuk'   => 2021,
            'status_aktif'  => 'aktif',
        ]);

        $mhs2 = User::updateOrCreate(['email' => 'mahasiswa2@si-obe.id'], [
            'name'          => 'Bela Sari',
            'password'      => Hash::make('password'),
            'role'          => Role::Mahasiswa,
            'identifier'    => '21523002',
            'program_studi' => 'Sistem Informasi',
            'tahun_masuk'   => 2021,
            'status_aktif'  => 'aktif',
        ]);

        $mhs3 = User::updateOrCreate(['email' => 'mahasiswa3@si-obe.id'], [
            'name'          => 'Candra Wijaya',
            'password'      => Hash::make('password'),
            'role'          => Role::Mahasiswa,
            'identifier'    => '21523003',
            'program_studi' => 'Sistem Informasi',
            'tahun_masuk'   => 2021,
            'status_aktif'  => 'aktif',
        ]);

        // ── 2. SEMESTER AKADEMIK ──────────────────────────────────────────────
        $semGanjil = SemesterAkademik::firstOrCreate(
            ['tahun_akademik' => '2024/2025', 'jenis' => 'Gasal'],
            [
                'is_aktif'        => 1,
                'tanggal_mulai'   => '2024-09-01',
                'tanggal_selesai' => '2025-01-31',
            ]
        );

        $semGenap = SemesterAkademik::firstOrCreate(
            ['tahun_akademik' => '2023/2024', 'jenis' => 'Genap'],
            [
                'is_aktif'        => 0,
                'tanggal_mulai'   => '2024-02-01',
                'tanggal_selesai' => '2024-06-30',
            ]
        );

        // ── 3. KURIKULUM ──────────────────────────────────────────────────────
        // Kode otomatis format: K-{PRODI}-{JENJANG}-{TAHUN} → K-SI-S1-2021
        $kurikulum = Kurikulum::firstOrCreate(['kode' => 'K-SI-S1-2021'], [
            'nama_kurikulum' => 'Kurikulum Sistem Informasi 2021',
            'program_studi'  => 'Sistem Informasi',
            'jenjang'        => 'S1',
            'tahun_mulai'    => 2021,
            'tahun_selesai'  => 2025,
            'visi'           => 'Menjadi program studi unggul dalam bidang sistem informasi berbasis OBE.',
            'misi'           => 'Menyelenggarakan pendidikan berkualitas, penelitian inovatif, dan pengabdian masyarakat.',
            'tujuan'         => 'Menghasilkan lulusan yang kompeten, adaptif, dan berintegritas.',
            'sasaran'        => 'Mahasiswa Sistem Informasi angkatan 2021 ke atas.',
            'status'         => 'aktif',
            'dibuat_oleh'    => $kaprodi->id,
        ]);

        // ── 4. PERIODE KURIKULUM ──────────────────────────────────────────────
        $periode = PeriodeKurikulum::firstOrCreate(
            ['id_kurikulum' => $kurikulum->id, 'nama_periode' => 'Penyusunan 2021'],
            [
                'tanggal_mulai'   => '2021-01-01',
                'tanggal_selesai' => '2021-06-30',
                'ketua_tim'       => $timKur1->id,
                'deskripsi'       => 'Periode penyusunan kurikulum OBE pertama.',
                'status'          => 'selesai',
            ]
        );

        TimKurikulum::firstOrCreate(
            ['id_periode' => $periode->id, 'id_user' => $timKur1->id],
            ['jabatan_tim' => 'Ketua Tim', 'sk_nomor' => 'SK/001/2021']
        );
        TimKurikulum::firstOrCreate(
            ['id_periode' => $periode->id, 'id_user' => $dosen1->id],
            ['jabatan_tim' => 'Anggota', 'sk_nomor' => 'SK/001/2021']
        );

        // ── 5. ARSIP RAPAT ────────────────────────────────────────────────────
        ArsipRapat::firstOrCreate(
            ['id_kurikulum' => $kurikulum->id, 'judul_rapat' => 'Rapat Pleno Kurikulum 2021'],
            [
                'id_periode'   => $periode->id,
                'jenis_rapat'  => 'pleno_kurikulum',
                'tanggal_rapat'=> '2021-03-15',
                'tempat'       => 'Ruang Rapat Prodi SI',
                'agenda'       => 'Finalisasi kurikulum OBE 2021',
                'kesimpulan'   => 'Kurikulum disepakati dan akan diberlakukan mulai 2021.',
                'dibuat_oleh'  => $timKur1->id,
            ]
        );

        // ── 6-11. KURIKULUM OBE: PL, CPL SN-DIKTI, CPL Prodi, BK, MK, dan mapping ──
        (new CurriculumDataSeeder())->seedFor($kurikulum);

        // ── 11.5. Sinkronisasi matriks turunan (pivot_cpl_bk_mk) supaya CPMK seeder ──
        //         bisa mendapat derivasi MK↔CPL via Bahan Kajian.
        app(\App\Services\MatrixConsistencyService::class)->syncAll($kurikulum);

        // ── 11.6. CPMK + Sub-CPMK + Komponen Asesmen + pivot CPL↔CPMK ──
        (new CpmkDataSeeder())->seedFor($kurikulum, $semGanjil);

        // Lookup objek MK & CPL yang dipakai bagian downstream (RPS, CPMK, Nilai)
        $mkPbw = MataKuliah::where('id_kurikulum', $kurikulum->id)->where('kode_mk', 'MK22')->firstOrFail(); // Pemrograman Web
        $mkBd  = MataKuliah::where('id_kurikulum', $kurikulum->id)->where('kode_mk', 'MK21')->firstOrFail(); // Sistem & Manajemen Basis Data
        $cpl1  = CplProdi::where('id_kurikulum', $kurikulum->id)->where('kode_cpl', 'CPL03')->firstOrFail();
        $cpl2  = CplProdi::where('id_kurikulum', $kurikulum->id)->where('kode_cpl', 'CPL02')->firstOrFail();

        // ── 13. CPMK ──────────────────────────────────────────────────────────
        // Format kode: CPMK{cpl_urutan_2digit}{seq_1digit}
        // cpl1 = CPL03 (urutan=3) → CPMK031 (MK22, CPL03, urutan ke-1)
        // cpl2 = CPL02 (urutan=2) → CPMK021 (MK22, CPL02, urutan ke-1)
        // cpl2 pada MK21         → CPMK021 (MK21, CPL02, urutan ke-1)
        $cpmk1 = Cpmk::firstOrCreate(['id_mk' => $mkPbw->id, 'kode_cpmk' => 'CPMK031'], [
            'id_kurikulum' => $kurikulum->id,
            'id_cpl'       => $cpl1->id,
            'deskripsi'    => 'Mahasiswa mampu membangun aplikasi web sederhana.',
            'urutan'       => 1,
        ]);
        $cpmk2 = Cpmk::firstOrCreate(['id_mk' => $mkPbw->id, 'kode_cpmk' => 'CPMK021'], [
            'id_kurikulum' => $kurikulum->id,
            'id_cpl'       => $cpl2->id,
            'deskripsi'    => 'Mahasiswa mampu mengintegrasikan database ke aplikasi web.',
            'urutan'       => 2,
        ]);
        $cpmkBd = Cpmk::firstOrCreate(['id_mk' => $mkBd->id, 'kode_cpmk' => 'CPMK021'], [
            'id_kurikulum' => $kurikulum->id,
            'id_cpl'       => $cpl2->id,
            'deskripsi'    => 'Mahasiswa mampu merancang skema basis data.',
            'urutan'       => 1,
        ]);

        // ── 14. SUB-CPMK ─────────────────────────────────────────────────────
        // Format: Sub-CPMK{cpl_2digit}{cpmk_1digit}{sub_1digit}
        // cpmk1 = CPMK031 → Sub-CPMK0311, Sub-CPMK0312
        // cpmk2 = CPMK021 → Sub-CPMK0211
        $sub1 = SubCpmk::firstOrCreate(['id_cpmk' => $cpmk1->id, 'kode_sub_cpmk' => 'Sub-CPMK0311'], [
            'deskripsi' => 'Membuat halaman HTML dan CSS yang responsif.',
            'bobot'     => 40.00,
            'urutan'    => 1,
        ]);
        $sub2 = SubCpmk::firstOrCreate(['id_cpmk' => $cpmk1->id, 'kode_sub_cpmk' => 'Sub-CPMK0312'], [
            'deskripsi' => 'Mengimplementasikan logika JavaScript dasar.',
            'bobot'     => 60.00,
            'urutan'    => 2,
        ]);
        $sub3 = SubCpmk::firstOrCreate(['id_cpmk' => $cpmk2->id, 'kode_sub_cpmk' => 'Sub-CPMK0211'], [
            'deskripsi' => 'Menghubungkan PHP ke MySQL untuk operasi CRUD.',
            'bobot'     => 100.00,
            'urutan'    => 1,
        ]);

        // ── 15. DISTRIBUSI SEMESTER (dihitung dari data MK) ───────────────────
        $distribusi = MataKuliah::where('id_kurikulum', $kurikulum->id)
            ->selectRaw('semester, SUM(sks_teori + sks_praktikum) AS total_sks, COUNT(*) AS jumlah_mk')
            ->groupBy('semester')
            ->get();
        foreach ($distribusi as $row) {
            DistribusiSemester::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'semester' => $row->semester],
                ['total_sks' => $row->total_sks, 'jumlah_mk' => $row->jumlah_mk]
            );
        }

        // ── 16. BATAS KETERCAPAIAN (semua CPL Prodi) ──────────────────────────
        $semuaCpl = CplProdi::where('id_kurikulum', $kurikulum->id)->get();
        foreach ($semuaCpl as $cpl) {
            BatasKetercapaian::updateOrCreate(
                ['id_cpl' => $cpl->id, 'id_kurikulum' => $kurikulum->id],
                ['batas_nilai' => 70.00]
            );
        }

        // ── 17. PENGAMPUAN MK ─────────────────────────────────────────────────
        PengampuanMk::firstOrCreate(
            ['id_mk' => $mkPbw->id, 'id_dosen' => $dosen1->id, 'id_semester' => $semGanjil->id],
            ['is_koordinator' => 1]
        );
        PengampuanMk::firstOrCreate(
            ['id_mk' => $mkBd->id, 'id_dosen' => $dosen2->id, 'id_semester' => $semGenap->id],
            ['is_koordinator' => 1]
        );

        // ── 18. RPS HEADER ────────────────────────────────────────────────────
        $rps = RpsHeader::firstOrCreate(
            ['id_mk' => $mkPbw->id, 'id_semester' => $semGanjil->id],
            [
                'id_dosen_pengembang' => $dosen1->id,
                'tanggal_penyusunan'  => '2024-08-20',
                'kode_dokumen'        => 'RPS/MK22/GNJ2024',
                'status'              => 'disahkan',
                'disahkan_pada'       => now(),
                'id_kaprodi_pengesah' => $kaprodi->id,
            ]
        );

        // ── 19. RPS PERTEMUAN ─────────────────────────────────────────────────
        $pertemuanData = [
            [1, 'Pengenalan HTML5 dan struktur dokumen web',     'Ceramah & Demonstrasi', $sub1->id],
            [2, 'CSS3 - Styling dan Layout Flexbox',             'Ceramah & Praktikum',   $sub1->id],
            [3, 'CSS3 - Grid Layout dan Responsif Design',       'Praktikum',             $sub1->id],
            [4, 'JavaScript Dasar - Variabel dan Fungsi',        'Ceramah & Latihan',     $sub2->id],
            [5, 'JavaScript - DOM Manipulation',                 'Praktikum',             $sub2->id],
            [6, 'JavaScript - Event Handling & AJAX',            'Praktikum',             $sub2->id],
            [7, 'PHP Dasar - Sintaks dan Tipe Data',             'Ceramah & Praktikum',   $sub3->id],
            [8, 'UTS - Evaluasi Tengah Semester',                'Ujian',                 $sub3->id],
            [9, 'PHP - Form Handling dan Validasi',              'Praktikum',             $sub3->id],
            [10,'PHP - Koneksi ke Database MySQL',               'Praktikum',             $sub3->id],
            [11,'PHP - CRUD Operasi Dasar',                      'Praktikum',             $sub3->id],
            [12,'PHP - Session dan Cookie',                      'Ceramah & Praktikum',   $sub3->id],
            [13,'Framework Laravel - Routing & MVC',             'Ceramah & Praktikum',   $sub3->id],
            [14,'Framework Laravel - Eloquent ORM',              'Praktikum',             $sub3->id],
            [15,'Proyek Mini - Implementasi Aplikasi Web',       'Proyek',                $sub3->id],
            [16,'UAS - Evaluasi Akhir Semester',                 'Ujian',                 $sub3->id],
        ];

        foreach ($pertemuanData as [$minggu, $materi, $metode, $subCpmkId]) {
            RpsPertemuan::firstOrCreate(
                ['id_rps' => $rps->id, 'minggu_ke' => $minggu],
                [
                    'materi_pembelajaran' => $materi,
                    'metode_pembelajaran' => $metode,
                    'id_sub_cpmk'         => $subCpmkId,
                    'estimasi_waktu'      => '150 menit',
                ]
            );
        }

        // ── 20. KOMPONEN ASESMEN ──────────────────────────────────────────────
        $komTugas = KomponenAsesmen::firstOrCreate(
            ['id_mk' => $mkPbw->id, 'id_semester' => $semGanjil->id, 'nama_komponen' => 'Tugas Mingguan'],
            ['id_sub_cpmk' => $sub1->id, 'jenis_komponen' => 'Unjuk Kerja', 'bobot_persen' => 30, 'skor_maks' => 100]
        );
        $komUts = KomponenAsesmen::firstOrCreate(
            ['id_mk' => $mkPbw->id, 'id_semester' => $semGanjil->id, 'nama_komponen' => 'UTS'],
            ['id_sub_cpmk' => $sub2->id, 'jenis_komponen' => 'UTS', 'bobot_persen' => 30, 'skor_maks' => 100]
        );
        $komUas = KomponenAsesmen::firstOrCreate(
            ['id_mk' => $mkPbw->id, 'id_semester' => $semGanjil->id, 'nama_komponen' => 'UAS'],
            ['id_sub_cpmk' => $sub3->id, 'jenis_komponen' => 'UAS', 'bobot_persen' => 40, 'skor_maks' => 100]
        );

        // ── 21. ENROLLMENT ────────────────────────────────────────────────────
        foreach ([$mhs1, $mhs2, $mhs3] as $mhs) {
            EnrollmentMk::firstOrCreate(
                ['id_mahasiswa' => $mhs->id, 'id_mk' => $mkPbw->id, 'id_semester' => $semGanjil->id],
                ['tanggal_daftar' => '2024-09-02', 'status' => 'aktif']
            );
        }

        // ── 22. NILAI MAHASISWA ───────────────────────────────────────────────
        $nilaiData = [
            $mhs1->id => [$komTugas->id => 85, $komUts->id => 78, $komUas->id => 82],
            $mhs2->id => [$komTugas->id => 75, $komUts->id => 70, $komUas->id => 68],
            $mhs3->id => [$komTugas->id => 90, $komUts->id => 88, $komUas->id => 92],
        ];

        foreach ($nilaiData as $mhsId => $kompNilai) {
            foreach ($kompNilai as $kompId => $nilai) {
                NilaiMahasiswa::updateOrCreate(
                    ['id_mahasiswa' => $mhsId, 'id_komponen' => $kompId, 'id_semester' => $semGanjil->id],
                    ['nilai_mentah' => $nilai]
                );
            }
        }

        // ── 23. HASIL CPMK (hitung manual) ───────────────────────────────────
        foreach ([$mhs1, $mhs2, $mhs3] as $mhs) {
            $nilaiMhs = $nilaiData[$mhs->id];
            // CPMK1 dari sub1+sub2 (tugas+uts)
            $nilaiCpmk1 = (($nilaiMhs[$komTugas->id] * 0.4) + ($nilaiMhs[$komUts->id] * 0.6));
            \App\Models\HasilCpmk::updateOrCreate(
                ['id_mahasiswa' => $mhs->id, 'id_cpmk' => $cpmk1->id, 'id_semester' => $semGanjil->id],
                ['nilai' => round($nilaiCpmk1, 2)]
            );
            // CPMK2 dari UAS
            \App\Models\HasilCpmk::updateOrCreate(
                ['id_mahasiswa' => $mhs->id, 'id_cpmk' => $cpmk2->id, 'id_semester' => $semGanjil->id],
                ['nilai' => $nilaiMhs[$komUas->id]]
            );
        }

        // ── 24. HASIL CPL ─────────────────────────────────────────────────────
        foreach ([$mhs1, $mhs2, $mhs3] as $mhs) {
            $nilaiMhs = $nilaiData[$mhs->id];
            $nilaiCpl1 = (($nilaiMhs[$komTugas->id] * 0.4) + ($nilaiMhs[$komUts->id] * 0.6));
            $nilaiCpl2 = $nilaiMhs[$komUas->id];

            HasilCpl::updateOrCreate(
                ['id_mahasiswa' => $mhs->id, 'id_cpl' => $cpl1->id, 'id_semester' => $semGanjil->id],
                ['id_kurikulum' => $kurikulum->id, 'nilai_cpl' => round($nilaiCpl1, 2), 'status_tercapai' => $nilaiCpl1 >= 70 ? 1 : 0]
            );
            HasilCpl::updateOrCreate(
                ['id_mahasiswa' => $mhs->id, 'id_cpl' => $cpl2->id, 'id_semester' => $semGanjil->id],
                ['id_kurikulum' => $kurikulum->id, 'nilai_cpl' => round($nilaiCpl2, 2), 'status_tercapai' => $nilaiCpl2 >= 70 ? 1 : 0]
            );
        }

        // ── 25. ACTIVITY LOG SAMPLE ───────────────────────────────────────────
        ActivityLog::firstOrCreate(
            ['action' => 'login', 'user_id' => $kaprodi->id],
            ['model_type' => 'User', 'model_id' => $kaprodi->id, 'ip_address' => '127.0.0.1']
        );

        $this->command->info('✅ Seeder selesai! Akun tersedia:');
        $this->command->table(
            ['Email', 'Password', 'Role'],
            [
                ['kaprodi@si-obe.id',    'password', 'Kaprodi'],
                ['kurikulum@si-obe.id',  'password', 'Tim Kurikulum'],
                ['dosen1@si-obe.id',     'password', 'Dosen'],
                ['dosen2@si-obe.id',     'password', 'Dosen'],
                ['mahasiswa1@si-obe.id', 'password', 'Mahasiswa'],
                ['mahasiswa2@si-obe.id', 'password', 'Mahasiswa'],
                ['mahasiswa3@si-obe.id', 'password', 'Mahasiswa'],
            ]
        );
    }
}
