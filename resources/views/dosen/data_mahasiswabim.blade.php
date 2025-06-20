<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Log Bimbingan Magang - Dosen STRIDEUP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('dosen.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Log Bimbingan Magang</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('dosen.data_mahasiswabim') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIM..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIM</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Program Studi</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Periode Magang</th>
                            <th class="px-5 py-3">Tanggal Mulai</th>
                            <th class="px-5 py-3">Tanggal Selesai</th>
                            <th class="px-5 py-3">Status Bimbingan</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($bimbingans as $index => $bimbingan)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4">{{ $bimbingans->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->mahasiswa->detailMahasiswa->nim ?? '-' }}</td>
                                <td class="px-5 py-4 text-left">{{ $bimbingan->mahasiswa->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-left">{{ $bimbingan->mahasiswa->email ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->mahasiswa->detailMahasiswa->program_studi ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->mahasiswa->detailMahasiswa->kelas ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->periode_magang ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->tanggal_mulai ? \Carbon\Carbon::parse($bimbingan->tanggal_mulai)->format('d-m-Y') : '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->tanggal_selesai ? \Carbon\Carbon::parse($bimbingan->tanggal_selesai)->format('d-m-Y') : '-' }}</td>
                                <td class="px-5 py-4">
                                    @if ($bimbingan->status_bimbingan == 'Aktif')
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                                    @elseif ($bimbingan->status_bimbingan == 'Selesai')
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Selesai</span>
                                    @elseif ($bimbingan->status_bimbingan == 'Dibatalkan')
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex item-center justify-center space-x-1">
                                        <a href="{{ route('dosen.mahasiswa.show', $bimbingan->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Show
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada bimbingan ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data bimbingan magang.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bimbingans->hasPages())
                <div class="mt-6">
                    {{ $bimbingans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>