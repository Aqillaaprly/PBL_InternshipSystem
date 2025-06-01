<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Penugasan Pembimbing - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-800">Manajemen Penugasan Pembimbing</h1>
                <a href="{{ route('admin.penugasan-pembimbing.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700 whitespace-nowrap">+ Tambah Penugasan</a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form method="GET" action="{{ route('admin.penugasan-pembimbing.index') }}" class="mb-4 flex flex-wrap gap-4">
                <input type="text" name="search_mahasiswa" value="{{ request('search_mahasiswa') }}" placeholder="Cari Mahasiswa (Nama/NIM)..." class="border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 flex-grow">
                <input type="text" name="search_pembimbing" value="{{ request('search_pembimbing') }}" placeholder="Cari Pembimbing (Nama/NIP)..." class="border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 flex-grow">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">Cari</button>
            </form>


            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs text-left">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Mahasiswa (NIM)</th>
                            <th class="px-5 py-3">Pembimbing (NIP)</th>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">Periode</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-left">
                        @forelse ($penugasan as $index => $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4 text-center">{{ $penugasan->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $item->mahasiswa->name ?? 'N/A' }} ({{ $item->mahasiswa->username ?? 'N/A' }})</td>
                                <td class="px-5 py-4">{{ $item->pembimbing->user->name ?? ($item->pembimbing->nama_lengkap ?? 'N/A') }} ({{ $item->pembimbing->nip ?? 'N/A' }})</td>
                                <td class="px-5 py-4">{{ $item->company->nama_perusahaan ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $item->periode_magang }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                        @if($item->status_bimbingan == 'Aktif') bg-green-100 text-green-700
                                        @elseif($item->status_bimbingan == 'Selesai') bg-blue-100 text-blue-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ $item->status_bimbingan }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex item-center justify-center space-x-1">
                                        <a href="{{ route('admin.penugasan-pembimbing.edit', $item->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.penugasan-pembimbing.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus penugasan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                    Belum ada data penugasan pembimbing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($penugasan->hasPages())
                <div class="mt-6">
                    {{ $penugasan->links() }}
                </div>
            @endif
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
