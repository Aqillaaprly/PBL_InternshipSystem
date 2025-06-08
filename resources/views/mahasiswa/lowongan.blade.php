<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Lowongan - Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-[#f0f6ff]">
@include('mahasiswa.template.navbar')

<main class="max-w-screen-xl mx-auto px-4 md:px-8 py-12 mt-16">
    <div class="bg-white p-6 md:p-8 rounded-xl shadow">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h1 class="text-2xl font-semibold text-gray-800">Manajemen Lowongan</h1>
            <div class="flex flex-col md:flex-row w-full md:w-auto gap-3">
                <form method="GET" action="{{ route('mahasiswa.lowongan.index') }}" class="flex">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari lowongan..."
                        class="border border-gray-300 rounded-l px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="flex gap-2">
                    <select class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Tipe</option>
                        <option value="Penuh Waktu">Penuh Waktu</option>
                        <option value="Paruh Waktu">Paruh Waktu</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Internship">Internship</option>
                    </select>
                    <select class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Ditutup">Ditutup</option>
                    </select>
                </div>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
            {{ session('error') }}
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Judul Lowongan</th>
                    <th class="px-5 py-3">Perusahaan</th>
                    <th class="px-5 py-3">Tipe</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3">Tanggal Tutup</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lowongans as $index => $lowongan)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-5 py-4">{{ $lowongans->firstItem() + $index }}</td>
                    <td class="px-5 py-4 font-medium">{{ $lowongan->judul }}</td>
                    <td class="px-5 py-4">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</td>
                    <td class="px-5 py-4">{{ $lowongan->tipe }}</td>
                    <td class="px-5 py-4">{{ $lowongan->lokasi }}</td>
                    <td class="px-5 py-4">
                        {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d M Y') }}
                        @if(\Carbon\Carbon::parse($lowongan->tanggal_tutup)->isPast())
                        <span class="text-red-500 text-xs ml-1"><i class="fas fa-exclamation-circle"></i></span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($lowongan->status == 'Aktif') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $lowongan->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            @if($lowongan->status == 'Aktif' && !\Carbon\Carbon::parse($lowongan->tanggal_tutup)->isPast())
                            <a href="{{ route('mahasiswa.apply.from.lowongan', ['lowonganId' => $lowongan->id]) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 text-xs rounded flex items-center gap-1 transition duration-200">
                                <i class="fas fa-paper-plane text-xs"></i> Apply
                            </a>
                            @else
                            <span class="text-gray-400 text-xs italic">Closed</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-4 text-center text-gray-500">
                        @if(request('search'))
                        Tidak ada lowongan ditemukan untuk pencarian "{{ request('search') }}".
                        @else
                        Belum ada data lowongan.
                        @endif
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($lowongans->hasPages())
        <div class="mt-6">
            {{ $lowongans->links() }}
        </div>
        @endif
    </div>
</main>

@include('mahasiswa.template.footer')
</body>
</html>
