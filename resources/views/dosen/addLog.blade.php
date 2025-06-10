<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tambah Log Bimbingan - {{ $bimbingan->mahasiswa->name ?? $bimbingan->mahasiswa->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800 flex flex-col min-h-screen">

    @include('dosen.template.navbar')

    <main class="flex-grow max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h1 class="text-xl font-semibold text-blue-800">Tambah Log Bimbingan</h1>
                <a href="{{ route('dosen.data_log') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Log Mahasiswa</a>
            </div>

            <form action="{{ route('dosen.log_bimbingan.store', $bimbingan->id) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Metode Bimbingan</label>
                    <input type="text" name="metode_bimbingan" required class="w-full border rounded px-3 py-2" placeholder="Contoh: WhatsApp / Zoom / Tatap Muka">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Waktu Bimbingan</label>
                    <input type="datetime-local" name="waktu_bimbingan" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Topik Bimbingan</label>
                    <textarea name="topik_bimbingan" required class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Deskripsi</label>
                    <textarea name="deskripsi" required class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nilai</label>
                    <input type="number" name="nilai" min="0" max="100" required class="w-full border rounded px-3 py-2" placeholder="Contoh: 80">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Komentar</label>
                    <textarea name="komentar" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    <a href="{{ route('dosen.data_log') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Batal</a>
                </div>
            </form>
        </div>
    </main>

    @include('dosen.template.footer')
</body>
</html>