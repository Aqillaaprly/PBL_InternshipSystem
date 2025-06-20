<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengisian Laporan Magang - SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-800">
@include('mahasiswa.template.navbar')

<main class="max-w-screen-xl mx-auto px-8 py-12 mt-6 space-y-10">
    <!-- Header & Actions -->
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center pb-4">
            <h1 class="text-2xl font-bold text-blue-800">Data Laporan Magang</h1>
            <div class="flex space-x-3">
                <form method="GET" action="{{ route('mahasiswa.laporan') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kegiatan..."
                           class="border border-gray-300 rounded px-4 py-2" />
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('mahasiswa.laporan') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                        Reset
                    </a>
                    @endif
                </form>
                <button id="openFormBtn"
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                    + Tambah Laporan
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Jam Kerja</th>
                    <th class="px-4 py-3">Deskripsi Kegiatan</th>
                    <th class="px-4 py-3">Bukti Kegiatan</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($aktivitas as $index => $item)
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $index + $aktivitas->firstItem() }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $item->jam_kerja ? \Carbon\Carbon::parse($item->jam_kerja)->format('H:i') : '-' }}</td>
                    <td class="px-4 py-3">{{ $item->deskripsi_kegiatan }}</td>
                    <td class="px-4 py-3">
                        @if($item->bukti_kegiatan)
                        <img src="{{ asset('storage/' . $item->bukti_kegiatan) }}"
                             class="w-16 h-16 mx-auto rounded object-cover cursor-pointer"
                             alt="Bukti Kegiatan"
                             onclick="window.open('{{ asset('storage/' . $item->bukti_kegiatan) }}', '_blank')">
                        @else
                        <span class="text-gray-400 italic">Tidak ada bukti</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('mahasiswa.laporan.destroy', $item->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs hover:bg-red-200 transition">
                                Hapus
                            </button>
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
            <h2 class="text-xl font-semibold mb-4">Tambah Laporan Kegiatan</h2>
            <form action="{{ route('mahasiswa.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" required
                           class="w-full border border-gray-300 px-4 py-2 rounded focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('tanggal', now()->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Kerja</label>
                    <input type="time" name="jam_kerja" required
                           class="w-full border border-gray-300 px-4 py-2 rounded focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('jam_kerja', now()->format('H:i')) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" required rows="3"
                              class="w-full border border-gray-300 px-4 py-2 rounded focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi_kegiatan') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Kegiatan (Foto)</label>
                    <input type="file" name="bukti_kegiatan" accept="image/*"
                           class="w-full border border-gray-300 px-4 py-2 rounded focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (Max: 2MB)</p>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        Simpan
                    </button>
                    <button type="button" id="cancelBtn"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Batal
                    </button>
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
