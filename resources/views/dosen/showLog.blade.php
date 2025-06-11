<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Log Bimbingan - {{ $mahasiswa->name ?? $mahasiswa->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800 flex flex-col min-h-screen">
    @include('dosen.template.navbar')

    <main class="flex-grow max-w-5xl mx-auto px-4 py-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h1 class="text-xl font-semibold text-blue-800">Detail Log Bimbingan Skripsi/TA Mahasiswa</h1>
                <a href="{{ route('dosen.data_log') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Log Mahasiswa</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                <div><strong>Mahasiswa:</strong> {{ $mahasiswa->username }}</div>
                <div><strong>Nama Mahasiswa:</strong> {{ $mahasiswa->name }}</div>
                <div><strong>Prodi:</strong> {{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</div>
                <div><strong>Status:</strong>
                    <span class="inline-block px-2 py-0.5 bg-green-100 text-green-600 rounded text-xs">Aktif</span>
                </div>
                <div><strong>Metode Bimbingan:</strong> WhatsApp</div>
                <div><strong>Waktu Bimbingan:</strong> 2025-05-19 11:47:00</div>
                <div class="md:col-span-2"><strong>Topik Bimbingan:</strong> Diskusi terkait Data Pengujian yang Didapatkan dari Mitra</div>
                <div class="md:col-span-2"><strong>Deskripsi:</strong> Deskripsi Bimbingan</div>
            </div>

            <div class="mb-4">
                <strong>Status Bimbingan:</strong>
                <span class="inline-flex items-center text-green-600 font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Log Bimbingan Diterima
                </span>
            </div>

            <div class="border rounded-lg overflow-hidden mb-4">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border">No</th>
                            <th class="py-2 px-4 border">NIM</th>
                            <th class="py-2 px-4 border">Nama Mahasiswa</th>
                            <th class="py-2 px-4 border">Nilai</th>
                            <th class="py-2 px-4 border">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white">
                            <td class="py-2 px-4 border">1</td>
                            <td class="py-2 px-4 border">{{ $mahasiswa->username }}</td>
                            <td class="py-2 px-4 border">{{ $mahasiswa->name }}</td>
                            <td class="py-2 px-4 border w-20">
                                <input type="number" name="nilai" class="w-full border rounded px-2 py-1" value="80">
                            </td>
                            <td class="py-2 px-4 border">
                                <input type="text" name="komentar" class="w-full border rounded px-2 py-1">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                <button class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Keluar</button>
            </div>

            <div class="text-xs text-gray-500 mt-6 border-t pt-3">
                <p><strong>Nilai</strong> merupakan pemberian nilai untuk tiap individu mahasiswa atas proses bimbingan. <strong>Nilai tidak ditampilkan di sisi mahasiswa.</strong></p>
                <p><strong>Komentar</strong> merupakan komentar mengenai masing-masing individu mahasiswa saat proses bimbingan.</p>
            </div>
        </div>
    </main>

    @include('dosen.template.footer')
</body>
</html>