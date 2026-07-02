-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251008.967007883e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 02, 2026 at 06:19 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_obe`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 4, 'login', 'App\\Models\\User', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 10:56:32'),
(2, 4, 'login', 'App\\Models\\User', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 10:56:57'),
(3, 4, 'create', 'App\\Models\\SemesterAkademik', 2, NULL, '{\"nama\": \"Genap 2026\", \"jenis\": \"Genap\", \"tahun_akademik\": \"2026\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:06:43'),
(4, 4, 'aktifkan', 'App\\Models\\SemesterAkademik', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:06:56'),
(5, 4, 'logout', 'App\\Models\\User', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:07:44'),
(6, 5, 'login', 'App\\Models\\User', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:07:57'),
(7, 5, 'create', 'App\\Models\\Kurikulum', 1, NULL, '{\"kode\": \"K-SI-S1-2026\", \"status\": \"draft\", \"nama_kurikulum\": \"Kurikulum Sistem Informasi 2026\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:09:07'),
(8, 5, 'logout', 'App\\Models\\User', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:10:48'),
(9, 4, 'login', 'App\\Models\\User', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:10:55'),
(10, 4, 'logout', 'App\\Models\\User', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:11:23'),
(11, 5, 'login', 'App\\Models\\User', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 11:11:32'),
(12, 5, 'create', 'App\\Models\\MataKuliah', 2, NULL, '{\"kode_mk\": \"MK01\", \"nama_mk\": \"Agama\", \"semester\": \"1\", \"sks_teori\": \"3\", \"sks_praktikum\": \"0\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:02:47'),
(13, 5, 'delete', 'App\\Models\\MataKuliah', 2, '{\"kode_mk\": \"MK01\", \"nama_mk\": \"Agama\", \"semester\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:10:51'),
(14, 5, 'logout', 'App\\Models\\User', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:42:26'),
(15, 4, 'login', 'App\\Models\\User', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:42:35'),
(16, 4, 'logout', 'App\\Models\\User', 4, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:43:46'),
(17, 6, 'login', 'App\\Models\\User', 6, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:43:53'),
(18, NULL, 'aktifkan', 'App\\Models\\Kurikulum', 1, '{\"status\": \"draft\"}', '{\"status\": \"aktif\"}', '127.0.0.1', 'Symfony', '2026-06-20 13:53:05'),
(19, 6, 'logout', 'App\\Models\\User', 6, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:53:41'),
(20, 5, 'login', 'App\\Models\\User', 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 13:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `arsip_rapat`
--

DROP TABLE IF EXISTS `arsip_rapat`;
CREATE TABLE `arsip_rapat` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `tempat` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temuan` longtext COLLATE utf8mb4_unicode_ci,
  `tindak_lanjut` longtext COLLATE utf8mb4_unicode_ci,
  `file_lampiran` json DEFAULT NULL,
  `dibuat_oleh` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bahan_kajian`
--

DROP TABLE IF EXISTS `bahan_kajian`;
CREATE TABLE `bahan_kajian` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `kode_bk` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bk` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `kompetensi` enum('Utama','Pendukung','Umum') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bidang_keilmuan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bahan_kajian`
--

INSERT INTO `bahan_kajian` (`id`, `id_kurikulum`, `kode_bk`, `nama_bk`, `deskripsi`, `kompetensi`, `referensi`, `bidang_keilmuan`, `urutan`, `status`, `approved_by`, `approved_at`, `deleted_at`) VALUES
(1, 1, 'BK01', 'Foundation of Information Systems', 'Memperkenalkan konsep dasar sistem informasi (hardware, software, dan information acquisition) untuk mendukung proses bisnis transaksional, keputusan, dan kolaboratif dengan menggunakan alat dan metode pengembangan IS yang relevan dalam membuat rekomendasi analisis bisnis organisasi, dan menilai proses dan sistem', 'Utama', 'IS2020', NULL, 1, 'draft', NULL, NULL, NULL),
(2, 1, 'BK02', 'Data / information Management', 'Fokus pada cara mengelola data dan informasi sebagai aset bisnis, termasuk teknik penyimpanan, pengambilan, dan pengolahan basis data serta prinsip-prinsip manajemen beserta keamanan basis data, termasuk ketrampilan dalam melindungi informasi yang sensitif.', 'Utama', 'IS2020', NULL, 2, 'draft', NULL, NULL, NULL),
(3, 1, 'BK03', 'IT Infrastructure', 'Fokus pada enterprise architecture yang mencakup pemahaman tentang komponen fisik dan virtual yang membentuk infrastruktur IT, termasuk perangkat keras, perangkat lunak, jaringan, dan cloud computing yang mendukung operasional sistem informasi.', 'Utama', 'IS2020', NULL, 3, 'draft', NULL, NULL, NULL),
(4, 1, 'BK04', 'IS Project Management', 'Pembelajaran tentang metodologi dan teknik manajemen proyek untuk mengelola proyek-proyek sistem informasi, termasuk perencanaan, pengorganisasian, pengendalian, dan penutupan proyek dengan pendekatan yang profesional, proaktif, kolaboratif, serta terarah pada tujuan', 'Utama', 'IS2020', NULL, 4, 'draft', NULL, NULL, NULL),
(5, 1, 'BK05', 'System Analysis & Design', 'Mempelajari proses analisis kebutuhan dan desain sistem informasi yang efektif, termasuk teknik pemodelan sistem, pengembangan diagram, dan pembuatan spesifikasi sistem yang berorientasi pada tujuan dalam semua aspek analisis dan desain sistem.', 'Utama', 'IS2020', NULL, 5, 'draft', NULL, NULL, NULL),
(6, 1, 'BK06', 'IS Management and Strategy', 'Fokus pada pengembangan strategi untuk pengelolaan sistem informasi yang selaras dengan tujuan bisnis, termasuk pengelolaan sumber daya IT, tata kelola IT, dan penerapan kebijakan teknologi, dan mengevaluasi pengambilan keputusan terkait IT organisasi, serta implementasi rencana strategis untuk sistem informasi. Selain itu, kompetensi ini menekankan kepatuhan sistem informasi terhadap kebijakan, hukum, dan regulasi yang berlaku, manajemen risiko organisasi dan pengembangan rencana mitigasi risiko, serta kemampuan untuk menciptakan kebijakan pengadaan IT dan negosiasi kontrak IT. Termasuk mencakup pengembangan rencana pengelolaan tenaga kerja, penerapan framework manajemen layanan terkemuka seperti ITIL dan CMMI, serta penggunaan framework tata kelola seperti COBIT dan TOGAF untuk menyelaraskan sistem informasi dengan strategi organisasi.', 'Utama', 'IS2020', NULL, 6, 'draft', NULL, NULL, NULL),
(7, 1, 'BK07', 'Application Development / Programming', 'Fokus dalam pengembangan aplikasi dan pemrograman, termasuk penggunaan bahasa pemrograman, framework, dan alat pengembangan untuk menciptakan solusi perangkat lunak.', 'Utama', 'IS2020', NULL, 7, 'draft', NULL, NULL, NULL),
(8, 1, 'BK08', 'Secure Computing', 'Menekankan pada pentingnya keamanan informasi dan sistem, termasuk konsep dasar keamanan komputer, enkripsi, pengelolaan ancaman, dan pengendalian akses dengan memprioritaskan faktor risiko terhadap aset informasi.', 'Utama', 'IS2020', NULL, 8, 'draft', NULL, NULL, NULL),
(9, 1, 'BK09', 'Ethics, use and implications for society', 'Mengeksplorasi aspek etika penggunaan teknologi informasi, dampak sosial, privasi, dan implikasi hukum dari implementasi sistem informasi dalam masyarakat.', 'Utama', 'IS2020', NULL, 9, 'draft', NULL, NULL, NULL),
(10, 1, 'BK10', 'Praktikum', NULL, 'Utama', NULL, NULL, 10, 'draft', NULL, NULL, NULL),
(11, 1, 'BK11', 'Mathematic and Statistics', 'Membangun dasar pengetahuan matematika dan statistik yang diperlukan untuk analisis data, pemodelan, dan pengambilan keputusan yang berbasis data dalam sistem informasi.', 'Utama', 'IABEE', NULL, 11, 'draft', NULL, NULL, NULL),
(12, 1, 'BK12', 'Data / Business Analytics', 'Memperkenalkan teknik dan alat analisis data untuk pengambilan keputusan bisnis, termasuk penggunaan big data, data mining, dan analisis prediktif untuk mendapatkan wawasan bisnis yang berharga.', 'Pendukung', 'IS2020', NULL, 12, 'draft', NULL, NULL, NULL),
(13, 1, 'BK13', 'Personality Development', 'Pengembangan keterampilan interpersonal dan soft skills, seperti komunikasi, kerja sama tim, dan manajemen waktu yang penting bagi profesional di bidang sistem informas', 'Pendukung', 'IABEE', NULL, 13, 'draft', NULL, NULL, NULL),
(14, 1, 'BK14', 'Business Process Management', 'Fokus pada analisis, desain, implementasi, pemantauan, dan penyempurnaan proses bisnis untuk meningkatkan efisiensi dan efektivitas manajemen bisnis.', 'Pendukung', 'ASIIN', NULL, 14, 'draft', NULL, NULL, NULL),
(15, 1, 'BK15', 'Enterprise Architecture', 'Pembelajaran tentang bagaimana merancang dan mengelola arsitektur organisasi secara holistik untuk memastikan bahwa teknologi informasi sejalan dengan tujuan strategis bisnis.', 'Pendukung', 'CC2020', NULL, 15, 'draft', NULL, NULL, NULL),
(16, 1, 'BK16', 'User Interface Design', 'Prinsip dan praktik desain antarmuka pengguna yang efektif, termasuk pemahaman tentang pengalaman pengguna (UX), navigasi, dan desain interaksi yang intuitif dan mudah digunakan.', 'Pendukung', 'IS2020', NULL, 16, 'draft', NULL, NULL, NULL),
(17, 1, 'BK17', 'Emerging Technology', 'Eksplorasi teknologi-teknologi baru dan inovatif seperti kecerdasan buatan, Internet of Things (IoT), blockchain, dan teknologi disruptif lainnya yang mengubah lanskap industri.', 'Pendukung', 'IS2020', NULL, 17, 'draft', NULL, NULL, NULL),
(18, 1, 'BK18', 'Digital Inovation', 'Pengembangan ide-ide inovatif dan penerapan solusi digital untuk menciptakan nilai baru bagi bisnis dan masyarakat, termasuk pemikiran desain, inovasi model bisnis, dan kewirausahaan digital', 'Pendukung', 'IS2020', NULL, 17, 'draft', NULL, NULL, NULL),
(19, 1, 'BK19', 'Visualisasi Informasi', NULL, 'Pendukung', NULL, NULL, 18, 'draft', NULL, NULL, NULL),
(20, 1, 'BK20', 'Pemrograman Berorientasi Objek', NULL, 'Utama', NULL, NULL, 19, 'draft', NULL, NULL, NULL),
(21, 1, 'BK21', 'Pemrograman Web', NULL, 'Pendukung', NULL, NULL, 20, 'draft', NULL, NULL, NULL),
(22, 1, 'BK22', 'Pemrograman Mobile', NULL, 'Pendukung', NULL, NULL, 21, 'draft', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `batas_ketercapaian`
--

DROP TABLE IF EXISTS `batas_ketercapaian`;
CREATE TABLE `batas_ketercapaian` (
  `id` bigint UNSIGNED NOT NULL,
  `id_cpl` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `batas_nilai` decimal(5,2) NOT NULL DEFAULT '70.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batas_ketercapaian`
--

INSERT INTO `batas_ketercapaian` (`id`, `id_cpl`, `id_kurikulum`, `batas_nilai`) VALUES
(1, 1, 1, 70.00),
(2, 2, 1, 70.00),
(3, 3, 1, 70.00),
(4, 4, 1, 70.00),
(5, 5, 1, 70.00),
(6, 6, 1, 70.00),
(7, 7, 1, 70.00),
(8, 8, 1, 70.00),
(9, 9, 1, 70.00),
(10, 10, 1, 70.00),
(11, 11, 1, 70.00),
(12, 12, 1, 70.00),
(13, 13, 1, 70.00),
(14, 14, 1, 70.00);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1781963691),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1781963691;', 1781963691);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cpl_prodi`
--

DROP TABLE IF EXISTS `cpl_prodi`;
CREATE TABLE `cpl_prodi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `kode_cpl` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `referensi` text COLLATE utf8mb4_unicode_ci,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpl_prodi`
--

INSERT INTO `cpl_prodi` (`id`, `id_kurikulum`, `kode_cpl`, `deskripsi`, `kategori`, `referensi`, `urutan`, `status`, `approved_by`, `approved_at`, `deleted_at`) VALUES
(1, 1, 'CPL01', 'Mampu memahami, menganalisis, dan menilai konsep dasar dan peran sistem informasi dalam mengelola data dan memberikan rekomendasi pengambilan keputusan pada proses dan sistem organisasi.', 'Sikap', 'CPL-P01', 1, 'draft', NULL, NULL, NULL),
(2, 1, 'CPL02', 'Mampu merancang dan menggunakan database, serta mengolah dan menganalisa data dengan alat dan teknik pengolahan data', 'Sikap', 'CPL-KK01, CPL-P02', 2, 'draft', NULL, NULL, NULL),
(3, 1, 'CPL03', 'Mampu memahami dan menggunakan berbagai metodologi pengembangan sistem beserta alat pemodelan sistem dan menganalisa kebutuhan pengguna dalam membangun sistem informasi untuk mencapai tujuan organisasi', 'Sikap', 'CPL-KK03, CPL-KK04, CPL-P04, CPL-P05, CPL-KK09, CPL-KK10, CPL-KK11, CPL-KK12, CPL-P12, CPL-P13, CPL-P14', 3, 'draft', NULL, NULL, NULL),
(4, 1, 'CPL04', 'Mampu membuat perencanaan infrastruktur TI, arsitektur jaringan, layanan fisik dan cloud, menganalisa konsep identifikasi, otentikasi, otorisasi akses dalam konteks melindungi orang dan perangkat', 'Sikap', 'CPL-KK02, CPL-P03', 4, 'draft', NULL, NULL, NULL),
(5, 1, 'CPL05', 'Mampu memahami dan menerapkan kode etik dalam penggunaan informasi dan data pada perancangan, implementasi, dan penggunaan suatu sistem', 'Sikap', 'CPL-KK05, CPL-P06', 5, 'draft', NULL, NULL, NULL),
(6, 1, 'CPL06', 'Memiliki kemampuan merencanakan, menerapkan, memelihara dan meningkatkan sistem informasi organisasi untuk mencapai tujuan dan sasaran organisasi yang strategis baik jangka pendek maupun jangka panjang.', 'Sikap', 'CPL-KK06, CPL-KK07, CPL-P07, CPL-KK14', 6, 'draft', NULL, NULL, NULL),
(7, 1, 'CPL07', 'Mampu memahami, mengidentifikasi dan menerapkan konsep, teknik dan metodologi manajemen proyek sistem informasi.', 'Sikap', 'CPL-KK08, CPL-P08', 7, 'draft', NULL, NULL, NULL),
(8, 1, 'CPL08', 'Mampu memahami dan menerapkan konsep, metode, teknik, dan tahapan data mining serta visualisasi data dalam pengolahan data, pengorganisasian data, dan penyajian informasi yang efektif, efisien, dan estetik', 'Sikap', 'CPL-P10, CPL-P11, CPL-KK13, CPL-KK16', 8, 'draft', NULL, NULL, NULL),
(9, 1, 'CPL09', 'Mampu memahami dan menerapkan model sistem, metode dan berbagai teknik peningkatan bisnis proses,  peluang inovasi digital dalam pengelolaan bisnis bidang kesehatan yang memanfaatkan teknologi', 'Sikap', 'CPL-P09, CPL-KK15, CPL-P15, CPL-P16, CPL-P17', 9, 'draft', NULL, NULL, NULL),
(10, 1, 'CPL10', 'Mampu menunjukkan sikap profesionalitas, integritas, dan berjati diri islami yang dilengkapi dengan kemampuan komunikasi, kepemimpinan, bekerja sama dan bertanggung jawab atas pekerjaan di bidang keahliannya, bermasyarakat, berbangsa, dan bernegara sebagai warga negara yang bangga dan cinta tanah air berlandaskan nilai ahlus sunah waljamahah', 'Sikap', 'CPL-S01, CPL-S02, CPL-S03, CPL-S04, CPL-S06, CPL-S09, CPL-S11', 10, 'draft', NULL, NULL, NULL),
(11, 1, 'CPL11', 'Mampu menunjukkan sikap taat hukum, disiplin, dan menghargai keanekaragaman melalui internalisasi nilai, norma, etika akademik, semangat kemandirian, kejuangan, dan kewirausahaan', 'Sikap', 'CPL-S05, CPL-S07, CPL-S08, CPL-S10', 11, 'draft', NULL, NULL, NULL),
(12, 1, 'CPL12', 'Mampu menunjukkan kinerja mandiri, bermutu, terukur, berfikir logis, kritis, sistematis, dan inovatif, komunikatif dalam mengembangkan ilmu pengetahuan yang memperhatikan nilai humaniora sesuai bidang keahliannya', 'Sikap', 'CPL-KU01, CPL-KU02, CPL-KU10', 12, 'draft', NULL, NULL, NULL),
(13, 1, 'CPL13', 'Mampu mengkaji implikasi pengembangan ilmu pengetahuan dengan menerapkan keahliannya dalam rangka menghasilkan solusi, menyusun deskripsi saintifik hasil kajian dalam bentuk laporan ilmiah yang sahih dan original dan memelihara serta mengembangan jaringan kerja dengan pembimbing, kolega, sejawat baik di dalam maupun di luar lembaga', 'Sikap', 'CPL-KU03, CPL-KU04, CPL-KU05, CPL-KU06, CPL-KU09', 13, 'draft', NULL, NULL, NULL),
(14, 1, 'CPL14', 'Mampu melakukan evaluasi diri dan supervisi terhadap penyelesaian pekerjaan sebagai wujud tanggung jawab atas pencapaian hasil kelompok kerja', 'Sikap', 'CPL-KU07, CPL-KU08', 14, 'draft', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cpl_sndikti`
--

DROP TABLE IF EXISTS `cpl_sndikti`;
CREATE TABLE `cpl_sndikti` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('Sikap','Keterampilan Umum','Keterampilan Khusus','Pengetahuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpl_sndikti`
--

INSERT INTO `cpl_sndikti` (`id`, `kode`, `deskripsi`, `kategori`, `urutan`, `status`, `approved_by`, `approved_at`) VALUES
(1, 'CPL-S01', 'Bertakwa kepada Tuhan Yang Maha Esa dan mampu menunjukkan sikap religius.', 'Sikap', 1, 'draft', NULL, NULL),
(2, 'CPL-S02', 'Menjunjung tinggi nilai kemanusiaan dalam menjalankan tugas berdasarkan agama, moral dan etika.', 'Sikap', 2, 'draft', NULL, NULL),
(3, 'CPL-S03', 'Berkontribusi dalam peningkatan mutu kehidupan bermasyarakat, berbangsa, dan bernegara berdasarkan Pancasila.', 'Sikap', 3, 'draft', NULL, NULL),
(4, 'CPL-S04', 'Berperan sebagai warga negara yang bangga dan cinta tanah air, memiliki nasionalisme serta rasa tanggungjawab pada negara dan bangsa.', 'Sikap', 4, 'draft', NULL, NULL),
(5, 'CPL-S05', 'Menghargai keanekaragaman budaya, pandangan, agama, dan kepercayaan, serta pendapat atau temuan orisinal orang lain.', 'Sikap', 5, 'draft', NULL, NULL),
(6, 'CPL-S06', 'Taat hukum dan disiplin dalam kehidupan bermasyarakat dan bernegara.', 'Sikap', 6, 'draft', NULL, NULL),
(7, 'CPL-S07', 'Menginternalisasi nilai, norma, dan etika akademik.', 'Sikap', 7, 'draft', NULL, NULL),
(8, 'CPL-S08', 'Menunjukkan sikap bertanggungjawab atas pekerjaan di bidang keahliannya secara mandiri.', 'Sikap', 8, 'draft', NULL, NULL),
(9, 'CPL-S09', 'Menginternalisasi semangat kemandirian, kejuangan, dan kewirausahaan.', 'Sikap', 9, 'draft', NULL, NULL),
(10, 'CPL-S10', 'Menginternalisasi nilai-nilai ahlussunah waljamaah (Aswaja)', 'Sikap', 10, 'draft', NULL, NULL),
(11, 'CPL-KU01', 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam konteks pengembangan atau implementasi ilmu pengetahuan dan teknologi yang memperhatikan dan menerapkan nilai humaniora yang sesuai dengan bidang keahliannya.', 'Keterampilan Umum', 1, 'draft', NULL, NULL),
(12, 'CPL-KU02', 'Mampu menunjukkan kinerja mandiri, bermutu, dan terukur.', 'Keterampilan Umum', 2, 'draft', NULL, NULL),
(13, 'CPL-KU03', 'Mampu mengkaji implikasi pengembangan atau implementasi ilmu pengetahuan teknologi yang memperhatikan dan menerapkan nilai humaniora sesuai dengan keahliannya berdasarkan kaidah, tata cara dan etika ilmiah dalam rangka menghasilkan solusi, gagasan, desain atau kritik seni, menyusun deskripsi saintifik hasil kajiannya dalam bentuk skripsi atau laporan tugas akhir, dan mengunggahnya dalam laman perguruan tinggi.', 'Keterampilan Umum', 3, 'draft', NULL, NULL),
(14, 'CPL-KU04', 'Menyusun deskripsi saintifik hasil kajian tersebut di atas dalam bentuk skripsi atau laporan tugas akhir, dan mengunggahnya dalam laman perguruan tinggi.', 'Keterampilan Umum', 4, 'draft', NULL, NULL),
(15, 'CPL-KU05', 'Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah di bidang keahliannya, berdasarkan hasil analisis informasi dan data.', 'Keterampilan Umum', 5, 'draft', NULL, NULL),
(16, 'CPL-KU06', 'Mampu memelihara dan mengembangkan jaringan kerja dengan pembimbing, kolega, sejawat baik di dalam maupun di luar lembaganya.', 'Keterampilan Umum', 6, 'draft', NULL, NULL),
(17, 'CPL-KU07', 'Mampu bertanggungjawab atas pencapaian hasil kerja kelompok dan melakukan supervisi dan evaluasi terhadap penyelesaian pekerjaan yang ditugaskan kepada pekerja yang berada di bawah tanggungjawabnya.', 'Keterampilan Umum', 7, 'draft', NULL, NULL),
(18, 'CPL-KU08', 'Mampu melakukan proses evaluasi diri terhadap kelompok kerja yang berada dibawah tanggung jawabnya, dan mampu mengelola pembelajaran secara mandiri.', 'Keterampilan Umum', 8, 'draft', NULL, NULL),
(19, 'CPL-KU09', 'Mampu mendokumentasikan, menyimpan, mengamankan, dan menemukan kembali data untuk menjamin kesahihan dan mencegah plagiasi.', 'Keterampilan Umum', 9, 'draft', NULL, NULL),
(20, 'CPL-KU10', 'Berkomunikasi secara efektif dalam berbagai konteks profesional', 'Keterampilan Umum', 10, 'draft', NULL, NULL),
(21, 'CPL-KK01', 'Mampu membangun, mengelola, menggunakan dan mengamankan database dengan alat dan teknik dalam sistem basis data yang akan menghasilkan model relasional', 'Keterampilan Khusus', 1, 'draft', NULL, NULL),
(22, 'CPL-KK02', 'Mampu membuat perencanaan infrastruktur TI, arsitektur jaringan,\r\nlayanan fisik dan cloud, menganalisa  konsep identifikasi, otentikasi, otorisasi akses dalam konteks melindungi orang dan perangkat', 'Keterampilan Khusus', 2, 'draft', NULL, NULL),
(23, 'CPL-KK03', 'Mampu menerapkan metodologi pengembangan sistem informasi beserta alat pemodelannya meliputi pengembangan sistem berorientasi objek, system development life cycle (SDLC).', 'Keterampilan Khusus', 3, 'draft', NULL, NULL),
(24, 'CPL-KK04', 'Mampu menerapkan dasar logika, prinsip matematika, ekspresi, aspek modular,  linearitas dan non-linearitas struktur data pada pemrograman perangkat lunak', 'Keterampilan Khusus', 4, 'draft', NULL, NULL),
(25, 'CPL-KK05', 'Mampu memahami, menerapkan kode etik dalam penggunaan informasi dan data pada perancangan, implementasi, dan penggunaan suatu sistem', 'Keterampilan Khusus', 5, 'draft', NULL, NULL),
(26, 'CPL-KK06', 'Memiliki kemampuan merencanakan, menerapkan, memelihara dan meningkatkan sistem informasi organisasi untuk mencapai tujuan dan sasaran organisasi yang strategis baik jangka pendek maupun jangka panjang.', 'Keterampilan Khusus', 6, 'draft', NULL, NULL),
(27, 'CPL-KK07', 'Memiliki kemampuan untuk memantau, mengevaluasi dan mengendalikan sumberdaya sistem informasi untuk memastikan keselarasan, pencapaian dan sasaran strategis organisasi.', 'Keterampilan Khusus', 7, 'draft', NULL, NULL),
(28, 'CPL-KK08', 'Mampu membangun perangkat lunak dalam sebuah proyek sistem informasi', 'Keterampilan Khusus', 8, 'draft', NULL, NULL),
(29, 'CPL-KK09', 'Mampu menerapkan paradigma pemrograman berorientasi objek secara fundamental berdasarkan object, kelas, pewarisan, enkapsulasi, abstraksi dan polimorfisme', 'Keterampilan Khusus', 9, 'draft', NULL, NULL),
(30, 'CPL-KK10', 'Mampu menerapkan fungsi dan bahasa pemrograman serta memperhatikan aspek keamanan pada aplikasi berbasis web di sisi client dan server', 'Keterampilan Khusus', 10, 'draft', NULL, NULL),
(31, 'CPL-KK11', 'Mampu menerapkan fungsi dan bahasa pemrograman pada aplikasi berbasis perangkat bergerak', 'Keterampilan Khusus', 11, 'draft', NULL, NULL),
(32, 'CPL-KK12', 'Mampu menerapkan konsep, metode dan teknik dalam merancang UI/UX', 'Keterampilan Khusus', 12, 'draft', NULL, NULL),
(33, 'CPL-KK13', 'Memiliki kemampuan pengolahan data yaitu pemfilteran, agregasi dan\r\npengorganisasian serta menyajikan informasi yang efektif, efisien, estetik dalam analisis dan visualisasi data', 'Keterampilan Khusus', 13, 'draft', NULL, NULL),
(34, 'CPL-KK14', 'Memiliki kemampuan dalam mengidentifikasi, menilai, menganalisis dan memberikan rekomendasi terkait  manajemen risiko teknologi informasi dalam organisasi.', 'Keterampilan Khusus', 14, 'draft', NULL, NULL),
(35, 'CPL-KK15', 'Memiliki kemampuan dalam pengelolaan bisnis dengan memanfaatkan teknologi informasi', 'Keterampilan Khusus', 15, 'draft', NULL, NULL),
(36, 'CPL-KK16', 'Memiliki kemampuan dalam melakukan fungsi klasifikasi, klasterisasi, regresi, deteksi anomali, pembelajaran atura asosiasi, perangkuman, baik secara deskriptif maupun prediktif di dalam memahami masalah data secara tepat', 'Keterampilan Khusus', 16, 'draft', NULL, NULL),
(37, 'CPL-P01', 'Mampu memahami, menganalisis, dan menilai konsep dasar dan peran sistem informasi dalam mengelola data dan memberikan rekomendasi pengambilan keputusan pada proses dan sistem organisasi.', 'Pengetahuan', 1, 'draft', NULL, NULL),
(38, 'CPL-P02', 'Mampu memahami dan menjelaskan konsep basis data, struktur data dan visualisasi data secara menyeluruh', 'Pengetahuan', 2, 'draft', NULL, NULL),
(39, 'CPL-P03', 'Mampu memahami dan menjelaskan konsep infrastruktur TI, arsitektur\r\njaringan, layanan fisik dan cloud untuk menganalisa konsep identifikasi, otentikasi, otorisasi akses dalam konteks melindungi orang dan perangkat', 'Pengetahuan', 3, 'draft', NULL, NULL),
(40, 'CPL-P04', 'Mampu memahami dan menjelaskan metodologi pengembangan sistem informasi mulai dari pengembangan sistem berorientasi objek, software development life cycle (SDLC), dan pengembangan agile', 'Pengetahuan', 4, 'draft', NULL, NULL),
(41, 'CPL-P05', 'Mampu memahami dan menjelaskan dasar logika, prinsip matematika,\r\nekspresi, aspek modular, linearitas dan non-linearitas struktur data pada perangkat lunak', 'Pengetahuan', 5, 'draft', NULL, NULL),
(42, 'CPL-P06', 'Mampu memahami dan mengkaji dasar  hukum kode etik dalam penggunaan\r\ninformasi dan data pada perancangan, implementasi, dan penggunaan suatu sistem', 'Pengetahuan', 6, 'draft', NULL, NULL),
(43, 'CPL-P07', 'Mampu memahami dan menjelaskan  konsep perencanaan strategis, resiko\r\norganisasi, serta kerangka kerja tata kelola sistem informasi', 'Pengetahuan', 7, 'draft', NULL, NULL),
(44, 'CPL-P08', 'Mampu memahami konsep, teknik pada manajemen proyek untuk memenuhi business requirement berdasarkan kriteria pengambilan keputusan', 'Pengetahuan', 8, 'draft', NULL, NULL),
(45, 'CPL-P09', 'Mampu memahami, mengidentifikasi, merekomendasikan kebutuhan bisnis terhadap dampak penggunaan teknologi di dalam masyarakat dan bisnis', 'Pengetahuan', 9, 'draft', NULL, NULL),
(46, 'CPL-P10', 'Mampu memahami permasalahan bisnis berdasarkan analisis data di dalam organisasi sebagai pendukung pengambilan keputusan', 'Pengetahuan', 10, 'draft', NULL, NULL),
(47, 'CPL-P11', 'Mampu memahami konsep, metode, teknik dan tahapan data mining serta visualisasi data sebagai pengetahuan yang berkaitan dengan teknologi informasi', 'Pengetahuan', 11, 'draft', NULL, NULL),
(48, 'CPL-P12', 'Mampu memahami fungsi dan bahasa pemrograman serta memperhatikan\r\naspek keamanan pada aplikasi berbasis web di sisi client dan server', 'Pengetahuan', 12, 'draft', NULL, NULL),
(49, 'CPL-P13', 'Mampu memahami fungsi dan bahasa pemrograman pada aplikasi berbasis perangkat bergerak', 'Pengetahuan', 13, 'draft', NULL, NULL),
(50, 'CPL-P14', 'Mampu memahami konsep, metode dan teknik dalam merancang UI/UX', 'Pengetahuan', 14, 'draft', NULL, NULL),
(51, 'CPL-P15', 'Mampu memahami dan melihat peluang inovasi digital untuk mengembangkan model bisnis digital yang baru', 'Pengetahuan', 15, 'draft', NULL, NULL),
(52, 'CPL-P16', 'Mampu memahami model sistem, metode dan berbagai teknik peningkatan bisnis proses yang mendatangkan suatu nilai untuk organisasi.', 'Pengetahuan', 16, 'draft', NULL, NULL),
(53, 'CPL-P17', 'Memiliki pemahaman mengenai dasardasar bisnis dan pengetahuan pendukung lainnya yang berkaitan dengan teknologi informasi', 'Pengetahuan', 17, 'draft', NULL, NULL),
(54, 'CPL-S11', 'Bekerja sama dan memiliki kepekaan sosial serta kepedulian terhadap masyarakat dan lingkungan.', 'Sikap', 11, 'draft', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cpmk`
--

DROP TABLE IF EXISTS `cpmk`;
CREATE TABLE `cpmk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_cpl` bigint UNSIGNED NOT NULL,
  `kode_cpmk` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `level_bloom` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpmk`
--

INSERT INTO `cpmk` (`id`, `id_kurikulum`, `id_mk`, `id_cpl`, `kode_cpmk`, `deskripsi`, `urutan`, `level_bloom`, `deleted_at`) VALUES
(1, 1, 3, 1, 'CPMK-MK02-1', 'Mahasiswa mampu memahami dan menerapkan konsep AGAMA yang terkait dengan capaian CPL01.', 1, NULL, NULL),
(2, 1, 4, 2, 'CPMK-MK03-1', 'Mahasiswa mampu memahami dan menerapkan konsep PANCASILA yang terkait dengan capaian CPL02.', 1, NULL, NULL),
(3, 1, 3, 5, 'CPMK-MK02-2', 'Mahasiswa mampu memahami dan menerapkan konsep AGAMA yang terkait dengan capaian CPL05.', 2, NULL, NULL),
(4, 1, 3, 8, 'CPMK-MK02-3', 'Mahasiswa mampu memahami dan menerapkan konsep AGAMA yang terkait dengan capaian CPL08.', 3, NULL, NULL),
(5, 1, 3, 10, 'CPMK-MK02-4', 'Mahasiswa mampu memahami dan menerapkan konsep AGAMA yang terkait dengan capaian CPL10.', 4, NULL, NULL),
(6, 1, 4, 11, 'CPMK-MK03-2', 'Mahasiswa mampu memahami dan menerapkan konsep PANCASILA yang terkait dengan capaian CPL11.', 2, NULL, NULL),
(7, 1, 5, 13, 'CPMK-MK04-1', 'Mahasiswa mampu memahami dan menerapkan konsep BAHASA INDONESIA yang terkait dengan capaian CPL13.', 1, NULL, NULL),
(8, 1, 5, 14, 'CPMK-MK04-2', 'Mahasiswa mampu memahami dan menerapkan konsep BAHASA INDONESIA yang terkait dengan capaian CPL14.', 2, NULL, NULL),
(9, 1, 7, 13, 'CPMK-MK06-1', 'Mahasiswa mampu memahami dan menerapkan konsep ALGORITMA DAN PEMROGRAMAN yang terkait dengan capaian CPL13.', 1, NULL, NULL),
(10, 1, 9, 8, 'CPMK-MK08-1', 'Mahasiswa mampu memahami dan menerapkan konsep MATEMATIKA DISKRIT yang terkait dengan capaian CPL08.', 1, NULL, NULL),
(11, 1, 19, 6, 'CPMK-MK18-1', 'Mahasiswa mampu memahami dan menerapkan konsep PENGANTAR AKUNTANSI yang terkait dengan capaian CPL06.', 1, NULL, NULL),
(12, 1, 21, 9, 'CPMK-MK20-1', 'Mahasiswa mampu memahami dan menerapkan konsep ANALISIS DAN PERANCANGAN SISTEM yang terkait dengan capaian CPL09.', 1, NULL, NULL),
(13, 1, 22, 9, 'CPMK-MK21-1', 'Mahasiswa mampu memahami dan menerapkan konsep DESAIN UI/UX yang terkait dengan capaian CPL09.', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cpmk_penilaian`
--

DROP TABLE IF EXISTS `cpmk_penilaian`;
CREATE TABLE `cpmk_penilaian` (
  `id` bigint UNSIGNED NOT NULL,
  `id_cpmk` bigint UNSIGNED NOT NULL,
  `skor_maks` decimal(5,2) DEFAULT NULL COMMENT 'Skor maks per CPMK (default = sum bobot penilaian)',
  `tahap_penilaian` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Awal-Tengah Semester',
  `teknik_quiz` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_observasi` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_unjuk_kerja` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_uts` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_uas` tinyint(1) NOT NULL DEFAULT '0',
  `teknik_tes_lisan` tinyint(1) NOT NULL DEFAULT '0',
  `bobot_quiz` decimal(5,2) DEFAULT NULL,
  `bobot_observasi` decimal(5,2) DEFAULT NULL,
  `bobot_unjuk_kerja` decimal(5,2) DEFAULT NULL,
  `bobot_uts` decimal(5,2) DEFAULT NULL,
  `bobot_uas` decimal(5,2) DEFAULT NULL,
  `bobot_tes_lisan` decimal(5,2) DEFAULT NULL,
  `instrumen` text COLLATE utf8mb4_unicode_ci,
  `kriteria` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `distribusi_semester`
--

DROP TABLE IF EXISTS `distribusi_semester`;
CREATE TABLE `distribusi_semester` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `total_sks` int NOT NULL DEFAULT '0',
  `jumlah_mk` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `distribusi_semester`
--

INSERT INTO `distribusi_semester` (`id`, `id_kurikulum`, `semester`, `total_sks`, `jumlah_mk`, `keterangan`) VALUES
(14, 1, 1, 18, 7, NULL),
(15, 1, 2, 18, 8, NULL),
(16, 1, 3, 19, 6, NULL),
(17, 1, 4, 20, 7, NULL),
(18, 1, 5, 24, 8, NULL),
(19, 1, 6, 32, 11, NULL),
(20, 1, 7, 24, 8, NULL),
(21, 1, 8, 35, 11, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_mk`
--

DROP TABLE IF EXISTS `enrollment_mk`;
CREATE TABLE `enrollment_mk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `tanggal_daftar` date DEFAULT NULL,
  `status` enum('aktif','mengulang','lulus','tidak_lulus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollment_mk`
--

INSERT INTO `enrollment_mk` (`id`, `id_mahasiswa`, `id_mk`, `id_semester`, `tanggal_daftar`, `status`) VALUES
(1, 8, 6, 2, '2026-06-20', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_cpl`
--

DROP TABLE IF EXISTS `hasil_cpl`;
CREATE TABLE `hasil_cpl` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `id_cpl` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `nilai_cpl` decimal(6,2) NOT NULL,
  `skor_maks` decimal(6,2) NOT NULL DEFAULT '100.00',
  `total` decimal(6,2) DEFAULT NULL,
  `status_tercapai` tinyint NOT NULL DEFAULT '0',
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_cpmk`
--

DROP TABLE IF EXISTS `hasil_cpmk`;
CREATE TABLE `hasil_cpmk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `id_cpmk` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `nilai` decimal(6,2) NOT NULL,
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_pl`
--

DROP TABLE IF EXISTS `hasil_pl`;
CREATE TABLE `hasil_pl` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `id_pl` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `nilai_pl` decimal(6,2) NOT NULL,
  `status_tercapai` tinyint NOT NULL DEFAULT '0',
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_sub_cpmk`
--

DROP TABLE IF EXISTS `hasil_sub_cpmk`;
CREATE TABLE `hasil_sub_cpmk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `id_sub_cpmk` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `nilai` decimal(6,2) NOT NULL,
  `recalculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_mengajar`
--

DROP TABLE IF EXISTS `jurnal_mengajar`;
CREATE TABLE `jurnal_mengajar` (
  `id` bigint UNSIGNED NOT NULL,
  `id_rps_pertemuan` bigint UNSIGNED NOT NULL,
  `id_dosen` bigint UNSIGNED NOT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `realisasi_materi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_hadir` int DEFAULT NULL,
  `file_bukti_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komentar_review`
--

DROP TABLE IF EXISTS `komentar_review`;
CREATE TABLE `komentar_review` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `konten` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `elemen` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('open','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komponen_asesmen`
--

DROP TABLE IF EXISTS `komponen_asesmen`;
CREATE TABLE `komponen_asesmen` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `id_sub_cpmk` bigint UNSIGNED DEFAULT NULL,
  `nama_komponen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_komponen` enum('Partisipasi','Observasi','Unjuk Kerja','UTS','UAS','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_persen` decimal(5,2) NOT NULL,
  `skor_maks` decimal(5,2) NOT NULL DEFAULT '100.00'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `kurikulum`
--

DROP TABLE IF EXISTS `kurikulum`;
CREATE TABLE `kurikulum` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kurikulum` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_studi` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenjang` enum('D3','D4','S1','S2','S3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S1',
  `tahun_mulai` year NOT NULL,
  `tahun_selesai` year NOT NULL,
  `visi` text COLLATE utf8mb4_unicode_ci,
  `misi` text COLLATE utf8mb4_unicode_ci,
  `tujuan` text COLLATE utf8mb4_unicode_ci,
  `sasaran` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','aktif','arsip') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_by` bigint UNSIGNED DEFAULT NULL,
  `dibuat_oleh` bigint UNSIGNED DEFAULT NULL,
  `disahkan_oleh` bigint UNSIGNED DEFAULT NULL,
  `disahkan_pada` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kurikulum`
--

INSERT INTO `kurikulum` (`id`, `kode`, `nama_kurikulum`, `program_studi`, `jenjang`, `tahun_mulai`, `tahun_selesai`, `visi`, `misi`, `tujuan`, `sasaran`, `status`, `locked_at`, `locked_by`, `dibuat_oleh`, `disahkan_oleh`, `disahkan_pada`, `created_at`, `updated_at`) VALUES
(1, 'K-SI-S1-2026', 'Kurikulum Sistem Informasi 2026', 'Sistem Informasi', 'S1', '2026', '2031', 'Menjadi program studi Sistem Informasi yang unggul dan berdaya saing di tingkat nasional pada tahun 2031, menghasilkan lulusan profesional di bidang sistem informasi yang berintegritas, inovatif, dan adaptif terhadap perkembangan teknologi.', '1. Menyelenggarakan pendidikan Sistem Informasi berbasis Outcome-Based Education (OBE) yang berkualitas dan relevan dengan kebutuhan industri.\n2. Melaksanakan penelitian di bidang sistem informasi yang inovatif dan bermanfaat bagi masyarakat.\n3. Melaksanakan pengabdian kepada masyarakat melalui penerapan teknologi sistem informasi.\n4. Menjalin kerja sama dengan dunia industri, pemerintah, dan masyarakat untuk meningkatkan mutu dan penyerapan lulusan.', '1. Menghasilkan lulusan yang kompeten dalam analisis, perancangan, pengembangan, dan pengelolaan sistem informasi.\n2. Menghasilkan penelitian dan karya ilmiah di bidang sistem informasi yang berkontribusi bagi pengembangan keilmuan.\n3. Menerapkan ilmu sistem informasi untuk menyelesaikan permasalahan nyata di masyarakat dan industri.\n4. Menghasilkan lulusan yang berjiwa wirausaha, beretika, dan mampu beradaptasi dengan perkembangan teknologi.', '1. Tercapainya kelulusan tepat waktu dengan IPK dan capaian pembelajaran yang memenuhi standar.\n2. Meningkatnya jumlah serta mutu penelitian dan publikasi dosen maupun mahasiswa.\n3. Terjalinnya kemitraan dengan industri dan instansi untuk magang, sertifikasi, dan penyerapan lulusan.\n4. Meningkatnya kompetensi lulusan sesuai kebutuhan dunia kerja bidang sistem informasi.', 'aktif', NULL, NULL, 5, NULL, NULL, '2026-06-20 04:09:07', '2026-06-20 06:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `master_kategori`
--

DROP TABLE IF EXISTS `master_kategori`;
CREATE TABLE `master_kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `urutan` int NOT NULL DEFAULT '0',
  `is_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_kategori`
--

INSERT INTO `master_kategori` (`id`, `jenis`, `nama`, `deskripsi`, `urutan`, `is_aktif`, `created_at`, `updated_at`) VALUES
(1, 'pl', 'Penciri Utama', NULL, 1, 1, '2026-06-20 04:15:37', '2026-06-20 04:15:37'),
(2, 'pl', 'Ketrampilan Khusus', NULL, 2, 1, '2026-06-20 04:15:57', '2026-06-20 04:15:57'),
(3, 'pl', 'Sikap', NULL, 3, 1, '2026-06-20 04:16:05', '2026-06-20 04:16:05'),
(4, 'pl', 'Ketrampilan Umum', NULL, 4, 1, '2026-06-20 04:16:20', '2026-06-20 04:16:20'),
(5, 'cpl', 'Sikap', NULL, 1, 1, '2026-06-20 05:13:09', '2026-06-20 05:13:09'),
(6, 'cpl', 'Keterampilan Umum', NULL, 2, 1, '2026-06-20 05:13:09', '2026-06-20 05:13:09'),
(7, 'cpl', 'Keterampilan Khusus', NULL, 3, 1, '2026-06-20 05:13:09', '2026-06-20 05:13:09'),
(8, 'cpl', 'Pengetahuan', NULL, 4, 1, '2026-06-20 05:13:09', '2026-06-20 05:13:09'),
(9, 'bk', 'Utama', NULL, 1, 1, '2026-06-20 05:13:09', '2026-06-20 05:14:58'),
(10, 'bk', 'Pendukung', NULL, 2, 1, '2026-06-20 05:13:09', '2026-06-20 05:13:09'),
(11, 'bk', 'Umum', NULL, 3, 1, '2026-06-20 05:13:09', '2026-06-20 05:13:09'),
(12, 'mk', 'Wajib', NULL, 1, 1, '2026-06-20 06:52:47', '2026-06-20 06:52:47'),
(13, 'mk', 'Pilihan', NULL, 2, 1, '2026-06-20 06:52:47', '2026-06-20 06:52:47'),
(14, 'mk', 'MKWK', NULL, 3, 1, '2026-06-20 06:52:47', '2026-06-20 06:52:47'),
(15, 'mk', 'MKDU', NULL, 4, 1, '2026-06-20 06:52:47', '2026-06-20 06:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

DROP TABLE IF EXISTS `mata_kuliah`;
CREATE TABLE `mata_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `kode_mk` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mk` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks_teori` int NOT NULL DEFAULT '0',
  `sks_praktikum` int NOT NULL DEFAULT '0',
  `sks_total` int GENERATED ALWAYS AS ((`sks_teori` + `sks_praktikum`)) STORED,
  `semester` int NOT NULL,
  `kategori_mk` enum('Wajib','Pilihan','MKWK','MKDU') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kompetensi_mk` enum('Utama','Pendukung') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Utama',
  `kode_prasyarat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konsentrasi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `id_kurikulum`, `kode_mk`, `nama_mk`, `sks_teori`, `sks_praktikum`, `semester`, `kategori_mk`, `kompetensi_mk`, `kode_prasyarat`, `konsentrasi`, `deleted_at`) VALUES
(2, 1, 'MK01', 'Agama', 3, 0, 1, NULL, 'Utama', NULL, NULL, '2026-06-20 06:10:51'),
(3, 1, 'MK02', 'AGAMA', 3, 0, 1, 'Wajib', 'Utama', NULL, NULL, NULL),
(4, 1, 'MK03', 'PANCASILA', 2, 0, 1, 'Wajib', 'Utama', NULL, NULL, NULL),
(5, 1, 'MK04', 'BAHASA INDONESIA', 2, 0, 1, 'Wajib', 'Utama', NULL, NULL, NULL),
(6, 1, 'MK05', 'PENGANTAR SISTEM INFORMASI', 3, 0, 1, 'Wajib', 'Utama', NULL, NULL, NULL),
(7, 1, 'MK06', 'ALGORITMA DAN PEMROGRAMAN', 3, 0, 1, 'Wajib', 'Utama', NULL, NULL, NULL),
(8, 1, 'MK07', 'LITERASI TIK', 2, 0, 1, 'Wajib', 'Utama', NULL, NULL, NULL),
(9, 1, 'MK08', 'MATEMATIKA DISKRIT', 3, 0, 1, 'Wajib', 'Utama', NULL, NULL, NULL),
(10, 1, 'MK09', 'ASWAJA', 2, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(11, 1, 'MK10', 'KEWARGANEGARAAN', 2, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(12, 1, 'MK11', 'LOGIKA DAN METODE BERFIKIR KRITIS', 2, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(13, 1, 'MK12', 'MANAJEMEN DATA', 2, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(14, 1, 'MK13', 'STRUKTUR DATA', 2, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(15, 1, 'MK14', 'SISTEM OPERASI', 2, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(16, 1, 'MK15', 'PENGANTAR MANAJEMEN', 3, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(17, 1, 'MK16', 'MANAJEMEN PROSES BISNIS', 3, 0, 2, 'Wajib', 'Utama', NULL, NULL, NULL),
(18, 1, 'MK17', 'KEWIRAUSAHAAN', 3, 0, 3, 'Wajib', 'Utama', NULL, NULL, NULL),
(19, 1, 'MK18', 'PENGANTAR AKUNTANSI', 3, 0, 3, 'Wajib', 'Utama', NULL, NULL, NULL),
(20, 1, 'MK19', 'PEMROGRAMAN BERORIENTASI OBJEK', 3, 0, 3, 'Wajib', 'Utama', NULL, NULL, NULL),
(21, 1, 'MK20', 'ANALISIS DAN PERANCANGAN SISTEM', 3, 0, 3, 'Wajib', 'Utama', NULL, NULL, NULL),
(22, 1, 'MK21', 'DESAIN UI/UX', 3, 0, 3, 'Wajib', 'Utama', NULL, NULL, NULL),
(23, 1, 'MK22', 'SISTEM DAN MANAJEMEN BASIS DATA', 4, 0, 3, 'Wajib', 'Utama', NULL, NULL, NULL),
(24, 1, 'MK23', 'PEMROGRAMAN WEB', 3, 0, 4, 'Wajib', 'Utama', NULL, NULL, NULL),
(25, 1, 'MK24', 'MANAJEMEN PROYEK SISTEM INFORMASI', 3, 0, 4, 'Wajib', 'Utama', NULL, NULL, NULL),
(26, 1, 'MK25', 'DATA SCIENCE', 2, 0, 4, 'Wajib', 'Utama', NULL, NULL, NULL),
(27, 1, 'MK26', 'SISTEM ENTERPRISE', 3, 0, 4, 'Wajib', 'Utama', NULL, NULL, NULL),
(28, 1, 'MK27', 'RINTISAN BISNIS DIGITAL', 3, 0, 4, 'Wajib', 'Utama', NULL, NULL, NULL),
(29, 1, 'MK28', 'DESAIN DAN MANAJEMEN JARINGAN', 3, 0, 4, 'Wajib', 'Utama', NULL, NULL, NULL),
(30, 1, 'MK29', 'RISET OPERASI', 3, 0, 4, 'Wajib', 'Utama', NULL, NULL, NULL),
(31, 1, 'MK30', 'PENGUJIAN PERANGKAT LUNAK', 3, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(32, 1, 'MK31', 'MANAJEMEN INVESTASI TI', 3, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(33, 1, 'MK32', 'PROTEKSI ASET INFORMASI', 3, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(34, 1, 'MK33', 'DATA WAREHOUSE DAN DATA MINING', 3, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(35, 1, 'MK34', 'TATA KELOLA TI', 3, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(36, 1, 'MK35', 'CAPSTONE PROJECT', 4, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(37, 1, 'MK36', 'STATISTIK', 3, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(38, 1, 'MK37', 'BAHASA INGGRIS', 2, 0, 5, 'Wajib', 'Utama', NULL, NULL, NULL),
(39, 1, 'MK38', 'PERENCANAAN STRATEGIS SI/TI', 3, 0, 6, 'Wajib', 'Utama', NULL, NULL, NULL),
(40, 1, 'MK39', 'MANAJEMEN LAYANAN TI', 3, 0, 6, 'Wajib', 'Utama', NULL, NULL, NULL),
(41, 1, 'MK40', 'ETIKA PROFESI', 2, 0, 6, 'Wajib', 'Utama', NULL, NULL, NULL),
(42, 1, 'MK41', 'MAGANG', 5, 0, 7, 'Wajib', 'Utama', NULL, NULL, NULL),
(43, 1, 'MK42', 'METODOLOGI PENELITIAN', 3, 0, 7, 'Wajib', 'Utama', NULL, NULL, NULL),
(44, 1, 'MK43', 'KETERAMPILAN INTERPERSONAL', 2, 0, 7, 'Wajib', 'Utama', NULL, NULL, NULL),
(45, 1, 'MK44', 'KULIAH KERJA NYATA', 2, 0, 7, 'Wajib', 'Utama', NULL, NULL, NULL),
(46, 1, 'MK45', 'TUGAS AKHIR', 6, 0, 8, 'Wajib', 'Utama', NULL, NULL, NULL),
(47, 1, 'MK46', 'TEKNOLOGI DAN MASYARAKAT', 3, 0, 8, 'Wajib', 'Utama', NULL, NULL, NULL),
(48, 1, 'MK47', 'KULIAH LAPANGAN', 2, 0, 8, 'Wajib', 'Utama', NULL, NULL, NULL),
(49, 1, 'MK48', 'PEMROGRAMAN MOBILE', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(50, 1, 'MK49', 'INTERNET UNTUK SEGALA', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(51, 1, 'MK50', 'TEKNOLOGI PEMROGRAMAN', 3, 0, 7, 'Pilihan', 'Utama', NULL, NULL, NULL),
(52, 1, 'MK51', 'ARSITEKTUR TEKNOLOGI', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL),
(53, 1, 'MK52', 'KOMPUTASI AWAN', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL),
(54, 1, 'MK53', 'MANAJEMEN RISIKO TI', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(55, 1, 'MK54', 'MANAJEMEN PERUBAHAN', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(56, 1, 'MK55', 'PENGUKURAN KINERJA TI', 3, 0, 7, 'Pilihan', 'Utama', NULL, NULL, NULL),
(57, 1, 'MK56', 'MANAJEMEN KEBERLANGSUNGAN BISNIS', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL),
(58, 1, 'MK57', 'AUDIT SISTEM INFORMASI', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL),
(59, 1, 'MK58', 'SISTEM PENDUKUNG KEPUTUSAN', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(60, 1, 'MK59', 'VISUALISASI INFORMASI', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(61, 1, 'MK60', 'SISTEM CERDAS', 3, 0, 7, 'Pilihan', 'Utama', NULL, NULL, NULL),
(62, 1, 'MK61', 'TEKNIK PERAMALAN', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL),
(63, 1, 'MK62', 'PENGOLAHAN CITRA', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL),
(64, 1, 'MK63', 'MANAJEMEN MEREK DIGITAL', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(65, 1, 'MK64', 'PEMASARAN DIGITAL', 3, 0, 6, 'Pilihan', 'Utama', NULL, NULL, NULL),
(66, 1, 'MK65', 'KREATIF DIGITAL', 3, 0, 7, 'Pilihan', 'Utama', NULL, NULL, NULL),
(67, 1, 'MK66', 'MANAJEMEN HUBUNGAN PELANGGAN', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL),
(68, 1, 'MK67', 'MANAJEMEN RANTAI PASOK', 3, 0, 8, 'Pilihan', 'Utama', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000001_create_kurikulum_table', 1),
(5, '2025_01_01_000002_create_semester_akademik_table', 1),
(7, '2025_01_01_000004_create_arsip_rapat_table', 1),
(9, '2025_01_01_000006_create_pl_table', 1),
(10, '2025_01_01_000007_create_cpl_sndikti_table', 1),
(11, '2025_01_01_000008_create_cpl_prodi_table', 1),
(12, '2025_01_01_000009_create_bahan_kajian_table', 1),
(13, '2025_01_01_000010_create_mata_kuliah_table', 1),
(14, '2025_01_01_000011_create_cpmk_table', 1),
(15, '2025_01_01_000012_create_sub_cpmk_table', 1),
(16, '2025_01_01_000013_create_distribusi_semester_table', 1),
(17, '2025_01_01_000014_create_pivot_pl_cpl_table', 1),
(18, '2025_01_01_000015_create_pivot_cplsn_cplp_table', 1),
(19, '2025_01_01_000016_create_pivot_cpl_bk_table', 1),
(20, '2025_01_01_000017_create_pivot_mk_bk_table', 1),
(21, '2025_01_01_000018_create_pivot_cpl_cpmk_table', 1),
(22, '2025_01_01_000019_create_pivot_mk_cpl_table', 1),
(23, '2025_01_01_000020_create_pivot_cpl_bk_mk_table', 1),
(24, '2025_01_01_000021_create_pengampuan_mk_table', 1),
(25, '2025_01_01_000022_create_rps_header_table', 1),
(26, '2025_01_01_000023_create_rps_detail_mk_table', 1),
(27, '2025_01_01_000024_create_rps_pertemuan_table', 1),
(28, '2025_01_01_000025_create_jurnal_mengajar_table', 1),
(29, '2025_01_01_000026_create_repositori_materi_table', 1),
(30, '2025_01_01_000027_create_komponen_asesmen_table', 1),
(31, '2025_01_01_000028_create_enrollment_mk_table', 1),
(32, '2025_01_01_000029_create_nilai_mahasiswa_table', 1),
(33, '2025_01_01_000030_create_hasil_sub_cpmk_table', 1),
(34, '2025_01_01_000031_create_hasil_cpmk_table', 1),
(35, '2025_01_01_000032_create_hasil_cpl_table', 1),
(36, '2025_01_01_000033_create_hasil_pl_table', 1),
(37, '2025_01_01_000034_create_batas_ketercapaian_table', 1),
(38, '2025_01_01_000035_create_log_evaluasi_cqi_table', 1),
(39, '2025_01_01_000036_create_activity_log_table', 1),
(40, '2025_01_01_000037_add_foreign_keys_circular', 1),
(41, '2025_06_01_000001_add_missing_fields', 2),
(42, '2025_06_01_000002_create_missing_tables', 2),
(43, '2025_06_02_000001_create_master_kategori_table', 3),
(44, '2025_06_03_000001_add_konsentrasi_to_mata_kuliah', 4),
(45, '2026_05_31_161844_add_obe_fields_to_tables', 4),
(46, '2026_06_01_000001_create_cpmk_penilaian_table', 5),
(47, '2026_06_01_100001_add_skor_maks_to_cpmk_penilaian', 6),
(48, '2026_06_01_000001_extend_rps_tables', 7),
(49, '2026_06_07_000001_drop_removed_features', 8),
(50, '2026_06_07_000002_add_sks_to_rps_header', 9),
(51, '2026_06_07_043005_create_evaluasi_eksternal_table', 10),
(52, '2026_06_07_153026_make_kategori_mk_nullable_in_mata_kuliah', 11),
(53, '2026_06_08_004354_change_jenis_to_varchar_in_master_kategori', 12),
(54, '2026_06_13_000001_add_status_to_cpl_bk_tables', 13),
(55, '2026_06_15_000000_drop_log_evaluasi_cqi_and_evaluasi_eksternal_tables', 14),
(56, '2026_06_16_000000_add_kelas_to_users_table', 15),
(57, '2026_06_15_235139_simplify_arsip_rapat_table', 16),
(58, '2026_06_16_000330_drop_jenis_rapat_from_arsip_rapat', 17),
(59, '2026_06_19_163254_rename_arsip_rapat_fields', 18),
(60, '2026_06_20_180000_drop_nama_and_update_jenis_semester_akademik', 19),
(61, '2026_06_20_190000_seed_obe_master_kategori', 20);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_mahasiswa`
--

DROP TABLE IF EXISTS `nilai_mahasiswa`;
CREATE TABLE `nilai_mahasiswa` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `id_komponen` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `nilai_mentah` decimal(6,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
CREATE TABLE `notifikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `dibaca` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengampuan_mk`
--

DROP TABLE IF EXISTS `pengampuan_mk`;
CREATE TABLE `pengampuan_mk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_dosen` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `is_koordinator` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengampuan_mk`
--

INSERT INTO `pengampuan_mk` (`id`, `id_mk`, `id_dosen`, `id_semester`, `is_koordinator`, `created_at`) VALUES
(1, 6, 6, 2, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pivot_cplsn_cplp`
--

DROP TABLE IF EXISTS `pivot_cplsn_cplp`;
CREATE TABLE `pivot_cplsn_cplp` (
  `id_cpl_sndikti` bigint UNSIGNED NOT NULL,
  `id_cpl_prodi` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pivot_cplsn_cplp`
--

INSERT INTO `pivot_cplsn_cplp` (`id_cpl_sndikti`, `id_cpl_prodi`) VALUES
(37, 1),
(21, 2),
(38, 2),
(23, 3),
(24, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(40, 3),
(41, 3),
(48, 3),
(49, 3),
(50, 3),
(22, 4),
(39, 4),
(25, 5),
(42, 5),
(26, 6),
(27, 6),
(34, 6),
(43, 6),
(28, 7),
(44, 7),
(33, 8),
(36, 8),
(46, 8),
(47, 8),
(35, 9),
(45, 9),
(51, 9),
(52, 9),
(53, 9),
(1, 10),
(2, 10),
(3, 10),
(4, 10),
(6, 10),
(9, 10),
(54, 10),
(5, 11),
(7, 11),
(8, 11),
(10, 11),
(11, 12),
(12, 12),
(20, 12),
(13, 13),
(14, 13),
(15, 13),
(16, 13),
(19, 13),
(17, 14),
(18, 14);

-- --------------------------------------------------------

--
-- Table structure for table `pivot_cpl_bk`
--

DROP TABLE IF EXISTS `pivot_cpl_bk`;
CREATE TABLE `pivot_cpl_bk` (
  `id_cpl` bigint UNSIGNED NOT NULL,
  `id_bk` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pivot_cpl_bk`
--

INSERT INTO `pivot_cpl_bk` (`id_cpl`, `id_bk`) VALUES
(1, 1),
(2, 1),
(5, 1),
(7, 1),
(9, 1),
(2, 2),
(8, 2),
(4, 3),
(7, 4),
(3, 5),
(3, 7),
(6, 7),
(4, 8),
(5, 9),
(10, 9),
(11, 9),
(2, 10),
(7, 10),
(12, 10),
(13, 10),
(14, 10),
(2, 11),
(8, 12),
(8, 13),
(10, 13),
(11, 13),
(12, 13),
(13, 13),
(14, 13),
(5, 14),
(9, 14),
(4, 15),
(6, 15),
(3, 16),
(9, 17),
(8, 18),
(3, 19),
(3, 20),
(3, 21),
(3, 22);

-- --------------------------------------------------------

--
-- Table structure for table `pivot_cpl_bk_mk`
--

DROP TABLE IF EXISTS `pivot_cpl_bk_mk`;
CREATE TABLE `pivot_cpl_bk_mk` (
  `id_cpl` bigint UNSIGNED NOT NULL,
  `id_bk` bigint UNSIGNED NOT NULL,
  `id_mk` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pivot_cpl_bk_mk`
--

INSERT INTO `pivot_cpl_bk_mk` (`id_cpl`, `id_bk`, `id_mk`) VALUES
(1, 1, 6),
(1, 1, 8),
(2, 1, 6),
(2, 1, 8),
(5, 1, 6),
(5, 1, 8),
(7, 1, 6),
(7, 1, 8),
(9, 1, 6),
(9, 1, 8),
(2, 2, 13),
(2, 2, 26),
(2, 2, 34),
(8, 2, 13),
(8, 2, 26),
(8, 2, 34),
(4, 3, 8),
(4, 3, 15),
(4, 3, 29),
(4, 3, 50),
(4, 3, 53),
(7, 4, 25),
(7, 4, 36),
(7, 4, 46),
(3, 5, 21),
(3, 5, 22),
(3, 5, 36),
(3, 5, 46),
(4, 8, 42),
(5, 9, 3),
(5, 9, 4),
(5, 9, 42),
(10, 9, 3),
(10, 9, 4),
(10, 9, 42),
(11, 9, 3),
(11, 9, 4),
(11, 9, 42),
(2, 10, 42),
(7, 10, 42),
(12, 10, 42),
(13, 10, 42),
(14, 10, 42),
(8, 13, 3),
(8, 13, 4),
(8, 13, 5),
(8, 13, 43),
(10, 13, 3),
(10, 13, 4),
(10, 13, 5),
(10, 13, 43),
(11, 13, 3),
(11, 13, 4),
(11, 13, 5),
(11, 13, 43),
(12, 13, 3),
(12, 13, 4),
(12, 13, 5),
(12, 13, 43),
(13, 13, 3),
(13, 13, 4),
(13, 13, 5),
(13, 13, 43),
(14, 13, 3),
(14, 13, 4),
(14, 13, 5),
(14, 13, 43),
(5, 14, 43),
(9, 14, 43),
(4, 15, 43),
(6, 15, 43),
(3, 16, 6),
(8, 18, 44),
(3, 19, 44),
(3, 20, 44),
(3, 21, 44);

-- --------------------------------------------------------

--
-- Table structure for table `pivot_cpl_cpmk`
--

DROP TABLE IF EXISTS `pivot_cpl_cpmk`;
CREATE TABLE `pivot_cpl_cpmk` (
  `id_cpl` bigint UNSIGNED NOT NULL,
  `id_cpmk` bigint UNSIGNED NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT '1.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pivot_cpl_cpmk`
--

INSERT INTO `pivot_cpl_cpmk` (`id_cpl`, `id_cpmk`, `bobot`) VALUES
(1, 1, 1.00),
(2, 2, 1.00),
(5, 3, 1.00),
(6, 11, 1.00),
(8, 4, 1.00),
(8, 10, 1.00),
(9, 12, 1.00),
(9, 13, 1.00),
(10, 5, 1.00),
(11, 6, 1.00),
(13, 7, 1.00),
(13, 9, 1.00),
(14, 8, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `pivot_mk_bk`
--

DROP TABLE IF EXISTS `pivot_mk_bk`;
CREATE TABLE `pivot_mk_bk` (
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_bk` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pivot_mk_bk`
--

INSERT INTO `pivot_mk_bk` (`id_mk`, `id_bk`) VALUES
(6, 1),
(8, 1),
(13, 2),
(26, 2),
(34, 2),
(8, 3),
(15, 3),
(29, 3),
(50, 3),
(53, 3),
(25, 4),
(36, 4),
(46, 4),
(21, 5),
(22, 5),
(36, 5),
(46, 5),
(46, 6),
(42, 8),
(3, 9),
(4, 9),
(42, 9),
(42, 10),
(3, 13),
(4, 13),
(5, 13),
(43, 13),
(43, 14),
(43, 15),
(6, 16),
(44, 18),
(44, 19),
(44, 20),
(44, 21);

-- --------------------------------------------------------

--
-- Table structure for table `pivot_mk_cpl`
--

DROP TABLE IF EXISTS `pivot_mk_cpl`;
CREATE TABLE `pivot_mk_cpl` (
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_cpl` bigint UNSIGNED NOT NULL,
  `id_cpmk` bigint UNSIGNED NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT '1.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pivot_mk_cpl`
--

INSERT INTO `pivot_mk_cpl` (`id_mk`, `id_cpl`, `id_cpmk`, `bobot`) VALUES
(3, 1, 1, 1.00),
(3, 5, 3, 1.00),
(3, 8, 4, 1.00),
(3, 10, 5, 1.00),
(4, 2, 2, 1.00),
(4, 11, 6, 1.00),
(5, 14, 8, 1.00),
(7, 13, 9, 1.00),
(9, 8, 10, 1.00),
(19, 6, 11, 1.00),
(21, 9, 12, 1.00),
(22, 9, 13, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `pivot_pl_cpl`
--

DROP TABLE IF EXISTS `pivot_pl_cpl`;
CREATE TABLE `pivot_pl_cpl` (
  `id_pl` bigint UNSIGNED NOT NULL,
  `id_cpl` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pivot_pl_cpl`
--

INSERT INTO `pivot_pl_cpl` (`id_pl`, `id_cpl`) VALUES
(1, 1),
(2, 1),
(3, 1),
(5, 1),
(1, 2),
(3, 2),
(5, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(2, 6),
(1, 7),
(3, 8),
(2, 9),
(4, 10),
(4, 11),
(5, 12),
(5, 13),
(5, 14);

-- --------------------------------------------------------

--
-- Table structure for table `pl`
--

DROP TABLE IF EXISTS `pl`;
CREATE TABLE `pl` (
  `id` bigint UNSIGNED NOT NULL,
  `id_kurikulum` bigint UNSIGNED NOT NULL,
  `kode_pl` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi` text COLLATE utf8mb4_unicode_ci,
  `ref_area_fungsi_1` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_area_fungsi_2` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_area_fungsi_3` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pl`
--

INSERT INTO `pl` (`id`, `id_kurikulum`, `kode_pl`, `deskripsi`, `kategori`, `referensi`, `ref_area_fungsi_1`, `ref_area_fungsi_2`, `ref_area_fungsi_3`, `urutan`, `status`, `approved_by`, `approved_at`, `deleted_at`) VALUES
(1, 1, 'PL01', 'lulusan memiliki kemampuan untuk merencanakan, menganalisis, merancang, membangun, mengujicoba, menerapkan, dan mengevaluasi sistem informasi (bidang x) dalam sebuah proyek yang selaras dengan tujuan organisasi (peta okupasi dalam KKNI Bidang TIK dan IS2020)', 'Penciri Utama', NULL, NULL, NULL, NULL, 1, 'draft', NULL, NULL, NULL),
(2, 1, 'PL02', 'lulusan memiliki kemampuan memahami, menerapkan, dan mengintegrasikan model bisnis dengan menggunakan metode dan berbagai teknik peningkatan bisnis proses yang mendatangkan suatu nilai tambah bagi organisasi (peta okupasi dalam KKNI Bidang TIK dan IS2020)', 'Penciri Utama', 'peta okupasi dalam KKNI Bidang TIK dan IS2020', NULL, NULL, NULL, 2, 'draft', NULL, NULL, NULL),
(3, 1, 'PL03', 'Lulusan memiliki kemampuan untuk mengolah, menganalisis, dan menyajikan data yang dikembangkan dengan konsep big data dan business intelligence untuk membantu dalam proses pengambilan keputusan (peta okupasi dalam KKNI Bidang TIK)', 'Ketrampilan Khusus', 'peta okupasi dalam KKNI Bidang TIK', NULL, NULL, NULL, 3, 'draft', NULL, NULL, NULL),
(4, 1, 'PL04', 'Lulusan memiliki sikap religius, beretika, dan peka terhadap lingkungan sosial sebagai seorang warga negara dengan berlandaskan nilai ahlussunah waljamaah (Aswaja).', 'Sikap', NULL, NULL, NULL, NULL, 4, 'draft', NULL, NULL, NULL),
(5, 1, 'PL05', 'Lulusan memiliki kemampuan berpikir kritis dan inovatif, bekerja mandiri, membuat keputusan tepat, mendokumentasikan data dengan benar, menyusun karya ilmiah, berkomunikasi efektif, membangun jaringan kerja, bertanggung jawab atas hasil tim, dan mengelola pembelajaran secara mandiri sesuai bidang keahliannya', 'Ketrampilan Umum', NULL, NULL, NULL, NULL, 5, 'draft', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `repositori_materi`
--

DROP TABLE IF EXISTS `repositori_materi`;
CREATE TABLE `repositori_materi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `id_dosen` bigint UNSIGNED NOT NULL,
  `id_rps_pertemuan` bigint UNSIGNED DEFAULT NULL,
  `jenis_file` enum('modul','presentasi','referensi','tugas','video','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ukuran_kb` int DEFAULT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rps_detail_mk`
--

DROP TABLE IF EXISTS `rps_detail_mk`;
CREATE TABLE `rps_detail_mk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_rps` bigint UNSIGNED NOT NULL,
  `id_cpl` bigint UNSIGNED NOT NULL,
  `id_cpmk` bigint UNSIGNED NOT NULL,
  `id_sub_cpmk` bigint UNSIGNED DEFAULT NULL,
  `urutan` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rps_header`
--

DROP TABLE IF EXISTS `rps_header`;
CREATE TABLE `rps_header` (
  `id` bigint UNSIGNED NOT NULL,
  `id_mk` bigint UNSIGNED NOT NULL,
  `id_semester` bigint UNSIGNED NOT NULL,
  `id_dosen_pengembang` bigint UNSIGNED NOT NULL,
  `id_koordinator_bk` bigint UNSIGNED DEFAULT NULL,
  `id_kaprodi_pengesah` bigint UNSIGNED DEFAULT NULL,
  `tanggal_penyusunan` date NOT NULL,
  `kode_dokumen` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sks_teori` tinyint UNSIGNED DEFAULT NULL,
  `sks_praktikum` tinyint UNSIGNED DEFAULT NULL,
  `nama_perguruan_tinggi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_fakultas` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_mk` text COLLATE utf8mb4_unicode_ci,
  `materi_pembelajaran` text COLLATE utf8mb4_unicode_ci,
  `pustaka_utama` text COLLATE utf8mb4_unicode_ci,
  `pustaka_pendukung` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','review','disahkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `disahkan_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rps_header`
--

INSERT INTO `rps_header` (`id`, `id_mk`, `id_semester`, `id_dosen_pengembang`, `id_koordinator_bk`, `id_kaprodi_pengesah`, `tanggal_penyusunan`, `kode_dokumen`, `sks_teori`, `sks_praktikum`, `nama_perguruan_tinggi`, `nama_fakultas`, `deskripsi_mk`, `materi_pembelajaran`, `pustaka_utama`, `pustaka_pendukung`, `status`, `disahkan_pada`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 6, NULL, NULL, '2026-06-20', 'RPS/MK05/GNP-2626', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', NULL, '2026-06-20 06:44:31', '2026-06-20 06:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `rps_pertemuan`
--

DROP TABLE IF EXISTS `rps_pertemuan`;
CREATE TABLE `rps_pertemuan` (
  `id` bigint UNSIGNED NOT NULL,
  `id_rps` bigint UNSIGNED NOT NULL,
  `minggu_ke` int NOT NULL,
  `materi_pembelajaran` text COLLATE utf8mb4_unicode_ci,
  `metode_pembelajaran` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_sub_cpmk` bigint UNSIGNED DEFAULT NULL,
  `indikator_penilaian` text COLLATE utf8mb4_unicode_ci,
  `kriteria_teknik` text COLLATE utf8mb4_unicode_ci,
  `bentuk_luring` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bentuk_daring` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bobot_penilaian` decimal(5,2) DEFAULT NULL,
  `estimasi_waktu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_pembelajaran` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semester_akademik`
--

DROP TABLE IF EXISTS `semester_akademik`;
CREATE TABLE `semester_akademik` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun_akademik` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('Gasal','Genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_aktif` tinyint NOT NULL DEFAULT '0',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semester_akademik`
--

INSERT INTO `semester_akademik` (`id`, `tahun_akademik`, `jenis`, `is_aktif`, `tanggal_mulai`, `tanggal_selesai`, `created_at`) VALUES
(2, '2026', 'Genap', 1, '2026-01-20', '2026-07-20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Atm85LbdWbjgVkCpcQUduNuxRarggi6RTAZLK4f0', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJblZEWmtnMGVVODRaR1Z2SzNkaFVqZzRObVJzYzJjOVBTSXNJblpoYkhWbElqb2lSalo2YjBoUFRtNHJVVXhsVW1oRWNtUkhNV05SVVUxQ1RVdE5iRmRZVFN0RlFVaHViMEpwWldreGFYVXlSelZUYTI5VVZHRlRielZETm1oVWNTdFBSVE55VTJ0SldrNXhRVFpUZFhnMVZUaFBTREZGVjJ0V01XTmhjMUkyYkZFMVZYZFFWM00wTTBscWVFTjRWekJKYkZoa2NESk9Sek5rTDB3dlVGTktOMk0yVkVkRmFsaERPV1Y0V1dFd1NYQnJiVEl5TjNWNVEyOXBLMWMxT1dOcWNIZHlUVTlYYW5rMWVUWk1OR3RZSzA0Mk9GTnBRM1JETW1OdGRrRm5XVEpCWlRkQ1RVUldXRUpvUmpoT2NESk9WVUZtYWpJMmVVdEVLelIwZUZwNFZXcFhWVWhKVEU1VVdUaHNWVFk0TUhkWWNIUlhNMjVLZVZFemNVUTVSMUJWU1ROcWIzQnhPWEZIYmpadU0yMUJZVUZvT0VKT1lVOUlkSGhHTlhNeGVXTkVXVFY0ZFhGR01qRnZielZYTjJ4U2FUWnhOekZpV1VRNE1ERmpiVXBOYzJ0YVIxUXlhbTVEYmxWNmRFODRlRGM0U1dSRldVUnJTMEl4VDNnck9UUldaSFZtZFV4MVNrNXBSUzl6Vmt4amNHSk1OVFJwTDJaR1pqTkhlVVZKZGtKVUlpd2liV0ZqSWpvaVl6YzJZV0prT0RsaE9HVXdOakV4TjJFeE9XRmtNelJoTlRBMU9HWmtZMlV3WlRGaE5EUm1Oek5oT0dWaVpEVXdaVFUwTW1KbVlURTNZVFUxTnpsak1pSXNJblJoWnlJNklpSjk=', 1781963671);

-- --------------------------------------------------------

--
-- Table structure for table `sub_cpmk`
--

DROP TABLE IF EXISTS `sub_cpmk`;
CREATE TABLE `sub_cpmk` (
  `id` bigint UNSIGNED NOT NULL,
  `id_cpmk` bigint UNSIGNED NOT NULL,
  `kode_sub_cpmk` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT '0.00',
  `urutan` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_cpmk`
--

INSERT INTO `sub_cpmk` (`id`, `id_cpmk`, `kode_sub_cpmk`, `deskripsi`, `bobot`, `urutan`) VALUES
(1, 1, 'SUB-MK02-1.1', 'Memahami konsep dasar AGAMA.', 50.00, 1),
(2, 1, 'SUB-MK02-1.2', 'Menerapkan AGAMA dalam studi kasus.', 50.00, 2),
(3, 2, 'SUB-MK03-1.1', 'Memahami konsep dasar PANCASILA.', 50.00, 1),
(4, 2, 'SUB-MK03-1.2', 'Menerapkan PANCASILA dalam studi kasus.', 50.00, 2),
(5, 3, 'SUB-MK02-2.1', 'Memahami konsep dasar AGAMA.', 50.00, 1),
(6, 3, 'SUB-MK02-2.2', 'Menerapkan AGAMA dalam studi kasus.', 50.00, 2),
(7, 4, 'SUB-MK02-3.1', 'Memahami konsep dasar AGAMA.', 50.00, 1),
(8, 4, 'SUB-MK02-3.2', 'Menerapkan AGAMA dalam studi kasus.', 50.00, 2),
(9, 5, 'SUB-MK02-4.1', 'Memahami konsep dasar AGAMA.', 50.00, 1),
(10, 5, 'SUB-MK02-4.2', 'Menerapkan AGAMA dalam studi kasus.', 50.00, 2),
(11, 6, 'SUB-MK03-2.1', 'Memahami konsep dasar PANCASILA.', 50.00, 1),
(12, 6, 'SUB-MK03-2.2', 'Menerapkan PANCASILA dalam studi kasus.', 50.00, 2),
(13, 7, 'SUB-MK04-1.1', 'Memahami konsep dasar BAHASA INDONESIA.', 50.00, 1),
(14, 7, 'SUB-MK04-1.2', 'Menerapkan BAHASA INDONESIA dalam studi kasus.', 50.00, 2),
(15, 8, 'SUB-MK04-2.1', 'Memahami konsep dasar BAHASA INDONESIA.', 50.00, 1),
(16, 8, 'SUB-MK04-2.2', 'Menerapkan BAHASA INDONESIA dalam studi kasus.', 50.00, 2),
(17, 9, 'SUB-MK06-1.1', 'Memahami konsep dasar ALGORITMA DAN PEMROGRAMAN.', 50.00, 1),
(18, 9, 'SUB-MK06-1.2', 'Menerapkan ALGORITMA DAN PEMROGRAMAN dalam studi kasus.', 50.00, 2),
(19, 10, 'SUB-MK08-1.1', 'Memahami konsep dasar MATEMATIKA DISKRIT.', 50.00, 1),
(20, 10, 'SUB-MK08-1.2', 'Menerapkan MATEMATIKA DISKRIT dalam studi kasus.', 50.00, 2),
(21, 11, 'SUB-MK18-1.1', 'Memahami konsep dasar PENGANTAR AKUNTANSI.', 50.00, 1),
(22, 11, 'SUB-MK18-1.2', 'Menerapkan PENGANTAR AKUNTANSI dalam studi kasus.', 50.00, 2),
(23, 12, 'SUB-MK20-1.1', 'Memahami konsep dasar ANALISIS DAN PERANCANGAN SISTEM.', 50.00, 1),
(24, 12, 'SUB-MK20-1.2', 'Menerapkan ANALISIS DAN PERANCANGAN SISTEM dalam studi kasus.', 50.00, 2),
(25, 13, 'SUB-MK21-1.1', 'Memahami konsep dasar DESAIN UI/UX.', 50.00, 1),
(26, 13, 'SUB-MK21-1.2', 'Menerapkan DESAIN UI/UX dalam studi kasus.', 50.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('kaprodi','tim_kurikulum','dosen','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_studi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fakultas` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perguruan_tinggi` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_masuk` year DEFAULT NULL,
  `kelas` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kurikulum` bigint UNSIGNED DEFAULT NULL,
  `foto` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` enum('aktif','nonaktif','cuti') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `identifier`, `program_studi`, `fakultas`, `perguruan_tinggi`, `tahun_masuk`, `kelas`, `id_kurikulum`, `foto`, `status_aktif`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Dr. Ahmad Kaprodi, M.Kom', 'kaprodi@si-obe.id', NULL, '$2y$12$j9ewlQkvcE05Dk2iK143e.8wOTfF0.5FM2aVm3pQDZpuLjga/xtou', 'kaprodi', '197001012000011001', 'Sistem Informasi', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', '2026-06-20 06:42:35', NULL, '2026-05-25 12:46:36', '2026-06-20 06:42:35', NULL),
(5, 'Siti Kurikulum, M.T', 'kurikulum@si-obe.id', NULL, '$2y$12$clzpNPNTr1Cgm6SXNtAYtOTOahMbiT50USUdVVdO3EDDfM2VW2Y.W', 'tim_kurikulum', '198001012005012001', 'Sistem Informasi', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', '2026-06-20 06:53:51', NULL, '2026-05-25 12:46:37', '2026-06-20 06:53:51', NULL),
(6, 'Dr. Budi Santoso, M.Kom', 'dosen1@si-obe.id', NULL, '$2y$12$1P5si8B8JBNG5ySGKc2peOnIVr8Whf77BXdxSNlSR7uiMEGs4kCMq', 'dosen', '198505152010011005', 'Sistem Informasi', NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', '2026-06-20 06:43:53', 'hkP2Ql7uN07Qn0E6DmTWgrLuEzxtVz99hyDH4CcPO7FKdCk7fAm4BvYMeL04', '2026-05-25 12:46:37', '2026-06-20 06:43:53', NULL),
(8, 'Andi Pratama', 'mahasiswa1@si-obe.id', NULL, '$2y$12$x1zaY1cGXzSJVAiTIsG91ue1D5kSaLBs94DblEeavDpb1t6HHXJBG', 'mahasiswa', '21523001', 'Sistem Informasi', NULL, NULL, '2021', NULL, NULL, NULL, 'aktif', '2026-06-19 05:18:18', NULL, '2026-05-25 12:46:37', '2026-06-19 05:18:18', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_al_user` (`user_id`),
  ADD KEY `idx_al_model` (`model_type`,`model_id`),
  ADD KEY `idx_al_created` (`created_at`);

--
-- Indexes for table `arsip_rapat`
--
ALTER TABLE `arsip_rapat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arsip_rapat_dibuat_oleh_foreign` (`dibuat_oleh`),
  ADD KEY `idx_rapat_kur` (`id_kurikulum`,`tanggal`);

--
-- Indexes for table `bahan_kajian`
--
ALTER TABLE `bahan_kajian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_bk_kode` (`id_kurikulum`,`kode_bk`),
  ADD KEY `bahan_kajian_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `batas_ketercapaian`
--
ALTER TABLE `batas_ketercapaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_batas` (`id_cpl`,`id_kurikulum`),
  ADD KEY `batas_ketercapaian_id_kurikulum_foreign` (`id_kurikulum`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `cpl_prodi`
--
ALTER TABLE `cpl_prodi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cplp_kode` (`id_kurikulum`,`kode_cpl`),
  ADD KEY `cpl_prodi_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `cpl_sndikti`
--
ALTER TABLE `cpl_sndikti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cplsn_kode` (`kode`),
  ADD KEY `cpl_sndikti_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `cpmk`
--
ALTER TABLE `cpmk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cpmk_kode_mk` (`id_mk`,`kode_cpmk`),
  ADD KEY `cpmk_id_kurikulum_foreign` (`id_kurikulum`),
  ADD KEY `idx_cpmk_cpl` (`id_cpl`);

--
-- Indexes for table `cpmk_penilaian`
--
ALTER TABLE `cpmk_penilaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cpmk_penilaian` (`id_cpmk`);

--
-- Indexes for table `distribusi_semester`
--
ALTER TABLE `distribusi_semester`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_dist_kur_smt` (`id_kurikulum`,`semester`);

--
-- Indexes for table `enrollment_mk`
--
ALTER TABLE `enrollment_mk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_enroll` (`id_mahasiswa`,`id_mk`,`id_semester`),
  ADD KEY `enrollment_mk_id_mk_foreign` (`id_mk`),
  ADD KEY `enrollment_mk_id_semester_foreign` (`id_semester`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `hasil_cpl`
--
ALTER TABLE `hasil_cpl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_hk_cpl` (`id_mahasiswa`,`id_cpl`,`id_semester`),
  ADD KEY `hasil_cpl_id_cpl_foreign` (`id_cpl`),
  ADD KEY `hasil_cpl_id_semester_foreign` (`id_semester`),
  ADD KEY `idx_hk_cpl_kur` (`id_kurikulum`,`id_cpl`,`id_semester`);

--
-- Indexes for table `hasil_cpmk`
--
ALTER TABLE `hasil_cpmk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_hk_cpmk` (`id_mahasiswa`,`id_cpmk`,`id_semester`),
  ADD KEY `hasil_cpmk_id_cpmk_foreign` (`id_cpmk`),
  ADD KEY `hasil_cpmk_id_semester_foreign` (`id_semester`);

--
-- Indexes for table `hasil_pl`
--
ALTER TABLE `hasil_pl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_hk_pl` (`id_mahasiswa`,`id_pl`,`id_semester`),
  ADD KEY `hasil_pl_id_pl_foreign` (`id_pl`),
  ADD KEY `hasil_pl_id_kurikulum_foreign` (`id_kurikulum`),
  ADD KEY `hasil_pl_id_semester_foreign` (`id_semester`);

--
-- Indexes for table `hasil_sub_cpmk`
--
ALTER TABLE `hasil_sub_cpmk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_hk_sc` (`id_mahasiswa`,`id_sub_cpmk`,`id_semester`),
  ADD KEY `hasil_sub_cpmk_id_sub_cpmk_foreign` (`id_sub_cpmk`),
  ADD KEY `hasil_sub_cpmk_id_semester_foreign` (`id_semester`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_mengajar_id_rps_pertemuan_foreign` (`id_rps_pertemuan`),
  ADD KEY `jurnal_mengajar_id_dosen_foreign` (`id_dosen`);

--
-- Indexes for table `komentar_review`
--
ALTER TABLE `komentar_review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `komentar_review_id_user_foreign` (`id_user`),
  ADD KEY `komentar_review_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `komponen_asesmen`
--
ALTER TABLE `komponen_asesmen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `komponen_asesmen_id_semester_foreign` (`id_semester`),
  ADD KEY `komponen_asesmen_id_sub_cpmk_foreign` (`id_sub_cpmk`),
  ADD KEY `idx_komponen_mk_smt` (`id_mk`,`id_semester`);

--
-- Indexes for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kurikulum_kode` (`kode`),
  ADD KEY `idx_kur_status` (`status`),
  ADD KEY `fk_kur_locked` (`locked_by`),
  ADD KEY `fk_kur_dibuat` (`dibuat_oleh`),
  ADD KEY `fk_kur_disahkan` (`disahkan_oleh`);

--
-- Indexes for table `master_kategori`
--
ALTER TABLE `master_kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_kategori_jenis_nama_unique` (`jenis`,`nama`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_mk_kode` (`id_kurikulum`,`kode_mk`),
  ADD KEY `idx_mk_semester` (`id_kurikulum`,`semester`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nilai` (`id_mahasiswa`,`id_komponen`,`id_semester`),
  ADD KEY `nilai_mahasiswa_id_semester_foreign` (`id_semester`),
  ADD KEY `idx_nilai_mhs_smt` (`id_mahasiswa`,`id_semester`),
  ADD KEY `idx_nilai_komponen` (`id_komponen`,`id_semester`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasi_id_user_foreign` (`id_user`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengampuan_mk`
--
ALTER TABLE `pengampuan_mk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pengampuan` (`id_mk`,`id_dosen`,`id_semester`),
  ADD KEY `pengampuan_mk_id_semester_foreign` (`id_semester`),
  ADD KEY `idx_pengampuan_dosen` (`id_dosen`,`id_semester`);

--
-- Indexes for table `pivot_cplsn_cplp`
--
ALTER TABLE `pivot_cplsn_cplp`
  ADD PRIMARY KEY (`id_cpl_sndikti`,`id_cpl_prodi`),
  ADD KEY `pivot_cplsn_cplp_id_cpl_prodi_foreign` (`id_cpl_prodi`);

--
-- Indexes for table `pivot_cpl_bk`
--
ALTER TABLE `pivot_cpl_bk`
  ADD PRIMARY KEY (`id_cpl`,`id_bk`),
  ADD KEY `pivot_cpl_bk_id_bk_foreign` (`id_bk`);

--
-- Indexes for table `pivot_cpl_bk_mk`
--
ALTER TABLE `pivot_cpl_bk_mk`
  ADD PRIMARY KEY (`id_cpl`,`id_bk`,`id_mk`),
  ADD KEY `pivot_cpl_bk_mk_id_bk_foreign` (`id_bk`),
  ADD KEY `pivot_cpl_bk_mk_id_mk_foreign` (`id_mk`);

--
-- Indexes for table `pivot_cpl_cpmk`
--
ALTER TABLE `pivot_cpl_cpmk`
  ADD PRIMARY KEY (`id_cpl`,`id_cpmk`),
  ADD KEY `pivot_cpl_cpmk_id_cpmk_foreign` (`id_cpmk`);

--
-- Indexes for table `pivot_mk_bk`
--
ALTER TABLE `pivot_mk_bk`
  ADD PRIMARY KEY (`id_mk`,`id_bk`),
  ADD KEY `pivot_mk_bk_id_bk_foreign` (`id_bk`);

--
-- Indexes for table `pivot_mk_cpl`
--
ALTER TABLE `pivot_mk_cpl`
  ADD PRIMARY KEY (`id_mk`,`id_cpl`,`id_cpmk`),
  ADD KEY `pivot_mk_cpl_id_cpl_foreign` (`id_cpl`),
  ADD KEY `pivot_mk_cpl_id_cpmk_foreign` (`id_cpmk`);

--
-- Indexes for table `pivot_pl_cpl`
--
ALTER TABLE `pivot_pl_cpl`
  ADD PRIMARY KEY (`id_pl`,`id_cpl`),
  ADD KEY `pivot_pl_cpl_id_cpl_foreign` (`id_cpl`);

--
-- Indexes for table `pl`
--
ALTER TABLE `pl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pl_kode` (`id_kurikulum`,`kode_pl`),
  ADD KEY `pl_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `repositori_materi`
--
ALTER TABLE `repositori_materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repositori_materi_id_mk_foreign` (`id_mk`),
  ADD KEY `repositori_materi_id_semester_foreign` (`id_semester`),
  ADD KEY `repositori_materi_id_rps_pertemuan_foreign` (`id_rps_pertemuan`),
  ADD KEY `repositori_materi_id_dosen_foreign` (`id_dosen`);

--
-- Indexes for table `rps_detail_mk`
--
ALTER TABLE `rps_detail_mk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rps_detail_mk_id_rps_foreign` (`id_rps`),
  ADD KEY `rps_detail_mk_id_cpl_foreign` (`id_cpl`),
  ADD KEY `rps_detail_mk_id_cpmk_foreign` (`id_cpmk`),
  ADD KEY `rps_detail_mk_id_sub_cpmk_foreign` (`id_sub_cpmk`);

--
-- Indexes for table `rps_header`
--
ALTER TABLE `rps_header`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_rps_mk_smt` (`id_mk`,`id_semester`),
  ADD KEY `rps_header_id_semester_foreign` (`id_semester`),
  ADD KEY `rps_header_id_dosen_pengembang_foreign` (`id_dosen_pengembang`),
  ADD KEY `rps_header_id_koordinator_bk_foreign` (`id_koordinator_bk`),
  ADD KEY `rps_header_id_kaprodi_pengesah_foreign` (`id_kaprodi_pengesah`);

--
-- Indexes for table `rps_pertemuan`
--
ALTER TABLE `rps_pertemuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_rps_minggu` (`id_rps`,`minggu_ke`),
  ADD KEY `idx_rps_pertemuan_id_rps` (`id_rps`),
  ADD KEY `rps_pertemuan_id_sub_cpmk_foreign` (`id_sub_cpmk`);

--
-- Indexes for table `semester_akademik`
--
ALTER TABLE `semester_akademik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_semester_thn_jenis` (`tahun_akademik`,`jenis`),
  ADD KEY `idx_semester_aktif` (`is_aktif`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_cpmk`
--
ALTER TABLE `sub_cpmk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_subcpmk_kode` (`id_cpmk`,`kode_sub_cpmk`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_identifier` (`identifier`),
  ADD KEY `idx_users_angkatan` (`tahun_masuk`,`role`),
  ADD KEY `fk_users_kurikulum` (`id_kurikulum`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `arsip_rapat`
--
ALTER TABLE `arsip_rapat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bahan_kajian`
--
ALTER TABLE `bahan_kajian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `batas_ketercapaian`
--
ALTER TABLE `batas_ketercapaian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cpl_prodi`
--
ALTER TABLE `cpl_prodi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cpl_sndikti`
--
ALTER TABLE `cpl_sndikti`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `cpmk`
--
ALTER TABLE `cpmk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cpmk_penilaian`
--
ALTER TABLE `cpmk_penilaian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `distribusi_semester`
--
ALTER TABLE `distribusi_semester`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `enrollment_mk`
--
ALTER TABLE `enrollment_mk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_cpl`
--
ALTER TABLE `hasil_cpl`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_cpmk`
--
ALTER TABLE `hasil_cpmk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_pl`
--
ALTER TABLE `hasil_pl`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_sub_cpmk`
--
ALTER TABLE `hasil_sub_cpmk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komentar_review`
--
ALTER TABLE `komentar_review`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komponen_asesmen`
--
ALTER TABLE `komponen_asesmen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kurikulum`
--
ALTER TABLE `kurikulum`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master_kategori`
--
ALTER TABLE `master_kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengampuan_mk`
--
ALTER TABLE `pengampuan_mk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pl`
--
ALTER TABLE `pl`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `repositori_materi`
--
ALTER TABLE `repositori_materi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rps_detail_mk`
--
ALTER TABLE `rps_detail_mk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rps_header`
--
ALTER TABLE `rps_header`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rps_pertemuan`
--
ALTER TABLE `rps_pertemuan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semester_akademik`
--
ALTER TABLE `semester_akademik`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sub_cpmk`
--
ALTER TABLE `sub_cpmk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `arsip_rapat`
--
ALTER TABLE `arsip_rapat`
  ADD CONSTRAINT `arsip_rapat_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `arsip_rapat_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bahan_kajian`
--
ALTER TABLE `bahan_kajian`
  ADD CONSTRAINT `bahan_kajian_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bahan_kajian_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `batas_ketercapaian`
--
ALTER TABLE `batas_ketercapaian`
  ADD CONSTRAINT `batas_ketercapaian_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `batas_ketercapaian_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cpl_prodi`
--
ALTER TABLE `cpl_prodi`
  ADD CONSTRAINT `cpl_prodi_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cpl_prodi_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cpl_sndikti`
--
ALTER TABLE `cpl_sndikti`
  ADD CONSTRAINT `cpl_sndikti_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cpmk`
--
ALTER TABLE `cpmk`
  ADD CONSTRAINT `cpmk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cpmk_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cpmk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cpmk_penilaian`
--
ALTER TABLE `cpmk_penilaian`
  ADD CONSTRAINT `cpmk_penilaian_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `distribusi_semester`
--
ALTER TABLE `distribusi_semester`
  ADD CONSTRAINT `distribusi_semester_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollment_mk`
--
ALTER TABLE `enrollment_mk`
  ADD CONSTRAINT `enrollment_mk_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_mk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_mk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hasil_cpl`
--
ALTER TABLE `hasil_cpl`
  ADD CONSTRAINT `hasil_cpl_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_cpl_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_cpl_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_cpl_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hasil_cpmk`
--
ALTER TABLE `hasil_cpmk`
  ADD CONSTRAINT `hasil_cpmk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_cpmk_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_cpmk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hasil_pl`
--
ALTER TABLE `hasil_pl`
  ADD CONSTRAINT `hasil_pl_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_pl_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_pl_id_pl_foreign` FOREIGN KEY (`id_pl`) REFERENCES `pl` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_pl_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hasil_sub_cpmk`
--
ALTER TABLE `hasil_sub_cpmk`
  ADD CONSTRAINT `hasil_sub_cpmk_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_sub_cpmk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_sub_cpmk_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  ADD CONSTRAINT `jurnal_mengajar_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jurnal_mengajar_id_rps_pertemuan_foreign` FOREIGN KEY (`id_rps_pertemuan`) REFERENCES `rps_pertemuan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `komentar_review`
--
ALTER TABLE `komentar_review`
  ADD CONSTRAINT `komentar_review_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `komponen_asesmen`
--
ALTER TABLE `komponen_asesmen`
  ADD CONSTRAINT `komponen_asesmen_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `komponen_asesmen_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `komponen_asesmen_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD CONSTRAINT `fk_kur_dibuat` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_kur_disahkan` FOREIGN KEY (`disahkan_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_kur_locked` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  ADD CONSTRAINT `nilai_mahasiswa_id_komponen_foreign` FOREIGN KEY (`id_komponen`) REFERENCES `komponen_asesmen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_mahasiswa_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_mahasiswa_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengampuan_mk`
--
ALTER TABLE `pengampuan_mk`
  ADD CONSTRAINT `pengampuan_mk_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengampuan_mk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengampuan_mk_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pivot_cplsn_cplp`
--
ALTER TABLE `pivot_cplsn_cplp`
  ADD CONSTRAINT `pivot_cplsn_cplp_id_cpl_prodi_foreign` FOREIGN KEY (`id_cpl_prodi`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_cplsn_cplp_id_cpl_sndikti_foreign` FOREIGN KEY (`id_cpl_sndikti`) REFERENCES `cpl_sndikti` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pivot_cpl_bk`
--
ALTER TABLE `pivot_cpl_bk`
  ADD CONSTRAINT `pivot_cpl_bk_id_bk_foreign` FOREIGN KEY (`id_bk`) REFERENCES `bahan_kajian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_cpl_bk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pivot_cpl_bk_mk`
--
ALTER TABLE `pivot_cpl_bk_mk`
  ADD CONSTRAINT `pivot_cpl_bk_mk_id_bk_foreign` FOREIGN KEY (`id_bk`) REFERENCES `bahan_kajian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_cpl_bk_mk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_cpl_bk_mk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pivot_cpl_cpmk`
--
ALTER TABLE `pivot_cpl_cpmk`
  ADD CONSTRAINT `pivot_cpl_cpmk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_cpl_cpmk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pivot_mk_bk`
--
ALTER TABLE `pivot_mk_bk`
  ADD CONSTRAINT `pivot_mk_bk_id_bk_foreign` FOREIGN KEY (`id_bk`) REFERENCES `bahan_kajian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_mk_bk_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pivot_mk_cpl`
--
ALTER TABLE `pivot_mk_cpl`
  ADD CONSTRAINT `pivot_mk_cpl_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_mk_cpl_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_mk_cpl_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pivot_pl_cpl`
--
ALTER TABLE `pivot_pl_cpl`
  ADD CONSTRAINT `pivot_pl_cpl_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pivot_pl_cpl_id_pl_foreign` FOREIGN KEY (`id_pl`) REFERENCES `pl` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pl`
--
ALTER TABLE `pl`
  ADD CONSTRAINT `pl_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pl_id_kurikulum_foreign` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repositori_materi`
--
ALTER TABLE `repositori_materi`
  ADD CONSTRAINT `repositori_materi_id_dosen_foreign` FOREIGN KEY (`id_dosen`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `repositori_materi_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `repositori_materi_id_rps_pertemuan_foreign` FOREIGN KEY (`id_rps_pertemuan`) REFERENCES `rps_pertemuan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `repositori_materi_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rps_detail_mk`
--
ALTER TABLE `rps_detail_mk`
  ADD CONSTRAINT `rps_detail_mk_id_cpl_foreign` FOREIGN KEY (`id_cpl`) REFERENCES `cpl_prodi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rps_detail_mk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rps_detail_mk_id_rps_foreign` FOREIGN KEY (`id_rps`) REFERENCES `rps_header` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rps_detail_mk_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rps_header`
--
ALTER TABLE `rps_header`
  ADD CONSTRAINT `rps_header_id_dosen_pengembang_foreign` FOREIGN KEY (`id_dosen_pengembang`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rps_header_id_kaprodi_pengesah_foreign` FOREIGN KEY (`id_kaprodi_pengesah`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rps_header_id_koordinator_bk_foreign` FOREIGN KEY (`id_koordinator_bk`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rps_header_id_mk_foreign` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rps_header_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rps_pertemuan`
--
ALTER TABLE `rps_pertemuan`
  ADD CONSTRAINT `rps_pertemuan_id_rps_foreign` FOREIGN KEY (`id_rps`) REFERENCES `rps_header` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rps_pertemuan_id_sub_cpmk_foreign` FOREIGN KEY (`id_sub_cpmk`) REFERENCES `sub_cpmk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sub_cpmk`
--
ALTER TABLE `sub_cpmk`
  ADD CONSTRAINT `sub_cpmk_id_cpmk_foreign` FOREIGN KEY (`id_cpmk`) REFERENCES `cpmk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_kurikulum` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
