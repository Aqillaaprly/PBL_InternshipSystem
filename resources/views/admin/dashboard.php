<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-800">

    <!-- Header/Navbar -->
    <?php
    include('../navbar_admin.php')
    ?>

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen">
        <main class="p-6 max-w-7xl mx-auto w-full">
          <div class="w-full">
               <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg" 
           alt="Foto" 
           class="w-full h-48 object-cover rounded-b-lg shadow" />
           </div> 
            
            <!-- Heading Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="my-3 col-span-3 text-center">
                    <h1 class="text-3xl font-bold text-blue-900">Selamat Datang di Dashboard SIMMAGANG</h1>
                    <p class="text-sm text-gray-600">Sistem Informasi Manajemen Magang Mahasiswa</p>
                </div>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <!-- Card Magang Pusat -->
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Pusat</h2>
                    <p class="text-gray-700 mb-3">
                        Magang Pusat adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.
                    </p>
                    <p class="text-gray-700">
                        Perusahaan-perusahaan yang akan dijadikan tempat magang sudah terorganisir oleh kampus.
                    </p>
                </div>

                <!-- Card Magang Mandiri -->
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Mandiri</h2>
                    <p class="text-gray-700 mb-3">
                        Magang Mandiri adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.
                    </p>
                    <p class="text-gray-700">
                        Mahasiswa mencari sendiri perusahaan-perusahaan yang akan dijadikan untuk penerapan keilmuan atau kompetensi.
                    </p>
                </div>
            </div>

            <!-- Info Boxes -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <a href="perusahaan.php" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        <p class="text-2xl font-bold text-blue-600">10</p>
                        <p class="text-sm text-gray-700 mt-1">Perusahaan</p>
                    </div>
                </a>
                <a href="lowongan.php" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        <p class="text-2xl font-bold text-blue-600">10</p>
                        <p class="text-gray-600">Lowongan</p>
                    </div>
                </a>
                <a href="pendaftar.php" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        <p class="text-2xl font-bold text-blue-600">10</p>
                        <p class="text-gray-600">Pendaftar</p>
                    </div>
                </a>

            </div>

            <?php include('jobcard.php') ?>
        

            <!-- Recent Absence Table -->
            <div class="bg-white p-6 rounded-xl shadow mb-6 hover:bg-blue-50 transition cursor-pointer">
                <h2 class="font-semibold text-gray-700 mb-4">Recent Absence</h2>
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
                            <tr class="border-b">
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">Budi Santoso</td>
                                <td class="px-4 py-2">2025-05-18</td>
                                <td class="px-4 py-2">08:10</td>
                                <td class="px-4 py-2">Masuk</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">3</td>
                                <td class="px-4 py-2">Citra Lestari</td>
                                <td class="px-4 py-2">2025-05-18</td>
                                <td class="px-4 py-2">08:12</td>
                                <td class="px-4 py-2">Masuk</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>

            <!-- Program Studi and Recent User -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10 hover:bg-blue-50 transition cursor-pointer">
                <!-- Program Studi -->
                <div class="bg-white p-6 rounded-xl shadow hover:bg-blue-50 transition">
                    <h2 class="font-semibold text-gray-700 mb-4">Program Studi</h2>

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700">Teknik Informatika</p>
                        <p class="text-xs text-gray-500 mb-1">Diterima</p>
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: 75%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mb-1">Ditolak</p>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gray-400 h-3 rounded-full" style="width: 25%"></div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-700">Sistem Informasi Bisnis</p>
                        <p class="text-xs text-gray-500 mb-1">Diterima</p>
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: 75%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mb-1">Ditolak</p>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gray-400 h-3 rounded-full" style="width: 25%"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Active User -->
                <div class="bg-white p-6 rounded-xl shadow hover:bg-blue-50 transition cursor-pointer">
                    <h2 class="font-semibold text-gray-700 mb-4">Recent Active User</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-500 uppercase border-b">
                                <tr>
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Nama</th>
                                    <th class="px-4 py-2">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-4 py-2">1</td>
                                    <td class="px-4 py-2">Andi Pratama</td>
                                    <td class="px-4 py-2">Mahasiswa</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-4 py-2">2</td>
                                    <td class="px-4 py-2">Dewi Kurnia</td>
                                    <td class="px-4 py-2">Perusahaan</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2">3</td>
                                    <td class="px-4 py-2">Fajar Setiawan</td>
                                    <td class="px-4 py-2">Admin</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <?php include('../footer.php') ?>
    </div>

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
    </script>
</body>

</html>