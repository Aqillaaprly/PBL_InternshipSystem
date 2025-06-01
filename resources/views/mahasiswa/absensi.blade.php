<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SIMMAGANG Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 pt-20 relative">

@include('mahasiswa.template.navbar')

<div class="max-w-7xl mx-auto p-4">
    <div class="bg-white rounded-xl shadow-md p-6 relative">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Data Absensi</h1>
                <span class="text-sm text-gray-500">› Mahasiswa TI</span>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" placeholder="Search" class="border rounded px-3 py-2 text-sm"/>
                <button class="border rounded px-3 py-2 text-sm flex items-center gap-1">Filter ⚙️</button>
                <button id="openModalBtn" class="bg-blue-900 text-white px-4 py-2 rounded text-sm font-medium">Add Absensi</button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="absensiTable" class="min-w-full text-sm text-left">
                <thead class="border-b text-gray-700">
                <tr>
                    <th class="py-2 px-4 font-semibold">No</th>
                    <th class="py-2 px-4 font-semibold">Tanggal</th>
                    <th class="py-2 px-4 font-semibold">Waktu</th>
                    <th class="py-2 px-4 font-semibold">Deskripsi</th>
                    <th class="py-2 px-4 font-semibold">Bukti Foto</th>
                    <th class="py-2 px-4 font-semibold">Action</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <!-- Dummy rows will be appended here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-end items-center mt-6 text-sm text-gray-600">
            <button class="px-3 py-1 rounded bg-blue-900 text-white">1</button>
            <button class="px-3 py-1 rounded hover:bg-gray-200">2</button>
            <button class="px-3 py-1 rounded hover:bg-gray-200">3</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div id="modalBox" class="bg-white p-6 rounded-xl w-[90%] max-w-md relative shadow-xl">
        <h2 class="text-center text-lg font-semibold mb-4">ABSENSI</h2>

        <form id="absensiForm" class="space-y-4">
            <div>
                <label class="text-sm text-gray-600">TUGAS HARI INI</label>
                <input name="deskripsi" type="text" placeholder="Title for a new assignment" class="w-full px-4 py-2 border rounded-md mt-1"/>
            </div>

            <div>
                <label class="text-sm text-gray-600">PICTURE</label>
                <div class="border-dashed border-2 border-gray-300 rounded-md py-6 text-center text-sm text-gray-500 mt-1">
                    Drag and drop or <span class="text-blue-600 cursor-pointer">browse files</span><br/>
                    <input name="buktiFoto" type="file" class="hidden" />
                    <p class="text-xs mt-1">Size limit: 25MB</p>
                </div>
            </div>

            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-medium px-4 py-2 rounded-full">Save & Publish</button>
        </form>
    </div>
</div>

@include('mahasiswa.template.footer')

<!-- Modal Script -->
<script>
    const openModalBtn = document.getElementById('openModalBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const absensiForm = document.getElementById('absensiForm');
    const tableBody = document.getElementById('tableBody');

    let rowCount = 0;

    openModalBtn.addEventListener('click', () => {
        modalOverlay.classList.remove('hidden');
    });

    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.add('hidden');
        }
    });

    absensiForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(absensiForm);
        const deskripsi = formData.get('deskripsi') || '-';
        const buktiFoto = formData.get('buktiFoto') ? formData.get('buktiFoto').name : '-';

        rowCount++;

        const now = new Date();
        const tanggal = now.toLocaleDateString();
        const waktu = now.toLocaleTimeString();

        const row = `
      <tr class="border-b hover:bg-gray-50">
        <td class="py-2 px-4">${rowCount}</td>
        <td class="py-2 px-4">${tanggal}</td>
        <td class="py-2 px-4">${waktu}</td>
        <td class="py-2 px-4">${deskripsi}</td>
        <td class="py-2 px-4">${buktiFoto}</td>
        <td class="py-2 px-4 text-center">⋯</td>
      </tr>
    `;
        tableBody.insertAdjacentHTML('beforeend', row);
        absensiForm.reset();
        modalOverlay.classList.add('hidden');
    });
</script>

</body>
</html>
