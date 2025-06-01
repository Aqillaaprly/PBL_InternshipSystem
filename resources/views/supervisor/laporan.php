<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengisian Laporan Magang - SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-blue-50 text-gray-800">

    <!-- Header/Navbar -->
    <?php include('template/navbar_pembimbing.php') ?>

    <!-- Main Content -->
    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-6 space-y-10">

        <!-- Tabel Data -->
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Pengisian Laporan Magang</h1>
                <div class="flex space-x-3">
                    <input type="text" placeholder="Search" class="border border-gray-300 rounded px-4 py-2" />
                    <button class="border border-gray-300 px-4 py-2 rounded">Filter</button>
                    <button class="bg-blue-600 text-white px-5 py-2 rounded">+ Tambah</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">Perusahaan</th>
                            <th class="px-4 py-3">Kegiatan</th>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <tr class="border-b">
                                <td class="px-4 py-3"><?= $i ?></td>
                                <td class="px-4 py-3">mhs<?= $i ?>@email.com</td>
                                <td class="px-4 py-3">Mahasiswa <?= $i ?></td>
                                <td class="px-4 py-3">TI-<?= $i ?>A</td>
                                <td class="px-4 py-3">Perusahaan <?= $i ?></td>
                                <td class="px-4 py-3">Mengerjakan proyek <?= $i ?></td>
                                <td class="px-4 py-3">
                                    <img src="https://tp-fst.ut.ac.id/wp-content/uploads/2024/10/magang-di-tsn-prodi-tp-1.jpg" alt="Foto" class="mx-auto rounded w-16 h-16 object-fit" />
                                </td>
                                <td class="px-4 py-3 space-x-1">
                                    <button class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">Show</button>
                                    <button class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</button>
                                    <button class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Delete</button>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

            <!-- Contoh Pagination -->
            <div class="flex justify-end mt-4">
                <nav class="inline-flex space-x-1">
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">«</button>
                    <button class="px-3 py-1 border rounded bg-blue-100 text-blue-700">1</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">2</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">3</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">»</button>
                </nav>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h2 class="text-xl font-bold text-blue-800 mb-4">Statistik Mahasiswa</h2>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Pie Chart 1 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow w-full col-span-1">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 text-center">Distribusi Perusahaan</h3>
                    <canvas id="pieChart" class="w-full h-48"></canvas>
                </div>
                <!-- Bar Chart -->
                <div class="bg-gray-50 p-4 rounded-lg shadow w-full col-span-2">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 text-center">Lokasi Magang</h3>
                    <canvas id="activityChart" class="w-full h-64"></canvas>
                </div>
                <!-- Pie Chart 2 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow w-full col-span-1">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 text-center">Distribusi Absensi</h3>
                    <canvas id="pieChart2" class="w-full h-48"></canvas>
                </div>
            </div>
        </div>


    </main>

    <!-- Footer -->
    <?php include('../footer.php') ?>

    <!-- Dropdown JS -->
    <script>
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        profileBtn?.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });


        // Pie Chart 1
        // Pie Chart Distribusi Perusahaan
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['TI', 'Non TI'],
                datasets: [{
                    data: [35, 15],
                    backgroundColor: ['#3B82F6', '#F59E0B', '#10B981', '#EF4444', '#8B5CF6'],
                }]
            }
        });


        // Pie Chart 2
        new Chart(document.getElementById('pieChart2'), {
            type: 'pie',
            data: {
                labels: ['Aktif', 'Nonaktif'],
                datasets: [{
                    data: [88, 12],
                    backgroundColor: ['#10B981', '#EF4444'],
                }]
            }
        });

        // Lokasi Magang
        new Chart(document.getElementById('activityChart'), {
            type: 'bar',
            data: {
                labels: ['Dalam Kota', 'Luar Kota', 'Luar Provinsi', 'Luar Negeri'],
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: [20, 25, 15, 5],
                    backgroundColor: '#6366F1'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
