<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Internity</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">

<?php include '../navbar_pembimbing.php'; ?>

<main class="pt-28 px-6 max-w-6xl mx-auto w-full">

    <!-- Hero Section -->
    <div class="bg-white p-6 rounded-xl shadow mb-8 flex flex-col lg:flex-row justify-between items-center space-y-6 lg:space-y-0">
        <div class="max-w-xl">
            <h1 class="text-xl font-semibold text-blue-900 mb-2">
                Lorem ipsum dolor sit amet consectetur. Augue aliquam quis sodales tempor donec.
            </h1>
            <p class="text-md text-blue-700 font-semibold">
                Nulla malesuada dui fermentum rhoncus feugiat ac aliquam.
            </p>
            <p class="text-sm text-gray-500 mt-2">Lorem ipsum dolor sit amet consectetur.</p>
            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                <select class="p-2 border border-gray-300 rounded w-full sm:w-48">
                    <option>Regional</option>
                </select>
                <select class="p-2 border border-gray-300 rounded w-full sm:w-48">
                    <option>Daftar Bimbing</option>
                </select>
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Show Jobs</button>
            </div>
        </div>
        <div class="w-40 h-40 bg-gray-300 rounded-lg"></div>
    </div>

    <!-- Tables -->
    <div class="space-y-6 mb-10">
        <!-- Table 1 -->
        <a href="bimbingan-list.php" class="block">
            <div class="bg-white p-6 rounded-xl shadow mb-6 cursor-pointer hover:shadow-lg transition">
                <h2 class="text-lg font-semibold text-blue-700 mb-4">Daftar Mahasiswa Bimbingan</h2>
                <table class="w-full table-auto text-left border-collapse">
                    <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Deskripsi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500 italic">No Data Available</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </a>

        <!-- Table 2 -->
        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="font-semibold text-lg text-blue-800 mb-3">Absensi Mahasiswa Bimbingan</h2>
            <div class="overflow-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-blue-100 text-blue-800 font-semibold">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Deskripsi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500 italic">
                            No Data Available
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow text-center">
            <p class="text-4xl font-bold text-blue-600">10</p>
            <p class="text-sm text-gray-600 mt-2">Perusahaan</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center">
            <p class="text-4xl font-bold text-blue-600">15</p>
            <p class="text-sm text-gray-600 mt-2">Lowongan</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center">
            <p class="text-4xl font-bold text-blue-600">10</p>
            <p class="text-sm text-gray-600 mt-2">Pendaftar</p>
        </div>
    </div>

</main>

<?php include '../footer.php'; ?>

</body>
</html>
