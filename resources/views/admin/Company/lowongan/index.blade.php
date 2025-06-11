<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Lowongan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
     /* Ensure table cells do not wrap text */
        .min-w-full th, .min-w-full td {
            white-space: nowrap;
        }
        /* Add horizontal scroll if content overflows */
        .overflow-x-auto {
            overflow-x: auto;
        }
</style>
<body class="bg-[#f0f6ff]">
    @include('admin.template.navbar')

<main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Manajemen Lowongan</h1>
            <div class="flex space-x-3">
                <form method="GET" action="{{ route('admin.lowongan.index') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, perusahaan..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r text-sm -ml-px">Cari</button>
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

         <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
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
                <tbody class="text-gray-600">
                @forelse($lowongans as $index => $lowongan)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-3 text-center">{{ $lowongans->firstItem() + $index }}</td>
                        <td class="px-5 py-3">{{ $lowongan->judul }}</td>
                        <td class="px-5 py-3">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</td>
                        <td class="px-5 py-3">{{ $lowongan->tipe }}</td>
                        <td class="px-5 py-3">{{ $lowongan->lokasi }}</td>
                        <td class="px-5 py-3">{{ $lowongan->tanggal_tutup ? \Carbon\Carbon::parse($lowongan->tanggal_tutup)->isoFormat('D MMMM YYYY') : '-' }}</td>
                       <td class="px-5 py-3 text-center align-middle">
                            <span class="text-xs font-medium w-20 block mx-auto py-2 px-2 py-1 rounded-full
                                @if($lowongan->status == 'Aktif') bg-green-100 text-green-600
                                @elseif($lowongan->status == 'Non-Aktif') bg-red-100 text-red-700 
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $lowongan->status }}
                            </span>
                        </td>
                       <td class="px-5 py-3 text-center">
                         <div class="flex item-center justify-center space-x-1">
                            <a href="{{ route('admin.lowongan.show', $lowongan->id) }}" class="bg-sky-100 text-sky-600 text-xs font-medium px-3 py-1 rounded hover:bg-sky-200">Show</a>
                            <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                            <form action="{{ route('admin.lowongan.destroy', $lowongan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-4 text-center text-gray-500">
                            @if(request('search'))
                                Tidak ada data lowongan ditemukan untuk pencarian "{{ request('search') }}".
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
                {{ $lowongans->links() }} {{-- Menampilkan pagination links --}}
            </div>
        @endif
    </div>
</main>

@include('admin.template.footer')
</body>
</html>