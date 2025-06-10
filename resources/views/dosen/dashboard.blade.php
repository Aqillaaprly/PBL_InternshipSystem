absen<?php
// session_start();
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'perusahaan') {
//     header('Location: ../index.php');
//     exit;
// }

// require '../koneksi.php';

// // Contoh query, sesuaikan dengan tabel Anda
// $jumlahLowongan   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM lowongan WHERE perusahaan_id = 1"))['total'];
// $jumlahPendaftar  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran WHERE perusahaan_id = 1"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pembimbing - STRIDEUP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">

    @include('dosen.template.navbar') 

    <main class="flex flex-col min-h-screen">
        <div class="p-6 max-w-7xl mx-auto w-full mt-16"> {{-- Tambahkan margin top jika navbar fixed --}}
            <div class="w-full mb-6">
                <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg"
                     alt="Header"
                     class="w-full h-48 object-cover rounded-b-lg shadow" />
            </div>

             <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, {{ Auth::user()->dosen->nama_dosen ?? Auth::user()->name ?? 'Bapak' }}!</h1>
                <p class="text-sm text-gray-600">Dashboard Sistem Informasi Manajemen Magang Mahasiswa</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Pusat</h2>
                    <p class="text-gray-700 mb-3">Magang Pusat adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.</p>
                    <p class="text-gray-700">Perusahaan-perusahaan yang akan di jadikan tempat magang sudah terorganisir oleh kampus</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Mandiri</h2>
                    <p class="text-gray-700 mb-3">Magang Mandiri adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.</p>
                    <p class="text-gray-700">Mahasiswa mencari sendiri perusahaan-perusahan yang akan dijadikan untuk penerapan keilmuan atau kompetensi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-10">
                {{-- Statistik Program Studi Mahasiswa Diterima --}}
                <div class="bg-white p-6 rounded-xl shadow hover:bg-blue-50 transition">
                    <h2 class="font-semibold text-gray-700 mb-4 text-center">Statistik Mahasiswa Diterima (Program Studi)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                        <div class="text-sm">
                            @if(isset($statsProdiDiterima))
                                <ul class="space-y-2">
                                    <li>
                                        <span class="text-gray-600">Teknik Informatika:</span>
                                        <span class="font-bold text-blue-600 float-right">{{ $statsProdiDiterima['Teknik Informatika'] ?? 0 }}</span>
                                    </li>
                                    <li>
                                        <span class="text-gray-600">Sistem Informasi Bisnis:</span>
                                        <span class="font-bold text-blue-600 float-right">{{ $statsProdiDiterima['Sistem Informasi Bisnis'] ?? 0 }}</span>
                                    </li>
                                    @if(isset($statsProdiDiterima['Lainnya']) && $statsProdiDiterima['Lainnya'] > 0)
                                        <li>
                                            <span class="text-gray-600">Lainnya/Tidak Terdefinisi:</span>
                                            <span class="font-bold text-gray-600 float-right">{{ $statsProdiDiterima['Lainnya'] }}</span>
                                        </li>
                                    @endif
                                </ul>
                                @php
                                    $totalDiterimaHitung = ($statsProdiDiterima['Teknik Informatika'] ?? 0) + 
                                                           ($statsProdiDiterima['Sistem Informasi Bisnis'] ?? 0) + 
                                                           ($statsProdiDiterima['Lainnya'] ?? 0);
                                @endphp
                                @if($totalDiterimaHitung == 0)
                                    <p class="text-sm text-gray-500 mt-3 text-center md:text-left">Belum ada data mahasiswa diterima yang tercatat.</p>
                                @else
                                    <p class="text-sm text-gray-700 mt-4 pt-2 border-t">
                                        Total Mahasiswa Diterima: <span class="font-bold float-right">{{ $totalDiterimaHitung }}</span>
                                    </p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 text-center md:text-left">Data statistik program studi tidak tersedia.</p>
                            @endif
                        </div>
                        <div class="chart-container relative h-56 md:h-64 w-full">
                            <canvas id="prodiPieChart"></canvas>
                        </div>
                    </div>
                </div>
        @include('dosen.Job')

{{--Tabel mahasiswa bimbingan--}}
       <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg mt-6 border border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-blue-800 mb-4 sm:mb-0">Daftar Mahasiswa Bimbingan</h2>
                    <a href="{{ route('dosen.data_mahasiswabim') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Mahasiswa</a>
                </div>

                @if (session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('success') }}</span></div> @endif
                @if (session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div> @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIM</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Program Studi</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Periode Magang</th>
                            <th class="px-5 py-3">Tanggal Mulai</th>
                            <th class="px-5 py-3">Tanggal Selesai</th>
                            <th class="px-5 py-3">Status Bimbingan</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($bimbingans as $index => $bimbingan)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4">{{ $bimbingans->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->mahasiswa->detailMahasiswa->nim ?? '-' }}</td>
                                <td class="px-5 py-4 text-left">{{ $bimbingan->mahasiswa->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-left">{{ $bimbingan->mahasiswa->email ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->mahasiswa->detailMahasiswa->program_studi ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->mahasiswa->detailMahasiswa->kelas ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->periode_magang ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->tanggal_mulai ? \Carbon\Carbon::parse($bimbingan->tanggal_mulai)->format('d-m-Y') : '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->tanggal_selesai ? \Carbon\Carbon::parse($bimbingan->tanggal_selesai)->format('d-m-Y') : '-' }}</td>
                                <td class="px-5 py-4">
                                    @if ($bimbingan->status_bimbingan == 'Aktif')
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                                    @elseif ($bimbingan->status_bimbingan == 'Selesai')
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Selesai</span>
                                    @elseif ($bimbingan->status_bimbingan == 'Dibatalkan')
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Dibatalkan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada bimbingan ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data bimbingan magang.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
       </div>

  {{--Tabel Absensi mahasiswa bimbingan--}}
       <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg mt-6 border border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-blue-800 mb-4 sm:mb-0">Daftar Absensi Mahasiswa</h2>
                    <a href="{{ route('dosen.absensi.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Mahasiswa</a>
                </div>

                @if (session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('success') }}</span></div> @endif
                @if (session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div> @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm text-center border border-gray-200 rounded">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3 border-b border-gray-300">No</th>
                            <th class="px-5 py-3 border-b border-gray-300">Nama Mahasiswa</th>
                            <th class="px-5 py-3 border-b border-gray-300">Pembimbing</th>
                            <th class="px-5 py-3 border-b border-gray-300">Perusahaan</th>
                            <th class="px-5 py-3 border-b border-gray-300">Periode</th>
                            <th class="px-5 py-3 border-b border-gray-300">Total Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($data as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-4">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4">{{ $item->mahasiswa->name ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->pembimbing->nama_lengkap ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->company->nama_perusahaan ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->periode_magang }}</td>
                            <td class="px-5 py-4">{{ $item->total_hadir }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                Belum ada data absensi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
       </div>       
        </main>
        @include('dosen.template.footer')
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Pastikan variabel $statsProdiDiterima ada dan dikirim dari controller
    const statsData = @json($statsProdiDiterima ?? ['Teknik Informatika' => 0, 'Sistem Informasi Bisnis' => 0, 'Lainnya' => 0]);
    
    const dataValues = [
        statsData['Teknik Informatika'],
        statsData['Sistem Informasi Bisnis'],
        statsData['Lainnya']
    ];
    const totalDiterimaForChart = dataValues.reduce((sum, value) => sum + value, 0);
    const hasDataForChart = totalDiterimaForChart > 0;

    const prodiPieChartCanvas = document.getElementById('prodiPieChart');
    const chartParentContainer = prodiPieChartCanvas ? prodiPieChartCanvas.closest('.chart-container') : null;


    if (hasDataForChart && prodiPieChartCanvas) {
        new Chart(prodiPieChartCanvas, {
            type: 'pie',
            data: {
                labels: ['Teknik Informatika', 'Sistem Informasi Bisnis', 'Lainnya/Tidak Terdefinisi'],
                datasets: [{
                    label: 'Mahasiswa Diterima',
                    data: dataValues,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',  // blue-500
                        'rgba(16, 185, 129, 0.8)', // emerald-500
                        'rgba(107, 114, 128, 0.8)' // gray-500
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(107, 114, 128, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.parsed;
                                const percentage = totalDiterimaForChart > 0 ? ((value / totalDiterimaForChart) * 100).toFixed(1) + '%' : '0%';
                                if (value !== null) {
                                    label += value + ' (' + percentage + ')';
                                }
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            if (value === 0) return '';
                            let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = (value*100 / sum).toFixed(1)+"%";
                            return percentage;
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 10
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Mengaktifkan plugin datalabels untuk chart ini
        });
    } else if (chartParentContainer) {
        // Jika tidak ada data, tampilkan pesan di dalam container chart
        chartParentContainer.innerHTML = '<p class="text-sm text-gray-500 text-center h-full flex items-center justify-center">Tidak ada data mahasiswa diterima untuk ditampilkan pada chart.</p>';
    }

    // Script untuk dropdown profile (jika variabel $profileBtn dan $profileDropdown ada)
    @if (isset($profileBtn) && isset($profileDropdown))
        const profileBtn = document.getElementById('{{ $profileBtn }}');
        const profileDropdown = document.getElementById('{{ $profileDropdown }}');

        if (profileBtn && profileDropdown) {
            profileBtn.addEventListener('click', () => {
                profileDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        }
    @endif
        </body>
</html>