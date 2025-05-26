<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pendaftar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f0f6ff]">
    <?php
    include('../navbar_admin.php')
    ?>

<main class="max-w-screen-xl mx-auto px-8 py-12 mt-6">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Pendaftar</h1>
            <div class="flex space-x-3">
                <input type="text" placeholder="Search" class="border border-gray-300 rounded px-4 py-2" />
                <button class="border border-gray-300 px-4 py-2 rounded">Filter</button>
                <button class="bg-blue-600 text-white px-5 py-2 rounded">+ Tambah</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Program Studi</th>
                    <th class="px-5 py-3">Perusahaan</th>
                    <th class="px-5 py-3">Dosen Pembimbing</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <tr class="border-b">
                        <td class="px-5 py-3"><?= $i ?></td>
                        <td class="px-5 py-3">Nama <?= $i ?></td>
                        <td class="px-5 py-3">Teknik Informatika</td>
                        <td class="px-5 py-3">PT. Contoh</td>
                        <td class="px-5 py-3">Dosen A</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full
                                <?php echo ($i % 3 == 0) ? 'bg-red-100 text-red-500' : (($i % 3 == 1) ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600'); ?>">
                              <?php echo ($i % 3 == 0) ? 'Rejected' : (($i % 3 == 1) ? 'Pending' : 'Approved'); ?>
                            </span>
                        </td>
                        <td class="px-5 py-3">...</td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>
        <div class="flex justify-between items-center mt-6">
            <p class="text-sm text-gray-500">Page</p>
            <div class="space-x-2">
                <button class="px-3 py-1 border rounded">1</button>
                <button class="px-3 py-1 border rounded">2</button>
                <button class="px-3 py-1 border rounded">3</button>
            </div>
            <p class="text-sm text-gray-500">Result</p>
        </div>
    </div>
</main>

</body>
</html>
