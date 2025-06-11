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

        $lowongansData = [
            [
                'company_id' => $company1 ? $company1->id : null,
                'judul' => 'Software Engineer Intern (Backend)',
                'deskripsi' => 'Bergabunglah dengan tim engineering kami untuk mengembangkan dan memelihara layanan backend yang skalabel dan efisien. Anda akan bekerja di lingkungan yang dinamis dan mendukung.',
                'kualifikasi' => "- Mahasiswa tingkat akhir atau fresh graduate jurusan Teknik Informatika, Sistem Informasi, atau terkait.\n- Memahami konsep OOP dan struktur data.\n- Familiar dengan salah satu bahasa pemrograman backend (misalnya PHP, Python, Java, Node.js).\n- Memiliki kemampuan analisis dan problem solving yang baik.\n- Bersedia belajar teknologi baru dan bekerja dalam tim.",
                'tanggung_jawab' => "- Mengembangkan dan mengimplementasikan fitur backend baru sesuai arahan.\n- Memelihara dan mengoptimalkan kode yang sudah ada.\n- Berpartisipasi dalam code review dan diskusi desain.\n- Membantu memecahkan bug dan masalah performa.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
                'provinsi' => 'DKI Jakarta', // Mengganti 'lokasi' dengan 'provinsi'
                'kota' => 'Jakarta Selatan',   // Menambahkan 'kota'
                'alamat' => 'Jl. Jend. Sudirman Kav. 52-53, SCBD', // Menambahkan 'alamat'
                'kode_pos' => '12190',     // Menambahkan 'kode_pos'
                'gaji_min' => 3000000.00, // Menggunakan float
                'gaji_max' => 5000000.00, // Menggunakan float
                'tanggal_buka' => Carbon::now()->subDays(10)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Human Resources Intern',
                'deskripsi' => 'Membantu tim HR dalam proses rekrutmen, administrasi, dan pengembangan karyawan. Anda akan mendapatkan pengalaman praktis di lingkungan HR korporat.',
                'kualifikasi' => "- Mahasiswa jurusan Psikologi, Manajemen SDM, atau terkait.\n- Memiliki kemampuan komunikasi dan interpersonal yang baik.\n- Teliti dan mampu bekerja dalam tim.\n- Tertarik pada bidang pengembangan sumber daya manusia.",
                'tanggung_jawab' => "- Mendukung proses rekrutmen (screening CV, menjadwalkan wawancara).\n- Membantu dalam administrasi dokumen karyawan.\n- Berpartisipasi dalam program orientasi karyawan baru.\n- Melakukan riset topik-topik HR terkait.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Utara',
                'alamat' => 'Jl. Gaya Motor Raya No. 8, Sunter',
                'kode_pos' => '14330',
                'gaji_min' => null, // Bisa dikosongkan jika tidak ada info gaji
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(5)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(25)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Backend Developer Intern',
                'deskripsi' => 'Berpartisipasi dalam pengembangan backend aplikasi web menggunakan Laravel dan MySQL. Anda akan belajar dari para mentor berpengalaman.',
                'kualifikasi' => "- Mahasiswa aktif jurusan Teknik Informatika atau sejenis.\n- Memahami dasar-dasar PHP dan Laravel.\n- Familiar dengan database relasional (MySQL, PostgreSQL).\n- Mampu bekerja secara tim dan mandiri.",
                'tanggung_jawab' => "- Membantu mengembangkan API RESTful.\n- Mengelola dan mengoptimalkan database.\n- Melakukan debugging dan perbaikan bug.\n- Menulis unit dan feature test.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Pusat',
                'alamat' => 'Jl. Medan Merdeka Timur No. 16',
                'kode_pos' => '10110',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(3)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'Nonaktif', // Menggunakan 'Nonaktif' sesuai HTML form
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'UI/UX Designer Intern',
                'deskripsi' => 'Mendukung tim desain dalam membuat user interface dan user experience untuk aplikasi mobile dan web. Anda akan terlibat dalam seluruh siklus desain.',
                'kualifikasi' => "- Mahasiswa jurusan DKV, Informatika, atau sejenis.\n- Menguasai Figma atau Adobe XD.\n- Memiliki portofolio desain menjadi nilai tambah.\n- Kreatif dan detail-oriented.",
                'tanggung_jawab' => "- Membuat wireframe, mockup, dan prototype.\n- Melakukan riset pengguna dan analisis kompetitor.\n- Berkolaborasi dengan developer untuk implementasi desain.\n- Memastikan konsistensi desain di seluruh platform.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Diponegoro No. 22',
                'kode_pos' => '40115',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(2)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Nonaktif', // Menggunakan 'Nonaktif' sesuai HTML form
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Data Analyst Intern',
                'deskripsi' => 'Menganalisis data penjualan dan membuat laporan visual untuk mendukung pengambilan keputusan bisnis yang strategis.',
                'kualifikasi' => "- Mahasiswa jurusan Statistika, Informatika, atau sejenis.\n- Menguasai Excel, SQL, dan salah satu tools visualisasi (Power BI/Tableau).\n- Analitis dan detail.\n- Komunikasi yang baik dalam menyampaikan insight.",
                'tanggung_jawab' => "- Mengumpulkan, membersihkan, dan memproses data.\n- Melakukan analisis statistik untuk mengidentifikasi tren.\n- Membuat dashboard dan laporan visual yang mudah dipahami.\n- Memberikan rekomendasi berdasarkan analisis data.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
                'provinsi' => 'Yogyakarta',
                'kota' => 'Yogyakarta',
                'alamat' => 'Jl. Malioboro No. 100',
                'kode_pos' => '55272',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(7)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(23)->toDateString(),
                'status' => 'Nonaktif', // Menggunakan 'Nonaktif' sesuai HTML form
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Cybersecurity Intern',
                'deskripsi' => 'Membantu tim keamanan informasi dalam mengidentifikasi dan menanggulangi potensi ancaman siber. Anda akan belajar praktik terbaik dalam keamanan digital.',
                'kualifikasi' => "- Mahasiswa jurusan Teknik Informatika, Keamanan Siber, atau sejenis.\n- Memahami konsep dasar keamanan jaringan.\n- Familiar dengan tools pentesting seperti Wireshark atau Burp Suite.\n- Memiliki integritas dan keingintahuan tinggi.",
                'tanggung_jawab' => "- Melakukan pemantauan keamanan jaringan.\n- Membantu dalam investigasi insiden keamanan.\n- Melakukan penilaian kerentanan dasar.\n- Mendokumentasikan prosedur keamanan.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
                'provinsi' => 'DKI Jakarta', // Contoh remote tapi lokasi perusahaan di Jakarta
                'kota' => 'Jakarta Selatan',
                'alamat' => 'Gedung Cyber 2, Kuningan',
                'kode_pos' => '12940',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(15)->toDateString(),
                'status' => 'Nonaktif', // Menggunakan 'Nonaktif' sesuai HTML form
            ],
            [
                'company_id' => $company3 ? $company3->id : null,
                'judul' => 'Marketing Intern',
                'deskripsi' => 'Terlibat dalam perencanaan dan eksekusi kampanye pemasaran digital dan offline. Anda akan mendapatkan wawasan tentang strategi merek global.',
                'kualifikasi' => "- Mahasiswa jurusan Marketing, Komunikasi, atau Bisnis.\n- Kreatif dan memiliki pemahaman dasar tentang marketing.\n- Aktif di media sosial dan mengikuti tren terkini.\n- Mampu bekerja secara mandiri maupun dalam tim.",
                'tanggung_jawab' => "- Membantu riset pasar dan analisis kompetitor.\n- Mendukung pembuatan materi promosi.\n- Membantu pengelolaan akun media sosial.\n- Menganalisis data kampanye pemasaran.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
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
                'company_id' => $company1 ? $company1->id : null, // Lowongan lain di Payfazz
                'judul' => 'Data Analyst Intern',
                'deskripsi' => 'Membantu dalam pengumpulan, pengolahan, dan analisis data untuk mendukung keputusan bisnis yang berbasis data.',
                'kualifikasi' => "- Mahasiswa jurusan Statistika, Matematika, Teknik Informatika, atau bidang terkait.\n- Memiliki kemampuan analisis kuantitatif yang kuat.\n- Familiar dengan tools analisis data (misalnya SQL, Excel, Python/R adalah nilai plus).\n- Teliti dan detail-oriented.",
                'tanggung_jawab' => "- Membantu mengumpulkan dan membersihkan data dari berbagai sumber.\n- Melakukan eksplorasi data untuk mengidentifikasi pola.\n- Membuat visualisasi data dan laporan dasar.\n- Mendukung tim analis dalam proyek-proyek data.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
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
                'company_id' => $company4 ? $company4->id : null, // Assuming PT. ABC Jaya exists
                'judul' => 'IT Support Intern',
                'deskripsi' => 'Memberikan dukungan teknis untuk infrastruktur IT perusahaan dan pengguna internal. Anda akan belajar tentang troubleshooting dan pemeliharaan sistem.',
                'kualifikasi' => "- Mahasiswa D3/S1 jurusan Teknik Komputer, Jaringan, atau terkait.\n- Memahami dasar-dasar troubleshooting hardware dan software.\n- Familiar dengan sistem operasi Windows dan Linux.\n- Komunikatif dan sabar dalam melayani pengguna.",
                'tanggung_jawab' => "- Menangani masalah IT dasar pengguna (hardware/software).\n- Membantu instalasi dan konfigurasi perangkat.\n- Melakukan pemeliharaan rutin sistem IT.\n- Mencatat dan melacak masalah IT.",
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
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
                'tipe' => 'Magang', // Menggunakan 'Magang' sesuai HTML form
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
        ];

        foreach ($lowongansData as $lowonganData) {
            // Hanya buat lowongan jika company_id valid (perusahaan ditemukan)
            if ($lowonganData['company_id']) {
                Lowongan::create($lowonganData);
            } else {
                // Opsi: Log peringatan jika perusahaan tidak ditemukan
                $this->command->warn("Company for job '{$lowonganData['judul']}' not found, skipping.");
            }
        }

        $this->command->info(count($lowongansData).' lowongan telah ditambahkan (atau diskip jika perusahaan tidak ditemukan).');
    }
}
