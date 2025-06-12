<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Lowongan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa ID perusahaan yang sudah ada untuk dikaitkan dengan lowongan
        // Pastikan CompanySeeder sudah dijalankan sebelumnya
        $company1 = Company::where('nama_perusahaan', 'Payfazz')->first();
        $company2 = Company::where('nama_perusahaan', 'Astra International')->first();
        $company3 = Company::where('nama_perusahaan', 'Mayora Indah Tbk')->first();
        $company4 = Company::where('nama_perusahaan', 'PT. ABC Jaya')->first(); // Pastikan perusahaan ini ada atau dibuat di CompanySeeder
        $company5 = Company::where('nama_perusahaan', 'Telkomsel')->first(); // Add more companies if needed for diversity
        $company6 = Company::where('nama_perusahaan', 'GoJek')->first();

        // Ensure these companies exist or create them if you prefer to test with known IDs
        // For testing, you might just use first() or create fake companies if needed.
        if (!$company1) $company1 = Company::factory()->create(['nama_perusahaan' => 'Payfazz', 'status_kerjasama' => 'Aktif']);
        if (!$company2) $company2 = Company::factory()->create(['nama_perusahaan' => 'Astra International', 'status_kerjasama' => 'Aktif']);
        if (!$company3) $company3 = Company::factory()->create(['nama_perusahaan' => 'Mayora Indah Tbk', 'status_kerjasama' => 'Aktif']);
        if (!$company4) $company4 = Company::factory()->create(['nama_perusahaan' => 'PT. ABC Jaya', 'status_kerjasama' => 'Aktif']);
        if (!$company5) $company5 = Company::factory()->create(['nama_perusahaan' => 'Telkomsel', 'status_kerjasama' => 'Aktif']);
        if (!$company6) $company6 = Company::factory()->create(['nama_perusahaan' => 'GoJek', 'status_kerjasama' => 'Aktif']);


        $lowongansData = [
            // Existing Seeders (Adjusted some statuses to 'Aktif' for better testing)
            [
                'company_id' => $company1 ? $company1->id : null,
                'judul' => 'Software Engineer Intern (Backend)', // Matches 'Backend Developer'
                'deskripsi' => 'Bergabunglah dengan tim engineering kami untuk mengembangkan dan memelihara layanan backend yang skalabel dan efisien. Anda akan bekerja di lingkungan yang dinamis dan mendukung.',
                'kualifikasi' => "- Mahasiswa tingkat akhir atau fresh graduate jurusan Teknik Informatika, Sistem Informasi, atau terkait.\n- Memahami konsep OOP dan struktur data.\n- Familiar dengan salah satu bahasa pemrograman backend (misalnya PHP, Python, Java, Node.js).\n- Memiliki kemampuan analisis dan problem solving yang baik.\n- Bersedia belajar teknologi baru dan bekerja dalam tim.",
                'tanggung_jawab' => "- Mengembangkan dan mengimplementasikan fitur backend baru sesuai arahan.\n- Memelihara dan mengoptimalkan kode yang sudah ada.\n- Berpartisipasi dalam code review dan diskusi desain.\n- Membantu memecahkan bug dan masalah performa.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Selatan',
                'alamat' => 'Jl. Jend. Sudirman Kav. 52-53, SCBD',
                'kode_pos' => '12190',
                'gaji_min' => 3000000.00,
                'gaji_max' => 5000000.00,
                'tanggal_buka' => Carbon::now()->subDays(10)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Aktif', // Changed to Aktif
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Human Resources Intern',
                'deskripsi' => 'Membantu tim HR dalam proses rekrutmen, administrasi, dan pengembangan karyawan. Anda akan mendapatkan pengalaman praktis di lingkungan HR korporat.',
                'kualifikasi' => "- Mahasiswa jurusan Psikologi, Manajemen SDM, atau terkait.\n- Memiliki kemampuan komunikasi dan interpersonal yang baik.\n- Teliti dan mampu bekerja dalam tim.\n- Tertarik pada bidang pengembangan sumber daya manusia.",
                'tanggung_jawab' => "- Mendukung proses rekrutmen (screening CV, menjadwalkan wawancara).\n- Membantu dalam administrasi dokumen karyawan.\n- Berpartisipasi dalam program orientasi karyawan baru.\n- Melakukan riset topik-topik HR terkait.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Utara',
                'alamat' => 'Jl. Gaya Motor Raya No. 8, Sunter',
                'kode_pos' => '14330',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(5)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(25)->toDateString(),
                'status' => 'Aktif', // Changed to Aktif
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Backend Developer Intern', // Matches 'Backend Developer'
                'deskripsi' => 'Berpartisipasi dalam pengembangan backend aplikasi web menggunakan Laravel dan MySQL. Anda akan belajar dari para mentor berpengalaman.',
                'kualifikasi' => "- Mahasiswa aktif jurusan Teknik Informatika atau sejenis.\n- Memahami dasar-dasar PHP dan Laravel.\n- Familiar dengan database relasional (MySQL, PostgreSQL).\n- Mampu bekerja secara tim dan mandiri.",
                'tanggung_jawab' => "- Membantu mengembangkan API RESTful.\n- Mengelola dan mengoptimalkan database.\n- Melakukan debugging dan perbaikan bug.\n- Menulis unit dan feature test.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Pusat',
                'alamat' => 'Jl. Medan Merdeka Timur No. 16',
                'kode_pos' => '10110',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(3)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'Aktif', // Changed to Aktif
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'UI/UX Designer Intern', // Matches 'UI/UX Designer'
                'deskripsi' => 'Mendukung tim desain dalam membuat user interface dan user experience untuk aplikasi mobile dan web. Anda akan terlibat dalam seluruh siklus desain.',
                'kualifikasi' => "- Mahasiswa jurusan DKV, Informatika, atau sejenis.\n- Menguasai Figma atau Adobe XD.\n- Memiliki portofolio desain menjadi nilai tambah.\n- Kreatif dan detail-oriented.",
                'tanggung_jawab' => "- Membuat wireframe, mockup, dan prototype.\n- Melakukan riset pengguna dan analisis kompetitor.\n- Berkolaborasi dengan developer untuk implementasi desain.\n- Memastikan konsistensi desain di seluruh platform.",
                'tipe' => 'Magang',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Diponegoro No. 22',
                'kode_pos' => '40115',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(2)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Aktif', // Changed to Aktif
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Data Analyst Intern', // Matches 'Data Analyst'
                'deskripsi' => 'Menganalisis data penjualan dan membuat laporan visual untuk mendukung pengambilan keputusan bisnis yang strategis.',
                'kualifikasi' => "- Mahasiswa jurusan Statistika, Informatika, atau sejenis.\n- Menguasai Excel, SQL, dan salah satu tools visualisasi (Power BI/Tableau).\n- Analitis dan detail.\n- Komunikasi yang baik dalam menyampaikan insight.",
                'tanggung_jawab' => "- Mengumpulkan, membersihkan, dan memproses data.\n- Melakukan analisis statistik untuk mengidentifikasi tren.\n- Membuat dashboard dan laporan visual yang mudah dipahami.\n- Memberikan rekomendasi berdasarkan analisis data.",
                'tipe' => 'Magang',
                'provinsi' => 'Yogyakarta',
                'kota' => 'Yogyakarta',
                'alamat' => 'Jl. Malioboro No. 100',
                'kode_pos' => '55272',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(7)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(23)->toDateString(),
                'status' => 'Aktif', // Changed to Aktif
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Cybersecurity Intern', // Matches 'Cyber Security'
                'deskripsi' => 'Membantu tim keamanan informasi dalam mengidentifikasi dan menanggulangi potensi ancaman siber. Anda akan belajar praktik terbaik dalam keamanan digital.',
                'kualifikasi' => "- Mahasiswa jurusan Teknik Informatika, Keamanan Siber, atau sejenis.\n- Memahami konsep dasar keamanan jaringan.\n- Familiar dengan tools pentesting seperti Wireshark atau Burp Suite.\n- Memiliki integritas dan keingintahuan tinggi.",
                'tanggung_jawab' => "- Melakukan pemantauan keamanan jaringan.\n- Membantu dalam investigasi insiden keamanan.\n- Melakukan penilaian kerentanan dasar.\n- Mendokumentasikan prosedur keamanan.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Selatan',
                'alamat' => 'Gedung Cyber 2, Kuningan',
                'kode_pos' => '12940',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(15)->toDateString(),
                'status' => 'Aktif', // Changed to Aktif
            ],
            [
                'company_id' => $company3 ? $company3->id : null,
                'judul' => 'Marketing Intern',
                'deskripsi' => 'Terlibat dalam perencanaan dan eksekusi kampanye pemasaran digital dan offline. Anda akan mendapatkan wawasan tentang strategi merek global.',
                'kualifikasi' => "- Mahasiswa jurusan Marketing, Komunikasi, atau Bisnis.\n- Kreatif dan memiliki pemahaman dasar tentang marketing.\n- Aktif di media sosial dan mengikuti tren terkini.\n- Mampu bekerja secara mandiri maupun dalam tim.",
                'tanggung_jawab' => "- Membantu riset pasar dan analisis kompetitor.\n- Mendukung pembuatan materi promosi.\n- Membantu pengelolaan akun media sosial.\n- Menganalisis data kampanye pemasaran.",
                'tipe' => 'Magang',
                'provinsi' => 'Banten',
                'kota' => 'Tangerang',
                'alamat' => 'Jl. Daan Mogot KM. 19, Batuceper',
                'kode_pos' => '15122',
                'gaji_min' => 2500000.00,
                'gaji_max' => 4000000.00,
                'tanggal_buka' => Carbon::now()->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company1 ? $company1->id : null,
                'judul' => 'Data Analyst Intern',
                'deskripsi' => 'Membantu dalam pengumpulan, pengolahan, dan analisis data untuk mendukung keputusan bisnis yang berbasis data.',
                'kualifikasi' => "- Mahasiswa jurusan Statistika, Matematika, Teknik Informatika, atau bidang terkait.\n- Memiliki kemampuan analisis kuantitatif yang kuat.\n- Familiar dengan tools analisis data (misalnya SQL, Excel, Python/R adalah nilai plus).\n- Teliti dan detail-oriented.",
                'tanggung_jawab' => "- Membantu mengumpulkan dan membersihkan data dari berbagai sumber.\n- Melakukan eksplorasi data untuk mengidentifikasi pola.\n- Membuat visualisasi data dan laporan dasar.\n- Mendukung tim analis dalam proyek-proyek data.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Selatan',
                'alamat' => 'Jl. Ampera Raya No. 13',
                'kode_pos' => '12560',
                'gaji_min' => 3500000.00,
                'gaji_max' => 5500000.00,
                'tanggal_buka' => Carbon::now()->subDays(15)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(15)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company4 ? $company4->id : null,
                'judul' => 'IT Support Intern',
                'deskripsi' => 'Memberikan dukungan teknis untuk infrastruktur IT perusahaan dan pengguna internal. Anda akan belajar tentang troubleshooting dan pemeliharaan sistem.',
                'kualifikasi' => "- Mahasiswa D3/S1 jurusan Teknik Komputer, Jaringan, atau terkait.\n- Memahami dasar-dasar troubleshooting hardware dan software.\n- Familiar dengan sistem operasi Windows dan Linux.\n- Komunikatif dan sabar dalam melayani pengguna.",
                'tanggung_jawab' => "- Menangani masalah IT dasar pengguna (hardware/software).\n- Membantu instalasi dan konfigurasi perangkat.\n- Melakukan pemeliharaan rutin sistem IT.\n- Mencatat dan melacak masalah IT.",
                'tipe' => 'Magang',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Pahlawan No. 70',
                'kode_pos' => '40123',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(2)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(40)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Finance & Accounting Intern',
                'deskripsi' => 'Membantu departemen keuangan dan akuntansi dalam tugas sehari-hari, termasuk pembukuan dan pelaporan keuangan.',
                'kualifikasi' => "- Mahasiswa jurusan Akuntansi atau Keuangan.\n- Memahami dasar-dasar akuntansi dan keuangan.\n- Teliti, jujur, dan bertanggung jawab.\n- Mampu mengoperasikan Microsoft Excel.",
                'tanggung_jawab' => "- Membantu dalam proses input data keuangan.\n- Membantu rekonsiliasi laporan bank.\n- Mendukung penyusunan laporan keuangan dasar.\n- Melakukan arsip dokumen keuangan.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Pusat',
                'alamat' => 'Jl. Kebon Sirih No. 18',
                'kode_pos' => '10340',
                'gaji_min' => 3000000.00,
                'gaji_max' => 4500000.00,
                'tanggal_buka' => Carbon::now()->subDays(20)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(10)->toDateString(),
                'status' => 'Aktif',
            ],

            // NEW Seeders for Missing Alternative Roles
            [
                'company_id' => $company5 ? $company5->id : null,
                'judul' => 'Fullstack Developer Intern', // Matches 'Fullstack Developer'
                'deskripsi' => 'Bergabunglah dengan tim kami untuk mengembangkan aplikasi web fullstack menggunakan teknologi modern. Anda akan terlibat dari front-end hingga back-end.',
                'kualifikasi' => "- Mahasiswa aktif jurusan Teknik Informatika.\n- Memahami dasar-dasar pengembangan web (HTML, CSS, JavaScript).\n- Familiar dengan framework PHP (Laravel) dan JavaScript (React/Vue).\n- Mampu bekerja dalam tim.",
                'tanggung_jawab' => "- Mengembangkan fitur baru untuk aplikasi web.\n- Memelihara dan meningkatkan kode yang sudah ada.\n- Melakukan debugging dan perbaikan bug.\n- Berpartisipasi dalam code review.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Barat',
                'alamat' => 'Jl. Tomang Raya No. 10',
                'kode_pos' => '11440',
                'gaji_min' => 3500000.00,
                'gaji_max' => 6000000.00,
                'tanggal_buka' => Carbon::now()->subDays(5)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(35)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company6 ? $company6->id : null,
                'judul' => 'Web Developer Intern', // Matches 'Web Developer'
                'deskripsi' => 'Membantu tim pengembangan web dalam membangun dan memelihara situs web dan aplikasi berbasis browser.',
                'kualifikasi' => "- Mahasiswa jurusan Teknik Informatika atau relevan.\n- Menguasai HTML, CSS, JavaScript.\n- Familiar dengan framework modern (misal React, Vue, Angular).\n- Memiliki passion di bidang web development.",
                'tanggung_jawab' => "- Mengembangkan komponen UI/UX.\n- Mengintegrasikan API dengan front-end.\n- Melakukan pengujian dan debugging web.\n- Memastikan responsivitas dan performa situs.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Selatan',
                'alamat' => 'Jl. Gatot Subroto No. 5',
                'kode_pos' => '12950',
                'gaji_min' => 3000000.00,
                'gaji_max' => 5000000.00,
                'tanggal_buka' => Carbon::now()->subDays(10)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company5 ? $company5->id : null,
                'judul' => 'Machine Learning Engineer Intern', // Matches 'Machine Learning Engineer'
                'deskripsi' => 'Terlibat dalam pengembangan model machine learning untuk berbagai proyek inovatif, dari data preprocessing hingga deployment.',
                'kualifikasi' => "- Mahasiswa tingkat akhir/fresh graduate jurusan Ilmu Komputer, Statistika, atau Matematika.\n- Memahami dasar-dasar Machine Learning dan Deep Learning.\n- Menguasai Python dan library ML (TensorFlow/PyTorch).\n- Pengalaman dengan data preprocessing dan evaluasi model.",
                'tanggung_jawab' => "- Membantu riset dan implementasi algoritma ML.\n- Melatih dan mengevaluasi model ML.\n- Mengumpulkan dan membersihkan dataset.\n- Mendukung deployment model ke produksi.",
                'tipe' => 'Magang',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Asia Afrika No. 100',
                'kode_pos' => '40112',
                'gaji_min' => 4000000.00,
                'gaji_max' => 7000000.00,
                'tanggal_buka' => Carbon::now()->subDays(3)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(28)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company6 ? $company6->id : null,
                'judul' => 'Data Scientist Intern', // Matches 'Data Scientist'
                'deskripsi' => 'Menganalisis data besar untuk menemukan pola dan memberikan insight yang dapat mendorong pertumbuhan bisnis. Anda akan bekerja dengan berbagai dataset.',
                'kualifikasi' => "- Mahasiswa S1/S2 jurusan Ilmu Komputer, Statistika, Matematika, atau relevan.\n- Menguasai Python/R untuk analisis data.\n- Familiar dengan SQL dan database relasional/NoSQL.\n- Memiliki kemampuan berpikir analitis dan problem solving.",
                'tanggung_jawab' => "- Mengembangkan dan mengimplementasikan model statistik.\n- Melakukan eksplorasi data untuk identifikasi tren.\n- Membuat laporan dan presentasi hasil analisis.\n- Berkolaborasi dengan tim produk dan teknik.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Selatan',
                'alamat' => 'Jl. HR Rasuna Said Kav. B-32',
                'kode_pos' => '12920',
                'gaji_min' => 4500000.00,
                'gaji_max' => 7500000.00,
                'tanggal_buka' => Carbon::now()->subDays(10)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company1 ? $company1->id : null,
                'judul' => 'Network Engineer Intern', // Matches 'Computer Network'
                'deskripsi' => 'Membantu dalam perencanaan, implementasi, dan pemeliharaan infrastruktur jaringan perusahaan. Anda akan belajar tentang teknologi jaringan terkini.',
                'kualifikasi' => "- Mahasiswa jurusan Teknik Komputer/Jaringan.\n- Memahami dasar-dasar TCP/IP, routing, dan switching.\n- Familiar dengan perangkat Cisco/Juniper.\n- Tertarik pada bidang jaringan komputer.",
                'tanggung_jawab' => "- Mendukung konfigurasi perangkat jaringan.\n- Melakukan monitoring performa jaringan.\n- Membantu troubleshooting masalah konektivitas.\n- Mendokumentasikan topologi jaringan.",
                'tipe' => 'Magang',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Pusat',
                'alamat' => 'Jl. MH Thamrin No. 10',
                'kode_pos' => '10350',
                'gaji_min' => 3000000.00,
                'gaji_max' => 5000000.00,
                'tanggal_buka' => Carbon::now()->subDays(7)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(25)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Quality Assurance (QA) Intern', // Matches 'Quality Assurance'
                'deskripsi' => 'Bertanggung jawab untuk memastikan kualitas perangkat lunak melalui pengujian dan identifikasi bug. Anda akan belajar siklus pengujian perangkat lunak.',
                'kualifikasi' => "- Mahasiswa jurusan Teknik Informatika atau setara.\n- Memahami konsep dasar pengujian perangkat lunak.\n- Teliti, detail-oriented, dan memiliki kemampuan analisis.\n- Mampu menulis test case dan laporan bug.",
                'tanggung_jawab' => "- Melakukan pengujian fungsional dan non-fungsional.\n- Mengidentifikasi, mereplikasi, dan mendokumentasikan bug.\n- Berkolaborasi dengan tim developer untuk perbaikan.\n- Membantu dalam otomatisasi pengujian.",
                'tipe' => 'Magang',
                'provinsi' => 'Jawa Timur',
                'kota' => 'Surabaya',
                'alamat' => 'Jl. Jemursari No. 50',
                'kode_pos' => '60292',
                'gaji_min' => 2800000.00,
                'gaji_max' => 4800000.00,
                'tanggal_buka' => Carbon::now()->subDays(12)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(18)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company3 ? $company3->id : null,
                'judul' => 'System Analyst Intern', // Matches 'System Analyst'
                'deskripsi' => 'Membantu dalam menganalisis kebutuhan bisnis dan menerjemahkannya menjadi spesifikasi teknis untuk pengembangan sistem informasi.',
                'kualifikasi' => "- Mahasiswa jurusan Sistem Informasi, Teknik Informatika, atau Manajemen.\n- Memahami dasar-dasar analisis sistem.\n- Memiliki kemampuan komunikasi yang baik.\n- Mampu membuat diagram alir dan dokumentasi teknis.",
                'tanggung_jawab' => "- Mengumpulkan dan mendokumentasikan kebutuhan pengguna.\n- Membantu dalam perancangan alur sistem.\n- Berkolaborasi dengan developer untuk memastikan implementasi sesuai spesifikasi.\n- Membuat laporan analisis sistem.",
                'tipe' => 'Magang',
                'provinsi' => 'Jawa Tengah',
                'kota' => 'Semarang',
                'alamat' => 'Jl. Pemuda No. 10',
                'kode_pos' => '50132',
                'gaji_min' => 3200000.00,
                'gaji_max' => 5200000.00,
                'tanggal_buka' => Carbon::now()->subDays(8)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(22)->toDateString(),
                'status' => 'Aktif',
            ],
        ];

        foreach ($lowongansData as $lowonganData) {
            // Hanya buat lowongan jika company_id valid (perusahaan ditemukan)
            if ($lowonganData['company_id']) {
                Lowongan::create($lowonganData);
            } else {
                // Log peringatan jika perusahaan tidak ditemukan
                $this->command->warn("Company for job '{$lowonganData['judul']}' not found, skipping.");
            }
        }

        $this->command->info(count($lowongansData).' lowongan telah ditambahkan (atau diskip jika perusahaan tidak ditemukan).');
    }
}