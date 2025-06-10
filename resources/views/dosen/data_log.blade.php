<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Log Bimbingan - Dosen STRIDEUP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('dosen.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Log Bimbingan</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('dosen.data_log') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
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
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Periode Magang</th>
                            <th class="px-5 py-3">Status Bimbingan</th>
                            <th class="px-5 py-3">Pembimbing</th>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($bimbingans as $index => $bimbingan)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4">{{ $bimbingans->firstItem() + $index }}</td>
                                <td class="px-5 py-4">
                                    {{ $bimbingan->mahasiswa->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4">{{ $bimbingan->periode_magang ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex item-center justify-center space-x-1">
                                        <a href="{{ route('dosen.data_log.show', $bimbingan->status_bimbingan) }}" class="bg-green-100 text-green-600 text-xs font-medium px-3 py-1 rounded hover:bg-green-200">
                                            Aktif
                                        </a>
                                    </div>
                                </td>
                                <td class="px-5 py-4">{{ $bimbingan->pembimbing->nama_lengkap ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->company->nama_perusahaan ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex item-center justify-center space-x-1">
                                        <a href="{{ route('dosen.data_log.show', $bimbingan->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Show Log
                                        </a>
                                        <a href="{{ route('dosen.log_bimbingan.create', $bimbingan->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Add Log
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada bimbingan ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data log bimbingan.
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
    @include('dosen.template.footer')
</body>
</html>