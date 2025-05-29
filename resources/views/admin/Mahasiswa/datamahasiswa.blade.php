<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Mahasiswa - SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-800">

    <!-- Header/Navbar -->
    
    @include('admin.template.navbar')

    <!-- Main Content -->
    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-6">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Mahasiswa</h1>
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
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIM</th>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <tr class="border-b">
                                <td class="px-5 py-3"><?= $i ?></td>
                                <td class="px-5 py-3">2341123123</td>
                                <td class="px-5 py-3">Nama <?= $i ?></td>
                                <td class="px-5 py-3">Mahasiswa@gmail.com</td>
                                <td class="px-5 py-3">TI 2L</td>
                                <td class="px-5 py-3 space-x-1">
                                    <button class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                        Show
                                    </button>
                                    <button class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">
                                        Edit
                                    </button>
                                    <button class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">
                                        Delete
                                    </button>
                                </td>

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

    <!-- Footer -->
 @include('admin.template.footer')
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