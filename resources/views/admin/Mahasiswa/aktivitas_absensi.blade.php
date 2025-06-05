<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aktivitas & Absensi Mahasiswa - Admin SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Custom styles from previous version removed as per data mahasiswa template --}}
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                {{-- Adjusted title to match datamahasiswa style --}}
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Aktivitas & Absensi Mahasiswa</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('admin.aktivitas-absensi.index') }}" class="flex">
                        {{-- Placeholder updated for context --}}
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/jenis aktivitas..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r text-sm -ml-px">Cari</button>
                    </form>
                    {{-- Added 'Tambah' button, commented out as its route might not exist yet, consistent with previous responses --}}
                    {{-- <a href="{{ route('admin.aktivitas-absensi.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700">+ Tambah Aktivitas</a> --}}
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

             {{-- Removed shadow-sm from this div to match datamahasiswa.blade.php --}}
             <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-center"> {{-- Changed text-left to text-center --}}
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Pembimbing</th>
                            <th class="px-5 py-3">Tanggal Aktivitas</th>
                            <th class="px-5 py-3">Jenis Aktivitas</th>
                            <th class="px-5 py-3">Catatan</th>
                            <th class="px-5 py-3">Aksi</th> {{-- Aligned with datamahasiswa style --}}
                        </tr>
                    </thead>
                    <tbody class="text-gray-600"> {{-- Removed divide-y divide-gray-200 from tbody --}}
                        @forelse ($aktivitas_absensi as $index => $aktivitas)
                            <tr class="border-b border-gray-200 hover:bg-gray-50"> {{-- Added border-b --}}
                                <td class="px-5 py-4">{{ $aktivitas_absensi->firstItem() + $index }}</td> {{-- Changed py-3 to py-4 --}}
                                <td class="px-5 py-4 text-left font-medium text-gray-900">{{ $aktivitas->mahasiswa->user->name ?? 'N/A' }}</td> {{-- Changed py-3 to py-4 and added text-left --}}
                                <td class="px-5 py-4">{{ $aktivitas->pembimbing->nama_lengkap ?? 'N/A' }}</td> {{-- Changed py-3 to py-4 --}}
                                <td class="px-5 py-4">{{ \Carbon\Carbon::parse($aktivitas->tanggal)->isoFormat('D MMMM BBBB') }}</td> {{-- Changed py-3 to py-4 --}}
                                <td class="px-5 py-4">{{ $aktivitas->jenis_bimbingan }}</td> {{-- Changed py-3 to py-4 --}}
                                <td class="px-5 py-4 text-left">{{ Str::limit($aktivitas->catatan, 50, '...') }}</td> {{-- Changed py-3 to py-4 and added text-left --}}
                                <td class="px-5 py-4"> {{-- Changed py-3 to py-4 --}}
                                    <div class="flex item-center justify-center space-x-1"> {{-- Adjusted to match datamahasiswa action button wrapper --}}
                                        <a href="{{ route('admin.aktivitas-absensi.show', $aktivitas->id) }}"
                                           class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200"> {{-- Matched Show button style from datamahasiswa --}}
                                            Show
                                        </a>
                                        {{-- Edit and Delete buttons commented out, similar to previous response, as their routes and specific logic for Aktivitas/Absensi were not requested --}}
                                        {{-- <a href="{{ route('admin.aktivitas-absensi.edit', $aktivitas->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">
                                            Edit
                                        </a> --}}
                                        {{-- <form action="{{ route('admin.aktivitas-absensi.destroy', $aktivitas->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">
                                                Delete
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada aktivitas/absensi ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data aktivitas atau absensi mahasiswa.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($aktivitas_absensi->hasPages())
                <div class="mt-6">
                    {{ $aktivitas_absensi->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>