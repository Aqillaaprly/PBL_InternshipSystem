<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lowongan;
use App\Models\Company; // Pastikan model Company di-import
use Carbon\Carbon; // Untuk manipulasi tanggal

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
        $company4 = Company::where('nama_perusahaan', 'PT. ABC Jaya')->first();

        $lowongans = [
            [
                'company_id' => $company1 ? $company1->id : null,
                'judul' => 'Software Engineer Intern (Backend)',
                'deskripsi' => 'Bergabunglah dengan tim engineering kami untuk mengembangkan dan memelihara layanan backend.',
                'kualifikasi' => "- Mahasiswa tingkat akhir atau fresh graduate jurusan Teknik Informatika, Sistem Informasi, atau terkait.\n- Memahami konsep OOP dan struktur data.\n- Familiar dengan salah satu bahasa pemrograman backend (misalnya PHP, Python, Java, Node.js).\n- Memiliki kemampuan analisis dan problem solving yang baik.",
                'tipe' => 'Internship',
                'lokasi' => 'Jakarta Selatan, DKI Jakarta',
                'gaji_min' => 3000000,
                'gaji_max' => 5000000,
                'tanggal_buka' => Carbon::now()->subDays(10)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Human Resources Intern',
                'deskripsi' => 'Membantu tim HR dalam proses rekrutmen, administrasi, dan pengembangan karyawan.',
                'kualifikasi' => "- Mahasiswa jurusan Psikologi, Manajemen SDM, atau terkait.\n- Memiliki kemampuan komunikasi dan interpersonal yang baik.\n- Teliti dan mampu bekerja dalam tim.\n- Tertarik pada bidang pengembangan sumber daya manusia.",
                'tipe' => 'Internship',
                'lokasi' => 'Sunter, Jakarta Utara',
                'gaji_min' => null, // Bisa dikosongkan jika tidak ada info gaji
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(5)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(25)->toDateString(),
                'status' => 'Aktif',
            ],
            [
               'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Backend Developer Intern',
                'deskripsi' => 'Berpartisipasi dalam pengembangan backend aplikasi web menggunakan Laravel dan MySQL.',
                'kualifikasi' => "- Mahasiswa aktif jurusan Teknik Informatika atau sejenis.\n- Memahami dasar-dasar PHP dan Laravel.\n- Familiar dengan database relasional (MySQL, PostgreSQL).\n- Mampu bekerja secara tim dan mandiri.",
                'tipe' => 'Internship',
                'lokasi' => 'Remote / Jakarta',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(3)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'Non-Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'UI/UX Designer Intern',
                'deskripsi' => 'Mendukung tim desain dalam membuat user interface dan user experience untuk aplikasi mobile.',
                'kualifikasi' => "- Mahasiswa jurusan DKV, Informatika, atau sejenis.\n- Menguasai Figma atau Adobe XD.\n- Memiliki portofolio desain menjadi nilai tambah.\n- Kreatif dan detail-oriented.",
                'tipe' => 'Internship',
                'lokasi' => 'Bandung, Jawa Barat',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(2)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(20)->toDateString(),
                'status' => 'Non-Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Data Analyst Intern',
                'deskripsi' => 'Menganalisis data penjualan dan membuat laporan visual untuk mendukung pengambilan keputusan bisnis.',
                'kualifikasi' => "- Mahasiswa jurusan Statistika, Informatika, atau sejenis.\n- Menguasai Excel, SQL, dan salah satu tools visualisasi (Power BI/Tableau).\n- Analitis dan detail.\n- Komunikasi yang baik dalam menyampaikan insight.",
                'tipe' => 'Internship',
                'lokasi' => 'Yogyakarta',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(7)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(23)->toDateString(),
                'status' => 'Non-Aktif',
            ],
            [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Cybersecurity Intern',
                'deskripsi' => 'Membantu tim keamanan informasi dalam mengidentifikasi dan menanggulangi potensi ancaman siber.',
                'kualifikasi' => "- Mahasiswa jurusan Teknik Informatika, Keamanan Siber, atau sejenis.\n- Memahami konsep dasar keamanan jaringan.\n- Familiar dengan tools pentesting seperti Wireshark atau Burp Suite.\n- Memiliki integritas dan keingintahuan tinggi.",
                'tipe' => 'Internship',
                'lokasi' => 'Remote',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(15)->toDateString(),
                'status' => 'Non-Aktif',
            ],
            [
                'company_id' => $company3 ? $company3->id : null,
                'judul' => 'Marketing Intern',
                'deskripsi' => 'Terlibat dalam perencanaan dan eksekusi kampanye pemasaran digital dan offline.',
                'kualifikasi' => "- Mahasiswa jurusan Marketing, Komunikasi, atau Bisnis.\n- Kreatif dan memiliki pemahaman dasar tentang marketing.\n- Aktif di media sosial dan mengikuti tren terkini.\n- Mampu bekerja secara mandiri maupun dalam tim.",
                'tipe' => 'Internship',
                'lokasi' => 'Tangerang, Banten',
                'gaji_min' => 2500000,
                'gaji_max' => 4000000,
                'tanggal_buka' => Carbon::now()->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company1 ? $company1->id : null, // Lowongan lain di Payfazz
                'judul' => 'Data Analyst Intern',
                'deskripsi' => 'Membantu dalam pengumpulan, pengolahan, dan analisis data untuk mendukung keputusan bisnis.',
                'kualifikasi' => "- Mahasiswa jurusan Statistika, Matematika, Teknik Informatika, atau bidang terkait.\n- Memiliki kemampuan analisis kuantitatif yang kuat.\n- Familiar dengan tools analisis data (misalnya SQL, Excel, Python/R adalah nilai plus).\n- Teliti dan detail-oriented.",
                'tipe' => 'Internship',
                'lokasi' => 'Jakarta Selatan, DKI Jakarta',
                'gaji_min' => 3500000,
                'gaji_max' => 5500000,
                'tanggal_buka' => Carbon::now()->subDays(15)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(15)->toDateString(),
                'status' => 'Aktif',
            ],
            [
                'company_id' => $company4 ? $company4->id : null,
                'judul' => 'IT Support Intern',
                'deskripsi' => 'Memberikan dukungan teknis untuk infrastruktur IT perusahaan dan pengguna internal.',
                'kualifikasi' => "- Mahasiswa D3/S1 jurusan Teknik Komputer, Jaringan, atau terkait.\n- Memahami dasar-dasar troubleshooting hardware dan software.\n- Familiar dengan sistem operasi Windows dan Linux.\n- Komunikatif dan sabar dalam melayani pengguna.",
                'tipe' => 'Internship',
                'lokasi' => 'Bandung, Jawa Barat',
                'gaji_min' => null,
                'gaji_max' => null,
                'tanggal_buka' => Carbon::now()->subDays(2)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(40)->toDateString(),
                'status' => 'Aktif',
            ],
             [
                'company_id' => $company2 ? $company2->id : null,
                'judul' => 'Finance & Accounting Intern',
                'deskripsi' => 'Membantu departemen keuangan dan akuntansi dalam tugas sehari-hari, termasuk pembukuan dan pelaporan.',
                'kualifikasi' => "- Mahasiswa jurusan Akuntansi atau Keuangan.\n- Memahami dasar-dasar akuntansi dan keuangan.\n- Teliti, jujur, dan bertanggung jawab.\n- Mampu mengoperasikan Microsoft Excel.",
                'tipe' => 'Internship',
                'lokasi' => 'Jakarta Pusat, DKI Jakarta',
                'gaji_min' => 3000000,
                'gaji_max' => 4500000,
                'tanggal_buka' => Carbon::now()->subDays(20)->toDateString(),
                'tanggal_tutup' => Carbon::now()->addDays(10)->toDateString(),
                'status' => 'Aktif'
            ],
        ];

        foreach ($lowongans as $lowonganData) {
            // Hanya buat lowongan jika company_id valid (perusahaan ditemukan)
            if ($lowonganData['company_id']) {
                Lowongan::create($lowonganData);
            } else {
                // Opsi: Log peringatan jika perusahaan tidak ditemukan
                $this->command->warn("Company for job '{$lowonganData['judul']}' not found, skipping.");
            }
        }

        $this->command->info(count($lowongans) . ' lowongan telah ditambahkan (atau diskip jika perusahaan tidak ditemukan).');
    }
}