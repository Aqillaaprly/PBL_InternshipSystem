<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pendaftar - perusahaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/perusahaan_style.css') }}"> --}}
</head>
<body class="bg-blue-50 text-gray-800">
@include('perusahaan.template.navbar')
    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-blue-800">Manajemen Pendaftar Magang</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('perusahaan.aktivitas_magang') }}" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="border border-gray-300 rounded px-4 py-2 text-sm">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded text-sm">Cari</button>
                    </form>
                    {{-- Tombol Filter bisa ditambahkan di sini jika diperlukan --}}
                    {{-- <a href="{{ route('perusahaan.pendaftar.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700 whitespace-nowrap">+ Tambah Pendaftar</a> --}}
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Lowongan</th>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">Tanggal Daftar</th>
                            <th class="px-5 py-3">Status Lamaran</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($pendaftars as $index => $pendaftar)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-3">{{ $pendaftars->firstItem() + $index }}</td>
                                <td class="px-5 py-3">{{ $pendaftar->user->name ?? ($pendaftar->user->username ?? 'N/A') }}</td>
                                <td class="px-5 py-3">{{ $pendaftar->lowongan->judul ?? 'N/A' }}</td>
                                <td class="px-5 py-3">{{ $pendaftar->lowongan->company->nama_perusahaan ?? 'N/A' }}</td>
                                <td class="px-5 py-3">{{ $pendaftar->tanggal_daftar ? \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d M Y') : 'N/A' }}</td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                        @if ($pendaftar->status_lamaran == 'Diterima') bg-green-100 text-green-700
                                        @elseif ($pendaftar->status_lamaran == 'Ditolak') bg-red-100 text-red-700
                                        @elseif ($pendaftar->status_lamaran == 'Ditinjau') bg-indigo-100 text-indigo-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $pendaftar->status_lamaran }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Show Button - This is the new addition --}}
                                        <a href="{{ route('perusahaan.pendaftar.show', $pendaftar->id) }}" 
                                           class="text-xs bg-sky-100 text-sky-600 hover:bg-sky-200 px-3 py-1 rounded transition-colors duration-200 flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>Detail</span>
                                        </a>
                                        
                                        {{-- Uncomment these if you want to add edit and delete functionality later --}}
                                        {{-- <a href="{{ route('perusahaan.pendaftar.edit', $pendaftar->id) }}" class="text-xs bg-yellow-100 text-yellow-600 hover:bg-yellow-200 px-3 py-1 rounded">Ubah</a> --}}
                                        {{-- <form action="{{ route('perusahaan.pendaftar.destroy', $pendaftar->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftar ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-3 py-1 rounded">Hapus</button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-3 text-center text-gray-500">
                                    @if(request('search'))
                                        Tidak ada pendaftar ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data pendaftar.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pendaftars->hasPages())
                <div class="mt-6">
                    {{ $pendaftars->appends(request()->query())->links() }} {{-- Menampilkan link paginasi dan menjaga parameter query --}}
                </div>
            @endif
        </div>
    </main>
@include('perusahaan.template.footer')
</body>
</html>