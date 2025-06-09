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

@include('mahasiswa.template.navbar')

<main class="max-w-screen-xl mx-auto px-8 py-12 mt-6 space-y-10">

    <!-- Header & Actions -->
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center pb-4">
            <h1 class="text-2xl font-bold text-blue-800">Data Pengisian Laporan Magang</h1>
            <div class="flex space-x-3">
                <form method="GET" action="{{ route('mahasiswa.laporan') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="border border-gray-300 rounded px-4 py-2" />
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
                    @if(request('search') || request('filter'))
                    <a href="{{ route('mahasiswa.laporan') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">Reset</a>
                    @endif
                </form>
                <button id="openFormBtn" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">+ Tambah</button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Keterangan</th>
                    <th class="px-4 py-3">Kegiatan</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($aktivitas as $index => $item)
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $item->tanggal }}</td>
                    <td class="px-4 py-3">{{ $item->jenis_aktivitas }}</td>
                    <td class="px-4 py-3">{{ $item->catatan }}</td>
                    <td class="px-4 py-3">
                        @if($item->foto->isNotEmpty())
                        @foreach($item->foto as $foto)
                        <img src="{{ asset('storage/' . $foto->path) }}" class="w-16 h-16 mx-auto rounded object-cover" alt="Bukti Foto">
                        @endforeach
                        @else
                        <span class="text-gray-400 italic">No image</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('mahasiswa.aktivitas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($aktivitas->hasPages())
        <div class="flex justify-end mt-4">
            {{ $aktivitas->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-xl relative">
            <h2 class="text-xl font-semibold mb-4">Tambah Data Laporan</h2>
            <form action="{{ route('mahasiswa.aktivitas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="mahasiswa_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="pembimbing_id" value="1"> {{-- Replace with dynamic ID if needed --}}

                <div>
                    <label class="block text-sm font-medium">Tanggal</label>
                    <input type="date" name="tanggal" required class="w-full border px-4 py-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium">Jenis Aktivitas</label>
                    <input type="text" name="jenis_aktivitas" required class="w-full border px-4 py-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium">Catatan</label>
                    <textarea name="catatan" required class="w-full border px-4 py-2 rounded"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Foto Bukti</label>
                    <input type="file" name="foto" accept="image/*" class="w-full border px-4 py-2 rounded">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    <button type="button" id="cancelBtn" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('mahasiswa.template.footer')

<script>
    const modal = document.getElementById('modal');
    const openBtn = document.getElementById('openFormBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', (e) => {
        if (e.target.id === 'modal') modal.classList.add('hidden');
    });
</script>

</body>
</html>
