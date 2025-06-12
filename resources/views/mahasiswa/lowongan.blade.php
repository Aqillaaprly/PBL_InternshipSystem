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
                    @if(request('search') || request('tipe') || request('status'))
                    <a href="{{ route('mahasiswa.lowongan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded ml-2">
                        Reset
                    </a>
                    @endif
                </form>
                <form method="GET" action="{{ route('mahasiswa.lowongan.index') }}" class="flex gap-2">
                    <select name="tipe" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        <option value="Penuh Waktu" @if(request('tipe') == 'Penuh Waktu') selected @endif>Penuh Waktu</option>
                        <option value="Paruh Waktu" @if(request('tipe') == 'Paruh Waktu') selected @endif>Paruh Waktu</option>
                        <option value="Kontrak" @if(request('tipe') == 'Kontrak') selected @endif>Kontrak</option>
                        <option value="Magang" @if(request('tipe') == 'Magang') selected @endif>Magang</option>
                    </select>
                    <select name="status" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Aktif" @if(request('status') == 'Aktif') selected @endif>Aktif</option>
                        <option value="Ditutup" @if(request('status') == 'Ditutup') selected @endif>Ditutup</option>
                    </select>
                </form>
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
        @if (session('info'))
        <div class="mb-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded">
            {{ session('info') }}
        </div>
        @endif

        @if (session()->has('recommended_job_title') && request()->routeIs('mahasiswa.lowongan.index'))
        <div class="mb-6 p-5 bg-indigo-50 border-l-4 border-indigo-400 text-indigo-800 rounded-lg shadow-sm flex items-center justify-between">
            <div>
                <p class="font-semibold text-lg mb-1">Rekomendasi Magang Untukmu!</p>
                <p>Anda sedang melihat lowongan yang direkomendasikan: <span class="font-bold">{{ session('recommended_job_title') }}</span></p>
            </div>
            <a href="{{ route('mahasiswa.lowongan.index') }}" class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200 ease-in-out">
                Lihat Semua Lowongan
            </a>
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
                        @if(request('search') || request('tipe') || request('status'))
                        Tidak ada lowongan ditemukan dengan filter yang dipilih.
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
