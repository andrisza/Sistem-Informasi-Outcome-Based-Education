<?php

namespace Database\Seeders;

use App\Models\BahanKajian;
use App\Models\CplProdi;
use App\Models\CplSndikti;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\Pl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed lengkap data kurikulum S1 Sistem Informasi
 * Sumber: dokumen kurikulum OBE Prodi SI (PL, CPL, BK, MK semester 1–8)
 */
class CurriculumDataSeeder extends Seeder
{
    public function seedFor(Kurikulum $kurikulum): void
    {
        $this->seedCplSndikti();
        $pl   = $this->seedPl($kurikulum);
        $cpl  = $this->seedCplProdi($kurikulum);
        $bk   = $this->seedBahanKajian($kurikulum);
        $mk   = $this->seedMataKuliah($kurikulum);

        $this->seedPivotPlCpl($pl, $cpl);
        $this->seedPivotCplBk($cpl, $bk);
        $this->seedPivotMkBk($mk, $bk);
        $this->seedPivotCplsnCplp($cpl);
    }

    // ── PROFIL LULUSAN ───────────────────────────────────────────────────────
    private function seedPl(Kurikulum $kurikulum): array
    {
        $data = [
            ['PL1', 'Lulusan memiliki kemampuan untuk merencanakan, menganalisis, merancang, membangun, mengujicoba, menerapkan, dan mengevaluasi sistem informasi (bidang x) dalam sebuah proyek yang selaras dengan tujuan organisasi (peta okupasi dalam KKNI Bidang TIK dan IS2020).', 'PL Penciri Utama', 1],
            ['PL2', 'Lulusan memiliki kemampuan memahami, menerapkan, dan mengintegrasikan model bisnis dengan menggunakan metode dan berbagai teknik peningkatan bisnis proses yang mendatangkan suatu nilai tambah bagi organisasi (peta okupasi dalam KKNI Bidang TIK dan IS2020).', 'PL Penciri Utama', 2],
            ['PL3', 'Lulusan memiliki kemampuan untuk mengolah, menganalisis, dan menyajikan data yang dikembangkan dengan konsep big data dan business intelligence untuk membantu dalam proses pengambilan keputusan (peta okupasi dalam KKNI Bidang TIK).', 'PL Tambahan KK dan P', 3],
            ['PL4', 'Lulusan memiliki sikap religius, beretika, dan peka terhadap lingkungan sosial sebagai seorang warga negara dengan berlandaskan nilai ahlussunah waljamaah (Aswaja).', 'PL Sikap', 4],
            ['PL5', 'Lulusan memiliki kemampuan berpikir kritis dan inovatif, bekerja mandiri, membuat keputusan tepat, mendokumentasikan data dengan benar, menyusun karya ilmiah, berkomunikasi efektif, membangun jaringan kerja, bertanggung jawab atas hasil tim, dan mengelola pembelajaran secara mandiri sesuai bidang keahliannya.', 'PL Keterampilan Umum dan Sikap', 5],
        ];

        $out = [];
        foreach ($data as [$kode, $desc, $kategori, $urut]) {
            $out[$kode] = Pl::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_pl' => $kode],
                ['deskripsi' => $desc, 'kategori' => $kategori, 'urutan' => $urut]
            );
        }
        return $out;
    }

    // ── CPL SN-DIKTI ─────────────────────────────────────────────────────────
    private function seedCplSndikti(): void
    {
        $sikap = [
            ['CPL-S01', 'Bertakwa kepada Tuhan Yang Maha Esa dan mampu menunjukkan sikap religius.'],
            ['CPL-S02', 'Menjunjung tinggi nilai kemanusiaan dalam menjalankan tugas berdasarkan agama, moral, dan etika.'],
            ['CPL-S03', 'Berkontribusi dalam peningkatan mutu kehidupan bermasyarakat, berbangsa, dan bernegara.'],
            ['CPL-S04', 'Berperan sebagai warga negara yang bangga dan cinta tanah air, memiliki nasionalisme.'],
            ['CPL-S05', 'Menghargai keanekaragaman budaya, pandangan, agama, dan kepercayaan, serta pendapat atau temuan orisinal orang lain.'],
            ['CPL-S06', 'Bekerja sama dan memiliki kepekaan sosial serta kepedulian terhadap masyarakat dan lingkungan.'],
            ['CPL-S07', 'Taat hukum dan disiplin dalam kehidupan bermasyarakat dan bernegara.'],
            ['CPL-S08', 'Menginternalisasi nilai, norma, dan etika akademik.'],
            ['CPL-S09', 'Menunjukkan sikap bertanggungjawab atas pekerjaan di bidang keahliannya secara mandiri.'],
            ['CPL-S10', 'Menginternalisasi semangat kemandirian, kejuangan, dan kewirausahaan.'],
            ['CPL-S11', 'Menginternalisasi nilai-nilai ahlussunah waljamaah (Aswaja).'],
        ];
        $ku = [
            ['CPL-KU01', 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam konteks pengembangan atau implementasi ipteks.'],
            ['CPL-KU02', 'Mampu menunjukkan kinerja mandiri, bermutu, dan terukur.'],
            ['CPL-KU03', 'Mampu mengkaji implikasi pengembangan atau implementasi ilmu pengetahuan teknologi sesuai keahliannya.'],
            ['CPL-KU04', 'Menyusun deskripsi saintifik hasil kajian tersebut di atas dalam bentuk skripsi atau laporan tugas akhir.'],
            ['CPL-KU05', 'Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah di bidang keahliannya.'],
            ['CPL-KU06', 'Mampu memelihara dan mengembangkan jaringan kerja dengan pembimbing, kolega, sejawat.'],
            ['CPL-KU07', 'Mampu bertanggungjawab atas pencapaian hasil kerja kelompok dan melakukan supervisi serta evaluasi.'],
            ['CPL-KU08', 'Mampu melakukan proses evaluasi diri terhadap kelompok kerja yang berada dibawah tanggung jawabnya.'],
            ['CPL-KU09', 'Mampu mendokumentasikan, menyimpan, mengamankan, dan menemukan kembali data untuk menjamin kesahihan dan mencegah plagiasi.'],
            ['CPL-KU10', 'Berkomunikasi secara efektif dalam berbagai konteks profesional.'],
        ];
        $kk = [
            ['CPL-KK01', 'Mampu membangun, mengelola, menggunakan dan mengamankan database dengan alat dan teknik pengolahan data yang sesuai.'],
            ['CPL-KK02', 'Mampu membuat perencanaan infrastruktur TI, arsitektur jaringan, layanan fisik dan cloud.'],
            ['CPL-KK03', 'Mampu menerapkan metodologi pengembangan sistem informasi beserta alat pemodelan sistem dan menganalisa kebutuhan pengguna.'],
            ['CPL-KK04', 'Mampu menerapkan dasar logika, prinsip matematika, ekspresi, aspek modular, linearitas dan non-linearitas struktur data pada pemrograman perangkat lunak.'],
            ['CPL-KK05', 'Mampu memahami, menerapkan kode etik dalam penggunaan informasi dan data pada sistem informasi.'],
        ];
        $p = [
            ['CPL-P01', 'Menguasai konsep dan teori dasar sistem informasi serta penerapannya pada organisasi.'],
            ['CPL-P02', 'Menguasai konsep pengembangan perangkat lunak dan database.'],
            ['CPL-P03', 'Menguasai konsep manajemen proses bisnis, big data, dan business intelligence.'],
        ];

        $insert = function (array $rows, string $kategori) {
            foreach ($rows as $i => [$kode, $desc]) {
                CplSndikti::updateOrCreate(
                    ['kode' => $kode],
                    ['deskripsi' => $desc, 'kategori' => $kategori, 'urutan' => $i + 1]
                );
            }
        };
        $insert($sikap, 'Sikap');
        $insert($ku,    'Keterampilan Umum');
        $insert($kk,    'Keterampilan Khusus');
        $insert($p,     'Pengetahuan');
    }

    // ── CPL PRODI ────────────────────────────────────────────────────────────
    private function seedCplProdi(Kurikulum $kurikulum): array
    {
        $data = [
            ['CPL01', 'Mampu memahami, menganalisis, dan menilai konsep dasar dan peran sistem informasi dalam mengelola data dan memberikan rekomendasi pengambilan keputusan pada proses dan sistem organisasi.', 'Pengetahuan'],
            ['CPL02', 'Mampu merancang dan menggunakan database, serta mengolah dan menganalisa data dengan alat dan teknik pengolahan data.', 'Keterampilan Khusus'],
            ['CPL03', 'Mampu memahami dan menggunakan berbagai metodologi pengembangan sistem beserta alat pemodelan sistem dan menganalisa kebutuhan pengguna dalam membangun sistem informasi untuk mencapai tujuan organisasi.', 'Keterampilan Khusus'],
            ['CPL04', 'Mampu membuat perencanaan infrastruktur TI, arsitektur jaringan, layanan fisik dan cloud, menganalisa keamanan, identifikasi ancaman, dan mengatasi risiko keamanan sistem.', 'Keterampilan Khusus'],
            ['CPL05', 'Mampu menerapkan kode etik dan memahami implikasi sosial penggunaan teknologi informasi.', 'Sikap'],
            ['CPL06', 'Mampu menerapkan konsep dan mengevaluasi manajemen proses bisnis, integrasi sistem informasi dengan strategi organisasi.', 'Keterampilan Khusus'],
            ['CPL07', 'Mampu mengelola proyek sistem informasi dengan menerapkan prinsip manajemen proyek dan kepemimpinan.', 'Keterampilan Khusus'],
            ['CPL08', 'Mampu mengolah, menganalisis, dan menyajikan data dengan konsep big data dan business intelligence.', 'Keterampilan Khusus'],
            ['CPL09', 'Mampu menerapkan jiwa kewirausahaan, inovasi, dan rintisan bisnis digital.', 'Keterampilan Khusus'],
            ['CPL10', 'Memiliki sikap religius, beretika, bertanggung jawab, dan menjunjung tinggi nilai kemanusiaan, sosial, dan budaya.', 'Sikap'],
            ['CPL11', 'Memiliki semangat kemandirian, kejuangan, kewirausahaan, dan internalisasi nilai ahlussunah waljamaah.', 'Sikap'],
            ['CPL12', 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam pengembangan ipteks.', 'Keterampilan Umum'],
            ['CPL13', 'Mampu menyusun deskripsi saintifik dan mendokumentasikan hasil kajian dalam bentuk laporan ilmiah serta berkomunikasi efektif.', 'Keterampilan Umum'],
            ['CPL14', 'Mampu mengambil keputusan tepat, bertanggung jawab atas kerja tim, dan melakukan evaluasi diri secara berkelanjutan.', 'Keterampilan Umum'],
        ];

        $out = [];
        foreach ($data as $i => [$kode, $desc, $kategori]) {
            $out[$kode] = CplProdi::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_cpl' => $kode],
                ['deskripsi' => $desc, 'kategori' => $kategori, 'urutan' => $i + 1]
            );
        }
        return $out;
    }

    // ── BAHAN KAJIAN ─────────────────────────────────────────────────────────
    private function seedBahanKajian(Kurikulum $kurikulum): array
    {
        $data = [
            ['BK01', 'Foundation of Information Systems',          'Dasar-dasar sistem informasi.'],
            ['BK02', 'Data / Information Management',              'Pengelolaan data dan informasi.'],
            ['BK03', 'Infrastructure',                             'Infrastruktur TI dan jaringan.'],
            ['BK04', 'Project Management',                         'Manajemen proyek SI.'],
            ['BK05', 'Systems Analysis & Design',                  'Analisis dan perancangan sistem.'],
            ['BK06', 'IS Management and Strategy',                 'Manajemen dan strategi sistem informasi.'],
            ['BK07', 'Application Development / Programming',      'Pengembangan aplikasi dan pemrograman.'],
            ['BK08', 'Secure Computing',                           'Keamanan komputasi dan sistem.'],
            ['BK09', 'Ethics, use and implications for society',   'Etika dan implikasi sosial TI.'],
            ['BK10', 'Praktikum',                                  'Praktik laboratorium.'],
            ['BK11', 'Mathematics and Statistics',                 'Matematika dan statistika.'],
            ['BK12', 'Data / Business Analytics',                  'Analitik data dan bisnis.'],
            ['BK13', 'Personality Development',                    'Pengembangan kepribadian.'],
            ['BK14', 'Business Process Management',                'Manajemen proses bisnis.'],
            ['BK15', 'Enterprise Architecture',                    'Arsitektur enterprise.'],
            ['BK16', 'User Interface Design',                      'Perancangan antarmuka pengguna.'],
            ['BK17', 'Digital Innovation',                         'Inovasi digital.'],
            ['BK18', 'Visualisasi Informasi',                      'Visualisasi data dan informasi.'],
            ['BK19', 'Pemrograman Berorientasi Objek',             'Konsep dan praktik OOP.'],
            ['BK20', 'Pemrograman Web',                            'Pengembangan aplikasi web.'],
            ['BK21', 'Pemrograman Mobile',                         'Pengembangan aplikasi mobile.'],
        ];

        $out = [];
        foreach ($data as $i => [$kode, $nama, $desc]) {
            $out[$kode] = BahanKajian::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_bk' => $kode],
                ['nama_bk' => $nama, 'deskripsi' => $desc, 'urutan' => $i + 1]
            );
        }
        return $out;
    }

    // ── MATA KULIAH (Semester 1–8) ───────────────────────────────────────────
    private function seedMataKuliah(Kurikulum $kurikulum): array
    {
        // [kode, nama, sks_teori, sks_praktikum, semester, kategori]
        $data = [
            // Semester 1 (18 SKS, 7 MK)
            ['MK01', 'Agama',                              3, 0, 1, 'MKWK'],
            ['MK02', 'Pancasila',                          2, 0, 1, 'MKWK'],
            ['MK03', 'Bahasa Indonesia',                   2, 0, 1, 'MKWK'],
            ['MK04', 'Pengantar Sistem Informasi',         3, 0, 1, 'Wajib'],
            ['MK05', 'Algoritma dan Pemrograman',          2, 1, 1, 'Wajib'],
            ['MK06', 'Literasi TIK',                       1, 1, 1, 'Wajib'],
            ['MK07', 'Matematika Diskrit',                 3, 0, 1, 'Wajib'],

            // Semester 2 (18 SKS, 8 MK)
            ['MK08', 'Aswaja',                             2, 0, 2, 'MKDU'],
            ['MK09', 'Kewarganegaraan',                    2, 0, 2, 'MKWK'],
            ['MK10', 'Logika dan Metode Berfikir Kritis',  2, 0, 2, 'MKDU'],
            ['MK11', 'Manajemen Data',                     2, 0, 2, 'Wajib'],
            ['MK12', 'Struktur Data',                      2, 0, 2, 'Wajib'],
            ['MK13', 'Sistem Operasi',                     2, 0, 2, 'Wajib'],
            ['MK14', 'Pengantar Manajemen',                3, 0, 2, 'Wajib'],
            ['MK15', 'Manajemen Proses Bisnis',            3, 0, 2, 'Wajib'],

            // Semester 3 (19 SKS, 6 MK)
            ['MK16', 'Bahasa Inggris',                     3, 0, 3, 'MKDU'],
            ['MK17', 'Pengantar Akuntansi',                3, 0, 3, 'Wajib'],
            ['MK18', 'Pemrograman Berorientasi Objek',     2, 1, 3, 'Wajib'],
            ['MK19', 'Analisis dan Perancangan Sistem Informasi', 3, 0, 3, 'Wajib'],
            ['MK20', 'Desain UI/UX',                       2, 1, 3, 'Wajib'],
            ['MK21', 'Sistem dan Manajemen Basis Data',    3, 1, 3, 'Wajib'],

            // Semester 4 (20 SKS, 7 MK)
            ['MK22', 'Pemrograman Web',                    2, 1, 4, 'Wajib'],
            ['MK23', 'Manajemen Proyek Sistem Informasi',  3, 0, 4, 'Wajib'],
            ['MK24', 'Data Science',                       2, 0, 4, 'Wajib'],
            ['MK25', 'Sistem Enterprise',                  3, 0, 4, 'Wajib'],
            ['MK26', 'Rintisan Bisnis Digital',            3, 0, 4, 'Wajib'],
            ['MK27', 'Desain dan Manajemen Jaringan Komputer', 2, 1, 4, 'Wajib'],
            ['MK28', 'Riset Operasi',                      3, 0, 4, 'Wajib'],

            // Semester 5 (22 SKS, 7 MK)
            ['MK29', 'Pengujian Perangkat Lunak',          2, 1, 5, 'Wajib'],
            ['MK30', 'Tata Kelola Teknologi Informasi',    3, 0, 5, 'Wajib'],
            ['MK31', 'Keamanan Sistem Informasi',          3, 0, 5, 'Wajib'],
            ['MK32', 'Business Intelligence',              2, 1, 5, 'Wajib'],
            ['MK33', 'E-Business',                         3, 0, 5, 'Wajib'],
            ['MK34', 'Metodologi Penelitian',              3, 0, 5, 'Wajib'],
            ['MK35', 'Audit Sistem Informasi',             3, 0, 5, 'Wajib'],

            // Semester 6 (16 SKS, 6 MK – 4 wajib + 2 pilihan)
            ['MK36', 'Statistika',                         3, 0, 6, 'MKDU'],
            ['MK37', 'Manajemen Layanan Teknologi Informasi', 3, 0, 6, 'Wajib'],
            ['MK38', 'Sistem Terdistribusi',               2, 1, 6, 'Wajib'],
            ['MK39', 'Kerja Praktek',                      0, 3, 6, 'Wajib'],
            ['MK40', 'Pilihan: Pemrograman Mobile',        2, 1, 6, 'Pilihan'],
            ['MK41', 'Pilihan: Cloud Computing',           2, 1, 6, 'Pilihan'],

            // Semester 7 (15 SKS, 5 MK – 4 wajib + 1 pilihan)
            ['MK42', 'Kuliah Kerja Nyata (KKN)',           0, 3, 7, 'MKDU'],
            ['MK43', 'Seminar Proposal Skripsi',           0, 3, 7, 'Wajib'],
            ['MK44', 'Etika Profesi',                      3, 0, 7, 'Wajib'],
            ['MK45', 'Pilihan: Internet of Things',        2, 1, 7, 'Pilihan'],
            ['MK46', 'Pilihan: Pengembangan Game',         2, 1, 7, 'Pilihan'],

            // Semester 8 (17 SKS, 5 MK – 3 wajib + 2 pilihan)
            ['MK47', 'Skripsi',                            0, 6, 8, 'Wajib'],
            ['MK48', 'Pengabdian Kepada Masyarakat',       0, 3, 8, 'Wajib'],
            ['MK49', 'Kapita Selekta Sistem Informasi',    2, 0, 8, 'Wajib'],
            ['MK50', 'Pilihan: Data Mining Lanjut',        2, 1, 8, 'Pilihan'],
            ['MK51', 'Pilihan: Manajemen Inovasi Digital', 2, 1, 8, 'Pilihan'],
        ];

        $out = [];
        foreach ($data as [$kode, $nama, $teori, $prak, $sem, $kat]) {
            $out[$kode] = MataKuliah::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_mk' => $kode],
                [
                    'nama_mk'       => $nama,
                    'sks_teori'     => $teori,
                    'sks_praktikum' => $prak,
                    'semester'      => $sem,
                    'kategori_mk'   => $kat,
                ]
            );
        }
        return $out;
    }

    // ── PIVOT PL ↔ CPL ───────────────────────────────────────────────────────
    private function seedPivotPlCpl(array $pl, array $cpl): void
    {
        // Matriks dari dokumen kurikulum (PL1..PL5 → CPL01..CPL14)
        $matrix = [
            'CPL01' => ['PL1', 'PL2', 'PL3', 'PL5'],
            'CPL02' => ['PL2'],
            'CPL03' => ['PL1'],
            'CPL04' => ['PL1'],
            'CPL05' => ['PL1', 'PL2', 'PL3', 'PL4', 'PL5'],
            'CPL06' => ['PL2'],
            'CPL07' => ['PL1'],
            'CPL08' => ['PL3'],
            'CPL09' => ['PL2'],
            'CPL10' => ['PL4'],
            'CPL11' => ['PL4'],
            'CPL12' => ['PL5'],
            'CPL13' => ['PL5'],
            'CPL14' => ['PL5'],
        ];

        $rows = [];
        foreach ($matrix as $cplKode => $plKodes) {
            if (!isset($cpl[$cplKode])) continue;
            foreach ($plKodes as $plKode) {
                if (!isset($pl[$plKode])) continue;
                $rows[] = ['id_pl' => $pl[$plKode]->id, 'id_cpl' => $cpl[$cplKode]->id];
            }
        }
        DB::table('pivot_pl_cpl')->upsert($rows, ['id_pl', 'id_cpl']);
    }

    // ── PIVOT CPL ↔ BK ───────────────────────────────────────────────────────
    private function seedPivotCplBk(array $cpl, array $bk): void
    {
        // Matriks BK ↔ CPL (BK01..BK21 → CPL01..CPL14)
        $matrix = [
            'BK01' => ['CPL01', 'CPL02', 'CPL05', 'CPL07', 'CPL09'],
            'BK02' => ['CPL02', 'CPL08'],
            'BK03' => ['CPL04'],
            'BK04' => ['CPL07'],
            'BK05' => ['CPL03'],
            'BK06' => ['CPL06'],
            'BK07' => ['CPL03'],
            'BK08' => ['CPL04'],
            'BK09' => ['CPL05', 'CPL10', 'CPL11'],
            'BK10' => ['CPL02', 'CPL07', 'CPL12', 'CPL13', 'CPL14'],
            'BK11' => ['CPL02', 'CPL08'],
            'BK12' => ['CPL08'],
            'BK13' => ['CPL05', 'CPL10', 'CPL11', 'CPL12', 'CPL13', 'CPL14'],
            'BK14' => ['CPL09'],
            'BK15' => ['CPL04', 'CPL06'],
            'BK16' => ['CPL03'],
            'BK17' => ['CPL09'],
            'BK18' => ['CPL08'],
            'BK19' => ['CPL03'],
            'BK20' => ['CPL03'],
            'BK21' => ['CPL03'],
        ];

        $rows = [];
        foreach ($matrix as $bkKode => $cplKodes) {
            if (!isset($bk[$bkKode])) continue;
            foreach ($cplKodes as $cplKode) {
                if (!isset($cpl[$cplKode])) continue;
                $rows[] = ['id_cpl' => $cpl[$cplKode]->id, 'id_bk' => $bk[$bkKode]->id];
            }
        }
        DB::table('pivot_cpl_bk')->upsert($rows, ['id_cpl', 'id_bk']);
    }

    // ── PIVOT MK ↔ BK ────────────────────────────────────────────────────────
    private function seedPivotMkBk(array $mk, array $bk): void
    {
        // Matriks MK ↔ BK dari dokumen (MK01..MK51)
        $matrix = [
            'MK01' => ['BK09', 'BK13'],
            'MK02' => ['BK09', 'BK13'],
            'MK03' => ['BK13'],
            'MK04' => ['BK01'],
            'MK05' => ['BK07'],
            'MK06' => ['BK01', 'BK03'],
            'MK07' => ['BK11'],
            'MK08' => ['BK13'],
            'MK09' => ['BK13'],
            'MK10' => ['BK11'],
            'MK11' => ['BK02', 'BK11'],
            'MK12' => ['BK07'],
            'MK13' => ['BK03'],
            'MK14' => ['BK06'],
            'MK15' => ['BK14'],
            'MK16' => ['BK13'],
            'MK17' => ['BK05'],
            'MK18' => ['BK07', 'BK19'],
            'MK19' => ['BK05'],
            'MK20' => ['BK05', 'BK16'],
            'MK21' => ['BK02'],
            'MK22' => ['BK07', 'BK20'],
            'MK23' => ['BK04'],
            'MK24' => ['BK02', 'BK11', 'BK12'],
            'MK25' => ['BK14', 'BK15'],
            'MK26' => ['BK17'],
            'MK27' => ['BK03'],
            'MK28' => ['BK11'],
            'MK29' => ['BK07', 'BK10'],
            'MK30' => ['BK06'],
            'MK31' => ['BK08'],
            'MK32' => ['BK12', 'BK18'],
            'MK33' => ['BK06', 'BK17'],
            'MK34' => ['BK10'],
            'MK35' => ['BK06', 'BK08'],
            'MK36' => ['BK11'],
            'MK37' => ['BK06'],
            'MK38' => ['BK03', 'BK15'],
            'MK39' => ['BK10'],
            'MK40' => ['BK21'],
            'MK41' => ['BK03'],
            'MK42' => ['BK13'],
            'MK43' => ['BK10'],
            'MK44' => ['BK09'],
            'MK45' => ['BK17'],
            'MK46' => ['BK17'],
            'MK47' => ['BK10'],
            'MK48' => ['BK09', 'BK13'],
            'MK49' => ['BK17'],
            'MK50' => ['BK12'],
            'MK51' => ['BK17'],
        ];

        $rows = [];
        foreach ($matrix as $mkKode => $bkKodes) {
            if (!isset($mk[$mkKode])) continue;
            foreach ($bkKodes as $bkKode) {
                if (!isset($bk[$bkKode])) continue;
                $rows[] = ['id_mk' => $mk[$mkKode]->id, 'id_bk' => $bk[$bkKode]->id];
            }
        }
        DB::table('pivot_mk_bk')->upsert($rows, ['id_mk', 'id_bk']);
    }

    // ── PIVOT CPL SN-DIKTI ↔ CPL PRODI ───────────────────────────────────────
    private function seedPivotCplsnCplp(array $cplProdi): void
    {
        // Pemetaan rumpun CPL SN-DIKTI → CPL Prodi
        // CPL01-04, 06-09: Keterampilan Khusus & Pengetahuan
        // CPL05, 10-11: Sikap (S01-S11)
        // CPL12-14: Keterampilan Umum (KU01-KU10)
        $mapping = [
            'CPL01' => ['CPL-P01', 'CPL-KK03'],
            'CPL02' => ['CPL-KK01', 'CPL-P02'],
            'CPL03' => ['CPL-KK03'],
            'CPL04' => ['CPL-KK02'],
            'CPL05' => ['CPL-KK05', 'CPL-S08'],
            'CPL06' => ['CPL-P03'],
            'CPL07' => ['CPL-KK01'],
            'CPL08' => ['CPL-P03'],
            'CPL09' => ['CPL-S10'],
            'CPL10' => ['CPL-S01', 'CPL-S02', 'CPL-S03', 'CPL-S04', 'CPL-S06', 'CPL-S07'],
            'CPL11' => ['CPL-S05', 'CPL-S09', 'CPL-S10', 'CPL-S11'],
            'CPL12' => ['CPL-KU01', 'CPL-KU03'],
            'CPL13' => ['CPL-KU04', 'CPL-KU09', 'CPL-KU10'],
            'CPL14' => ['CPL-KU02', 'CPL-KU05', 'CPL-KU07', 'CPL-KU08'],
        ];

        $rows = [];
        foreach ($mapping as $cplProdiKode => $snKodes) {
            if (!isset($cplProdi[$cplProdiKode])) continue;
            foreach ($snKodes as $snKode) {
                $sn = CplSndikti::where('kode', $snKode)->first();
                if (!$sn) continue;
                $rows[] = [
                    'id_cpl_sndikti' => $sn->id,
                    'id_cpl_prodi'   => $cplProdi[$cplProdiKode]->id,
                ];
            }
        }
        DB::table('pivot_cplsn_cplp')->upsert($rows, ['id_cpl_sndikti', 'id_cpl_prodi']);
    }
}
