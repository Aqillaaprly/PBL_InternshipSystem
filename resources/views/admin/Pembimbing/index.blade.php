<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pembimbing - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex flex-col sm:flex-row justify-between items-center pb-6">
                <h1 class="text-2xl font-bold text-blue-800 mb-4 sm:mb-0">Manajemen Dosen Pembimbing</h1>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <form method="GET" action="{{ route('admin.pembimbings.index') }}" class="flex flex-1 sm:flex-none">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP, Nama, Email..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 w-full">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r text-sm">Cari</button>
                    </form>
                    <a href="{{ route('admin.pembimbings.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700 whitespace-nowrap">+ Tambah Pembimbing</a>
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
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs text-left">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIP</th>
                            <th class="px-5 py-3">Nama Lengkap</th>
                            <th class="px-5 py-3">Email Institusi</th>
                            <th class="px-5 py-3">Program Studi</th>
                            <th class="px-5 py-3 text-center">Kuota (Aktif/Maks)</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-left">
                        @forelse ($pembimbings as $index => $pembimbing)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4 text-center">{{ $pembimbings->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->nip }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->nama_lengkap }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->email_institusi }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->program_studi_homebase ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">{{ $pembimbing->kuota_bimbingan_aktif }}/{{ $pembimbing->maks_kuota_bimbingan }}</td>
                                <td class="px-5 py-4 text-center">
                                    @if($pembimbing->status_aktif)
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full text-xs">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex item-center justify-center space-x-1">
                                        {{-- PASTIKAN BAGIAN INI MENGGUNAKAN NAMA RUTE YANG BENAR --}}
                                        <a href="{{ route('admin.pembimbings.show', $pembimbing->id) }}" class="bg-sky-100 text-sky-600 text-xs font-medium px-3 py-1 rounded hover:bg-sky-200">Detail</a>
                                        <a href="{{ route('admin.pembimbings.edit', $pembimbing->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                                        <form action="{{ route('admin.pembimbings.destroy', $pembimbing->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pembimbing ini beserta akun login terkait?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada pembimbing ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data pembimbing.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pembimbings->hasPages())
                <div class="mt-6">
                    {{ $pembimbings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
    @include('admin.template.footer')
</body>
</html>