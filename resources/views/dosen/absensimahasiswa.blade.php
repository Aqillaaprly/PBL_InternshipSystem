<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rekap Absensi Mahasiswa Bimbingan - Dosen STRIDEUP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">

    @include('dosen.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <h1 class="text-2xl font-bold text-blue-800 mb-6">Rekap Akumulasi Absensi Mahasiswa Bimbingan</h1>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center border border-gray-200 rounded">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3 border-b border-gray-300">No</th>
                            <th class="px-5 py-3 border-b border-gray-300">Nama Mahasiswa</th>
                            <th class="px-5 py-3 border-b border-gray-300">Pembimbing</th>
                            <th class="px-5 py-3 border-b border-gray-300">Perusahaan</th>
                            <th class="px-5 py-3 border-b border-gray-300">Periode</th>
                            <th class="px-5 py-3 border-b border-gray-300">Total Hadir</th>
                            <th class="px-5 py-3 border-b border-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($data as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-4">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4">{{ $item->mahasiswa->name ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->pembimbing->nama ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->company->nama ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->periode_magang }}</td>
                            <td class="px-5 py-4">{{ $item->total_hadir }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('dosen.absensi.show', $item->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Show</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                Belum ada data absensi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    @include('dosen.template.footer')

</body>
</html>