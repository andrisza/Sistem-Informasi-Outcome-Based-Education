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
 * Seed data kurikulum S1 Sistem Informasi
 * Sumber: Buku Panduan Kurikulum OBE/KKNI/SKKNI APTIKOM v2.0 (2024)
 * Tabel 1 (PL), Tabel 2 (CPL), Tabel 4 (BK), Tabel 9 (MK),
 * Tabel 3 (PL-CPL), Tabel 5 (CPL-BK), Tabel 6 (MK-BK), Tabel 7 (MK-CPL)
 */
class CurriculumDataSeeder extends Seeder
{
    public function seedFor(Kurikulum $kurikulum): void
    {
        $this->seedCplSndikti();
        $pl  = $this->seedPl($kurikulum);
        $cpl = $this->seedCplProdi($kurikulum);
        $bk  = $this->seedBahanKajian($kurikulum);
        $mk  = $this->seedMataKuliah($kurikulum);

        $this->seedPivotPlCpl($pl, $cpl);
        $this->seedPivotCplBk($cpl, $bk);
        $this->seedPivotMkBk($mk, $bk);
        $this->seedPivotCplsnCplp($cpl);
    }

    // ── PROFIL LULUSAN (Buku Tabel 1) ────────────────────────────────────────
    private function seedPl(Kurikulum $kurikulum): array
    {
        // [kode, deskripsi, kategori, referensi, urutan]
        $data = [
            [
                'PL01',
                'Lulusan memiliki kemampuan menganalisis, merancang, mengembangkan, dan menjamin kualitas sistem informasi sesuai dengan kebutuhan pengguna serta standar industri.',
                'Kompetensi Utama',
                'IS2020, Permendikbudristek No. 53/2023, SKKNI level 6 bidang TIK',
                1,
            ],
            [
                'PL02',
                'Lulusan memiliki kemampuan memahami, menerapkan dan mengintegrasikan model sistem, menggunakan metode dan berbagai teknik peningkatan bisnis proses yang mendatangkan suatu nilai untuk organisasi.',
                'Kompetensi Utama',
                'IS2020, Permendikbudristek No. 53/2023, SKKNI level 6 bidang TIK',
                2,
            ],
            [
                'PL03',
                'Mampu untuk bekerja secara kolaboratif, proaktif, dan bertanggungjawab dalam tim untuk mencapai tujuan bersama dalam berbagai konteks profesional.',
                'Kompetensi Sikap',
                'IABEE, ABET',
                3,
            ],
        ];

        $out = [];
        foreach ($data as [$kode, $desc, $kategori, $ref, $urutan]) {
            $out[$kode] = Pl::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_pl' => $kode],
                ['deskripsi' => $desc, 'kategori' => $kategori, 'referensi' => $ref, 'urutan' => $urutan]
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
            ['CPL-KU08', 'Mampu melakukan proses evaluasi diri terhadap kelompok kerja yang berada di bawah tanggung jawabnya.'],
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

    // ── CPL PRODI (Buku Tabel 2) ─────────────────────────────────────────────
    private function seedCplProdi(Kurikulum $kurikulum): array
    {
        // [kode, deskripsi, kategori, referensi, urutan]
        $data = [
            [
                'CPL01',
                'Mampu memahami, menganalisis permasalahan computing yang kompleks, dan menilai konsep dasar serta peran sistem informasi dalam mengelola data dan memberikan rekomendasi pengambilan keputusan pada sistem organisasi.',
                'Pengetahuan',
                'IS2020 A3.1 Foundations Competency Realm; SKKNI Area Fungsi Information System and Technology Development; IABEE, ABET',
                1,
            ],
            [
                'CPL02',
                'Mampu memahami, merancang, menggunakan sistem manajemen basis data, serta mengolah dan menganalisa data dengan peralatan dan metode pengolahan data.',
                'Keterampilan Khusus',
                'IS2020 A3.2.1 Data/Information Management; SKKNI Area Fungsi Data Management System',
                2,
            ],
            [
                'CPL03',
                'Mampu memahami dan menggunakan berbagai metodologi pengembangan sistem beserta alat pemodelan sistem serta menganalisis kebutuhan pengguna dalam membangun sistem informasi yang berkualitas untuk mencapai tujuan organisasi.',
                'Keterampilan Khusus',
                'IS2020 A3.4.1 System Analysis and Design; A3.4.2 Application Development and Programming; SKKNI Area Fungsi Programming and Software Development',
                3,
            ],
            [
                'CPL04',
                'Mampu menganalisis infrastruktur SI, arsitektur jaringan, layanan fisik dan cloud, konsep identifikasi, otentikasi, otorisasi akses dalam konteks melindungi orang dan perangkat.',
                'Keterampilan Khusus',
                'IS2020 A3.3 Technology Competency Realm; SKKNI Area Fungsi IT Security and Compliance; SKKNI Area Fungsi Network and Infrastructure',
                4,
            ],
            [
                'CPL05',
                'Mampu memahami dan menerapkan kode etik organisasi dalam penggunaan informasi maupun data pada perancangan dan implementasi suatu sistem.',
                'Sikap',
                'IS2020 A3.5.1 IS Ethics, Sustainability, User and Implication',
                5,
            ],
            [
                'CPL06',
                'Memiliki kemampuan merencanakan, menerapkan, memelihara serta meningkatkan sistem informasi organisasi untuk mencapai tujuan dan sasaran organisasi yang strategis baik jangka pendek maupun jangka panjang.',
                'Keterampilan Khusus',
                'IS2020 A3.5.2 Competency Area – IS Management and Strategy; SKKNI IT and Computing Facilities Management',
                6,
            ],
            [
                'CPL07',
                'Mampu memahami, mengidentifikasi dan menerapkan konsep, teknik dan metodologi manajemen proyek sistem informasi terintegrasi untuk peningkatan proses bisnis organisasi.',
                'Keterampilan Khusus',
                'IS2020 A3.6.1 IS Project Management; SKKNI Area Fungsi IT Project Management',
                7,
            ],
        ];

        $out = [];
        foreach ($data as [$kode, $desc, $kategori, $ref, $urutan]) {
            $out[$kode] = CplProdi::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_cpl' => $kode],
                ['deskripsi' => $desc, 'kategori' => $kategori, 'referensi' => $ref, 'urutan' => $urutan]
            );
        }
        return $out;
    }

    // ── BAHAN KAJIAN (Buku Tabel 4) ──────────────────────────────────────────
    private function seedBahanKajian(Kurikulum $kurikulum): array
    {
        // [kode, nama, deskripsi, kompetensi, referensi, urutan]
        $data = [
            ['BK01', 'Foundation of Information Systems',
                'Memperkenalkan konsep dasar sistem informasi untuk mendukung proses bisnis transaksional, keputusan, dan kolaboratif dengan menggunakan alat dan metode pengembangan IS yang relevan.',
                'Utama', 'IS2020', 1],
            ['BK02', 'Data/Information Management',
                'Fokus pada cara mengelola data dan informasi sebagai aset bisnis, termasuk teknik penyimpanan, pengambilan, dan pengolahan basis data serta prinsip-prinsip manajemen beserta keamanan basis data.',
                'Utama', 'IS2020', 2],
            ['BK03', 'IT Infrastructure',
                'Fokus pada enterprise architecture yang mencakup pemahaman tentang komponen fisik dan virtual yang membentuk infrastruktur IT, termasuk perangkat keras, perangkat lunak, jaringan, dan cloud computing.',
                'Utama', 'IS2020', 3],
            ['BK04', 'IS Project Management',
                'Pembelajaran tentang metodologi dan teknik manajemen proyek untuk mengelola proyek-proyek sistem informasi, termasuk perencanaan, pengorganisasian, pengendalian, dan penutupan proyek.',
                'Utama', 'IS2020', 4],
            ['BK05', 'Systems Analysis & Design',
                'Mempelajari proses analisis kebutuhan dan desain sistem informasi yang efektif, termasuk teknik pemodelan sistem, pengembangan diagram, dan pembuatan spesifikasi sistem.',
                'Utama', 'IS2020', 5],
            ['BK06', 'IS Management and Strategy',
                'Fokus pada pengembangan strategi untuk pengelolaan sistem informasi yang selaras dengan tujuan bisnis, termasuk pengelolaan sumber daya IT, tata kelola IT, dan penerapan kebijakan teknologi.',
                'Utama', 'IS2020', 6],
            ['BK07', 'Application Development/Programming',
                'Fokus dalam pengembangan aplikasi dan pemrograman, termasuk penggunaan bahasa pemrograman, framework, dan alat pengembangan untuk menciptakan solusi perangkat lunak.',
                'Utama', 'IS2020', 7],
            ['BK08', 'Secure Computing',
                'Menekankan pada pentingnya keamanan informasi dan sistem, termasuk konsep dasar keamanan komputer, enkripsi, pengelolaan ancaman, dan pengendalian akses.',
                'Utama', 'IS2020', 8],
            ['BK09', 'Ethics, use and implications for society',
                'Mengeksplorasi aspek etika penggunaan teknologi informasi, dampak sosial, privasi, dan implikasi hukum dari implementasi sistem informasi dalam masyarakat.',
                'Utama', 'IS2020', 9],
            ['BK10', 'Internship',
                'Program magang yang memberikan pengalaman praktis bagi mahasiswa dalam dunia kerja nyata di bidang sistem informasi, memungkinkan mahasiswa menerapkan pengetahuan yang telah diperoleh.',
                'Utama', 'IABEE', 10],
            ['BK11', 'Mathematics and Statistics',
                'Membangun dasar pengetahuan matematika dan statistik yang diperlukan untuk analisis data, pemodelan, dan pengambilan keputusan yang berbasis data dalam sistem informasi.',
                'Utama', 'IABEE', 11],
            ['BK12', 'Research Methodology',
                'Mencakup langkah-langkah sistematis dalam melakukan penelitian di bidang sistem informasi, mulai dari perumusan masalah, tinjauan literatur, pemilihan metode, hingga analisis data.',
                'Umum', null, 12],
            ['BK13', 'Data/Business Analytics',
                'Memperkenalkan teknik dan alat analisis data untuk pengambilan keputusan bisnis, termasuk penggunaan big data, data mining, dan analisis prediktif.',
                'Pendukung', 'IS2020', 13],
            ['BK14', 'Personality Development',
                'Pengembangan keterampilan interpersonal dan soft skills, seperti komunikasi, kerja sama tim, dan manajemen waktu yang penting bagi profesional di bidang sistem informasi.',
                'Pendukung', 'IS2020/IABEE', 14],
            ['BK15', 'Business Process Management',
                'Fokus pada analisis, desain, implementasi, pemantauan, dan penyempurnaan proses bisnis untuk meningkatkan efisiensi dan efektivitas manajemen bisnis.',
                'Pendukung', 'IS2020/ASIIN', 15],
            ['BK16', 'Enterprise Architecture',
                'Pembelajaran tentang bagaimana merancang dan mengelola arsitektur organisasi secara holistik untuk memastikan bahwa teknologi informasi sejalan dengan tujuan strategis bisnis.',
                'Pendukung', 'CC2020', 16],
            ['BK17', 'User Interface Design',
                'Prinsip dan praktik desain antarmuka pengguna yang efektif, termasuk pemahaman tentang pengalaman pengguna (UX), navigasi, dan desain interaksi yang intuitif.',
                'Pendukung', 'IS2020', 17],
            ['BK18', 'Emerging Technologies',
                'Eksplorasi teknologi-teknologi baru dan inovatif seperti kecerdasan buatan, Internet of Things (IoT), blockchain, dan teknologi disruptif lainnya.',
                'Pendukung', 'IS2020', 18],
            ['BK19', 'Digital Innovation',
                'Pengembangan ide-ide inovatif dan penerapan solusi digital untuk menciptakan nilai baru bagi bisnis dan masyarakat, termasuk pemikiran desain dan kewirausahaan digital.',
                'Pendukung', 'IS2020', 19],
        ];

        $out = [];
        foreach ($data as [$kode, $nama, $desc, $kompetensi, $ref, $urutan]) {
            $out[$kode] = BahanKajian::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_bk' => $kode],
                [
                    'nama_bk'    => $nama,
                    'deskripsi'  => $desc,
                    'kompetensi' => $kompetensi,
                    'referensi'  => $ref,
                    'urutan'     => $urutan,
                ]
            );
        }
        return $out;
    }

    // ── MATA KULIAH (Buku Tabel 9) ───────────────────────────────────────────
    private function seedMataKuliah(Kurikulum $kurikulum): array
    {
        // [kode, nama, sks_teori, sks_prak, semester, kategori_mk, kompetensi_mk]
        $data = [
            // Semester 1
            ['MK01', 'Konsep Sistem Informasi',          3, 0, 1, 'Wajib',  'Utama'],
            ['MK07', 'Pengantar Teknologi Informasi',    3, 0, 1, 'Wajib',  'Utama'],
            ['MK14', 'Pemrograman Dasar',                2, 1, 1, 'Wajib',  'Utama'],

            // Semester 2
            ['MK02', 'Sistem Informasi Manajemen',       3, 0, 2, 'Wajib',  'Utama'],
            ['MK03', 'Sistem Basis Data',                2, 1, 2, 'Wajib',  'Utama'],
            ['MK05', 'Sistem Operasi',                   3, 0, 2, 'Wajib',  'Utama'],
            ['MK23', 'Statistika dan Probabilitas',      3, 0, 2, 'Wajib',  'Utama'],

            // Semester 3
            ['MK04', 'Sistem Basis Data Lanjut',         2, 1, 3, 'Wajib',  'Utama'],
            ['MK06', 'Jaringan Komputer',                2, 1, 3, 'Wajib',  'Utama'],
            ['MK15', 'Transformasi Digital',             3, 0, 3, 'Wajib',  'Utama'],
            ['MK16', 'Pemrograman Berorientasi Objek',   2, 1, 3, 'Wajib',  'Utama'],
            ['MK20', 'Kepemimpinan dan Manajemen Organisasi', 3, 0, 3, 'Wajib', 'Utama'],

            // Semester 4
            ['MK10', 'Analisis dan Perancangan Sistem Informasi', 3, 0, 4, 'Wajib', 'Utama'],
            ['MK17', 'Pemrograman Berbasis Web',         2, 1, 4, 'Wajib',  'Utama'],
            ['MK18', 'Keamanan Jaringan',                3, 0, 4, 'Wajib',  'Utama'],
            ['MK21', 'Etika Profesi dan Profesional',    3, 0, 4, 'Wajib',  'Utama'],

            // Semester 5
            ['MK08', 'Manajemen Proyek Sistem Informasi', 3, 0, 5, 'Wajib', 'Utama'],
            ['MK12', 'Tata Kelola Teknologi Informasi',  3, 0, 5, 'Wajib',  'Utama'],

            // Semester 6
            ['MK11', 'Software Testing dan Quality Assurance', 2, 1, 6, 'Wajib', 'Utama'],
            ['MK13', 'Audit Sistem Informasi',           3, 0, 6, 'Wajib',  'Utama'],
            ['MK19', 'Keamanan Sistem Informasi',        3, 0, 6, 'Wajib',  'Utama'],
            ['MK25', 'Kerja Praktek/Magang',             0, 3, 6, 'Wajib',  'Utama'],

            // Semester 7
            ['MK09', 'Proyek Sistem Informasi',          2, 2, 7, 'Wajib',  'Utama'],
            ['MK22', 'Metodologi Penelitian',            3, 0, 7, 'Wajib',  'Utama'],

            // Semester 8
            ['MK24', 'Tugas Akhir',                      0, 6, 8, 'Wajib',  'Utama'],
        ];

        $out = [];
        foreach ($data as [$kode, $nama, $teori, $prak, $sem, $kat, $kompetensimk]) {
            $out[$kode] = MataKuliah::updateOrCreate(
                ['id_kurikulum' => $kurikulum->id, 'kode_mk' => $kode],
                [
                    'nama_mk'       => $nama,
                    'sks_teori'     => $teori,
                    'sks_praktikum' => $prak,
                    'semester'      => $sem,
                    'kategori_mk'   => $kat,
                    'kompetensi_mk' => $kompetensimk,
                ]
            );
        }
        return $out;
    }

    // ── PIVOT PL ↔ CPL (Buku Tabel 3) ────────────────────────────────────────
    private function seedPivotPlCpl(array $pl, array $cpl): void
    {
        // PL → [CPL yang dipenuhi]
        $matrix = [
            'PL01' => ['CPL01', 'CPL02', 'CPL03', 'CPL04', 'CPL06'],
            'PL02' => ['CPL02', 'CPL03', 'CPL05', 'CPL06', 'CPL07'],
            'PL03' => ['CPL05', 'CPL07'],
        ];

        $rows = [];
        foreach ($matrix as $plKode => $cplKodes) {
            if (!isset($pl[$plKode])) continue;
            foreach ($cplKodes as $cplKode) {
                if (!isset($cpl[$cplKode])) continue;
                $rows[] = ['id_pl' => $pl[$plKode]->id, 'id_cpl' => $cpl[$cplKode]->id];
            }
        }
        DB::table('pivot_pl_cpl')->upsert($rows, ['id_pl', 'id_cpl']);
    }

    // ── PIVOT CPL ↔ BK (Buku Tabel 5) ────────────────────────────────────────
    private function seedPivotCplBk(array $cpl, array $bk): void
    {
        // CPL → [BK yang mendukung]
        $matrix = [
            'CPL01' => ['BK01', 'BK02', 'BK04'],
            'CPL02' => ['BK02', 'BK05', 'BK11'],
            'CPL03' => ['BK05', 'BK06', 'BK07', 'BK10', 'BK12'],
            'CPL04' => ['BK03', 'BK08'],
            'CPL05' => ['BK09'],
            'CPL06' => ['BK04', 'BK05', 'BK06'],
            'CPL07' => ['BK04', 'BK05', 'BK06', 'BK10', 'BK12'],
        ];

        $rows = [];
        foreach ($matrix as $cplKode => $bkKodes) {
            if (!isset($cpl[$cplKode])) continue;
            foreach ($bkKodes as $bkKode) {
                if (!isset($bk[$bkKode])) continue;
                $rows[] = ['id_cpl' => $cpl[$cplKode]->id, 'id_bk' => $bk[$bkKode]->id];
            }
        }
        DB::table('pivot_cpl_bk')->upsert($rows, ['id_cpl', 'id_bk']);
    }

    // ── PIVOT MK ↔ BK (Buku Tabel 6) ─────────────────────────────────────────
    private function seedPivotMkBk(array $mk, array $bk): void
    {
        // MK → [BK yang dikandung]
        $matrix = [
            'MK01' => ['BK01'],
            'MK02' => ['BK01'],
            'MK03' => ['BK02', 'BK05'],
            'MK04' => ['BK02', 'BK05'],
            'MK05' => ['BK03'],
            'MK06' => ['BK03'],
            'MK07' => ['BK03'],
            'MK08' => ['BK04', 'BK05', 'BK06'],
            'MK09' => ['BK04', 'BK05', 'BK06'],
            'MK10' => ['BK05', 'BK06'],
            'MK11' => ['BK05', 'BK07'],
            'MK12' => ['BK06'],
            'MK13' => ['BK06'],
            'MK14' => ['BK07'],
            'MK15' => ['BK06'],
            'MK16' => ['BK07'],
            'MK17' => ['BK07'],
            'MK18' => ['BK08'],
            'MK19' => ['BK08'],
            'MK20' => ['BK09'],
            'MK21' => ['BK09'],
            'MK22' => ['BK12'],
            'MK23' => ['BK11'],
            'MK24' => ['BK10', 'BK12'],
            'MK25' => ['BK10', 'BK12'],
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

    // ── PIVOT MK ↔ CPL (Buku Tabel 7) ─────────────────────────────────────────
    private function seedPivotMkCpl(array $mk, array $cpl): void
    {
        // MK → [CPL yang dipenuhi]
        $matrix = [
            'MK01' => ['CPL01'],
            'MK02' => ['CPL01'],
            'MK03' => ['CPL02'],
            'MK04' => ['CPL02'],
            'MK05' => ['CPL04'],
            'MK06' => ['CPL04'],
            'MK07' => ['CPL04'],
            'MK08' => ['CPL07'],
            'MK09' => ['CPL01', 'CPL06', 'CPL07'],
            'MK10' => ['CPL03', 'CPL06'],
            'MK11' => ['CPL03'],
            'MK12' => ['CPL06'],
            'MK13' => ['CPL06'],
            'MK14' => ['CPL03'],
            'MK15' => ['CPL06', 'CPL07'],
            'MK16' => ['CPL03'],
            'MK17' => ['CPL03'],
            'MK18' => ['CPL04'],
            'MK19' => ['CPL04'],
            'MK20' => ['CPL05'],
            'MK21' => ['CPL05'],
            'MK22' => ['CPL07'],
            'MK23' => ['CPL02'],
            'MK24' => ['CPL03', 'CPL07'],
            'MK25' => ['CPL03', 'CPL07'],
        ];

        $rows = [];
        foreach ($matrix as $mkKode => $cplKodes) {
            if (!isset($mk[$mkKode])) continue;
            foreach ($cplKodes as $cplKode) {
                if (!isset($cpl[$cplKode])) continue;
                $rows[] = [
                    'id_mk'   => $mk[$mkKode]->id,
                    'id_cpl'  => $cpl[$cplKode]->id,
                    'id_cpmk' => null,
                    'bobot'   => 1.00,
                ];
            }
        }
        DB::table('pivot_mk_cpl')->upsert($rows, ['id_mk', 'id_cpl']);
    }

    // ── PIVOT CPL SN-DIKTI ↔ CPL PRODI ───────────────────────────────────────
    private function seedPivotCplsnCplp(array $cplProdi): void
    {
        $mapping = [
            'CPL01' => ['CPL-P01'],
            'CPL02' => ['CPL-KK01', 'CPL-P02'],
            'CPL03' => ['CPL-KK03', 'CPL-P02'],
            'CPL04' => ['CPL-KK02'],
            'CPL05' => ['CPL-KK05', 'CPL-S08'],
            'CPL06' => ['CPL-P03'],
            'CPL07' => ['CPL-KK01'],
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
