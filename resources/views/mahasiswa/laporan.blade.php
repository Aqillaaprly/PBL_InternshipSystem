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
@include('mahasiswa.template.navbar')
<!-- Main Content -->
<main class="max-w-screen-xl mx-auto px-8 py-12 mt-6 space-y-10">

    <!-- Tabel Data -->
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center pb-4">
            <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Pengisian Laporan Magang</h1>
            <div class="flex space-x-3">
                <input type="text" placeholder="Search" class="border border-gray-300 rounded px-4 py-2" />
                <button class="border border-gray-300 px-4 py-2 rounded hover:bg-gray-200 transition">Filter</button>
                <button id="openFormBtn" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">+ Tambah</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="dataTable" class="min-w-full text-sm text-center">
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
                <tbody id="tableBody">
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
                            <button class="editBtn bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200 transition">Edit</button>
                            <button class="deleteBtn bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200 transition">Delete</button>
                        </td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
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

    <!-- Modal Form -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div id="formContainer" class="bg-white p-6 rounded-lg w-full max-w-xl relative">
            <h2 class="text-xl font-semibold mb-4">Tambah Data Laporan</h2>
            <form id="absensiForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" required class="w-full border border-gray-300 rounded px-4 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="nama" required class="w-full border border-gray-300 rounded px-4 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Kelas</label>
                    <input type="text" name="kelas" required class="w-full border border-gray-300 rounded px-4 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Perusahaan</label>
                    <input type="text" name="perusahaan" required class="w-full border border-gray-300 rounded px-4 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Kegiatan</label>
                    <input type="text" name="kegiatan" required class="w-full border border-gray-300 rounded px-4 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Foto</label>
                    <input type="file" name="foto" accept="image/*" class="w-full border border-gray-300 rounded px-4 py-2" />
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
                    <button type="button" id="cancelBtn" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

</main>

<!-- Footer -->
@include('mahasiswa.template.footer')

<script>
    document.getElementById('openFormBtn').addEventListener('click', () => {
        document.getElementById('modal').classList.remove('hidden');
    });

    document.getElementById('cancelBtn').addEventListener('click', () => {
        document.getElementById('modal').classList.add('hidden');
    });

    document.getElementById('modal').addEventListener('click', (e) => {
        if (e.target.id === 'modal') {
            document.getElementById('modal').classList.add('hidden');
        }
    });

    document.getElementById('absensiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const row = document.createElement('tr');
        row.classList.add('border-b');
        row.innerHTML = `
            <td class="px-4 py-3">#</td>
            <td class="px-4 py-3">${formData.get('email')}</td>
            <td class="px-4 py-3">${formData.get('nama')}</td>
            <td class="px-4 py-3">${formData.get('kelas')}</td>
            <td class="px-4 py-3">${formData.get('perusahaan')}</td>
            <td class="px-4 py-3">${formData.get('kegiatan')}</td>
            <td class="px-4 py-3">
                <img src="https://tp-fst.ut.ac.id/wp-content/uploads/2024/10/magang-di-tsn-prodi-tp-1.jpg" alt="Foto" class="mx-auto rounded w-16 h-16 object-fit" />
            </td>
            <td class="px-4 py-3 space-x-1">
                <button class="editBtn bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200 transition">Edit</button>
                <button class="deleteBtn bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200 transition">Delete</button>
            </td>
        `;
        document.getElementById('tableBody').appendChild(row);
        document.getElementById('modal').classList.add('hidden');
        this.reset();
    });

    document.getElementById('tableBody').addEventListener('click', function(e) {
        if (e.target.classList.contains('deleteBtn')) {
            e.target.closest('tr').remove();
        } else if (e.target.classList.contains('editBtn')) {
            const cells = e.target.closest('tr').children;
            document.querySelector('#absensiForm [name="email"]').value = cells[1].textContent;
            document.querySelector('#absensiForm [name="nama"]').value = cells[2].textContent;
            document.querySelector('#absensiForm [name="kelas"]').value = cells[3].textContent;
            document.querySelector('#absensiForm [name="perusahaan"]').value = cells[4].textContent;
            document.querySelector('#absensiForm [name="kegiatan"]').value = cells[5].textContent;
            cells[0].parentNode.remove();
            document.getElementById('modal').classList.remove('hidden');
        }
    });
</script>
</body>

</html>
