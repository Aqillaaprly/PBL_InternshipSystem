<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Lowongan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f0f6ff]">
    @include('admin.template.navbar')

<main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Manajemen Lowongan</h1>
            <div class="flex space-x-3">
                <input type="text" placeholder="Search" class="border border-gray-300 rounded px-4 py-2" />
                <button class="border border-gray-300 px-4 py-2 rounded">Filter</button>
                {{-- Link ke route admin.lowongan.create jika ada --}}
                <a href="{{-- route('admin.lowongan.create') --}}" class="bg-blue-600 text-white px-5 py-2 rounded">+ Tambah Lowongan</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Judul Lowongan</th>
                    <th class="px-5 py-3">Perusahaan</th>
                    <th class="px-5 py-3">Tipe</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3">Tanggal Tutup</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lowongans as $index => $lowongan)
                    <tr class="border-b">
                        <td class="px-5 py-3">{{ $lowongans->firstItem() + $index }}</td>
                        <td class="px-5 py-3">{{ $lowongan->judul }}</td>
                        <td class="px-5 py-3">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</td>
                        <td class="px-5 py-3">{{ $lowongan->tipe }}</td>
                        <td class="px-5 py-3">{{ $lowongan->lokasi }}</td>
                        <td class="px-5 py-3">{{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full
                                @if($lowongan->status == 'Aktif') bg-green-100 text-green-600 @else bg-red-100 text-red-500 @endif">
                                {{ $lowongan->status }}
                            </span>
                        </td>
                       <td class="px-5 py-3">
                         <div class="flex space-x-1">
                            <a href="route('admin.lowongan.show')" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">Show</a>
                            <a href="route('admin.lowongan.edit')" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                            <form action="route('admin.lowongan.destroy')" method="POST" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-3 text-center text-gray-500">Tidak ada data lowongan.</td>
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