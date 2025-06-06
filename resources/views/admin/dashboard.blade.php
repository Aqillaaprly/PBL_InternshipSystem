<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard SIMMAGANG - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Tambahkan CDN Chart.js dan plugin datalabels --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            /* Anda bisa mengatur tinggi dan lebar default di sini atau membiarkannya responsif */
            /* height: 300px; */
            /* width: 100%; */ /* Biarkan lebar responsif */
        }
        /* Style untuk kualifikasi list jika admin.job di-include dan membutuhkannya */
        .kualifikasi-list ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            padding-left: 0;
        }
        .kualifikasi-list li {
            margin-bottom: 0.25rem;
        }
    </style>
</head>

<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <div class="flex flex-col min-h-screen">
        <main class="p-6 max-w-7xl mx-auto w-full mt-16">
            <div class="w-full mb-6">
                <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg"
                     alt="Header"
                     class="w-full h-48 object-cover rounded-b-lg shadow" />
            </div>

            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-blue-900 font-poppins">Selamat Datang {{ Auth::user()->username ?? 'Admin' }}</h1>
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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('admin.perusahaan.index') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        <p class="text-2xl font-bold text-blue-600">{{ $jumlahPerusahaan ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Perusahaan</p>
                    </div>
                </a>
                <a href="{{ route('admin.lowongan.index') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        <p class="text-2xl font-bold text-blue-600">{{ $jumlahLowongan ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Lowongan</p>
                    </div>
                </a>
                <a href="{{ route('admin.pendaftar.index') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                         <p class="text-2xl font-bold text-blue-600">{{ $jumlahPendaftar ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Pendaftar</p>
                    </div>
                </a>
            </div>
            
            {{-- Bagian ini akan di-include dari resources/views/admin/job.blade.php --}}
            {{-- Pastikan controller mengirimkan variabel $companies yang berisi Collection perusahaan --}}
            @if(view()->exists('admin.job'))
                @include('admin.job') 
            @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Perhatian:</p>
                    <p>View <code>admin.job</code> tidak ditemukan. Bagian rekomendasi lowongan tidak dapat ditampilkan.</p>
                </div>
            @endif
          

            <div class="bg-white p-6 rounded-xl shadow mb-6 hover:bg-blue-50 transition">
                <h2 class="font-semibold text-gray-700 mb-4">Absensi Terkini (Contoh)</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-500 uppercase border-b">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Waktu</th>
                                <th class="px-4 py-2">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">Andi Pratama</td>
                                <td class="px-4 py-2">2025-05-18</td>
                                <td class="px-4 py-2">08:03</td>
                                <td class="px-4 py-2">Masuk</td>
                            </tr>
                            {{-- Anda bisa menambahkan data absensi dummy lainnya atau mengambil dari DB --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
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


        </main>
    </div>
    @include('admin.template.footer')

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
});
</script>
</body>
</html>