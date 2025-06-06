<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Mahasiswa - Admin SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Mahasiswa</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('admin.datamahasiswa') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIM..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r text-sm -ml-px">Cari</button>
                    </form>
                    <a href="{{ route('admin.mahasiswa.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700">+ Tambah</a>
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
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIM</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Program Studi</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($mahasiswas as $index => $mahasiswa)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4">{{ $mahasiswas->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->username ?? ($mahasiswa->detailMahasiswa->nim ?? '-') }}</td>
                                <td class="px-5 py-4 text-left">{{ $mahasiswa->name ?? ($mahasiswa->detailMahasiswa->nama ?? '-') }}</td>
                                <td class="px-5 py-4 text-left">{{ $mahasiswa->email ?? ($mahasiswa->detailMahasiswa->email ?? '-') }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->detailMahasiswa->kelas ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex item-center justify-center space-x-1">
                                        <a href="{{ route('admin.mahasiswa.show', $mahasiswa->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Show
                                        </a>
                                        {{-- Mengarah ke AdminMahasiswaController@edit --}}
                                        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">
                                            Edit
                                        </a>
                                        {{-- Mengarah ke AdminMahasiswaController@destroy --}}
                                        <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini? Menghapus user mahasiswa juga akan menghapus detail mahasiswa terkait.');">
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
                                     @if(request('search'))
                                        Tidak ada mahasiswa ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data mahasiswa.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($mahasiswas->hasPages())
                <div class="mt-6">
                    {{ $mahasiswas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
    @include('admin.template.footer')
</body>
</html>