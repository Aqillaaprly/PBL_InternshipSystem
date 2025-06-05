<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas & Absensi Mahasiswa - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f7f8fc;
        }
        .page-header {
            background: linear-gradient(to right, #687EEA, #3B5998);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
            margin-bottom: -1rem; /* Overlaps with info-section slightly */
            position: relative;
            z-index: 10;
        }
        .info-section {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            padding: 2rem;
            padding-top: 2rem;
            position: relative;
            z-index: 5;
        }
        .info-block {
            border-bottom: 1px solid #f3f4f6;
            padding: 1rem 0;
        }
        .info-block:last-of-type {
            border-bottom: none; /* No border for the last block in a section */
        }
        .info-label {
            font-weight: 500;
            color: #6b7280;
        }
        .info-value {
            color: #1f2937;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .view-button {
            background-image: linear-gradient(to right, #2563eb, #3b82f6); /* Blue gradient for view */
            color: white;
        }
        .view-button:hover {
            background-image: linear-gradient(to right, #1d4ed8, #2563eb);
        }
    </style>
</head>
<body class="text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-6xl mx-auto px-4 py-10 mt-20">
        <div class="page-header text-center">
            <h1 class="text-3xl font-bold">Aktivitas & Absensi Mahasiswa</h1>
            <p class="text-sm text-blue-100 mt-1">Daftar Log Bimbingan dan Aktivitas Mahasiswa</p>
        </div>

        <div class="info-section">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-blue-800 mb-4 sm:mb-0">Daftar Log Aktivitas</h2>
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <form method="GET" action="{{ route('admin.aktivitas-absensi.index') }}" class="flex flex-1 sm:flex-none">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/jenis aktivitas..." class="border border-gray-300 rounded-l-md px-4 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md text-sm">Cari</button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Pembimbing</th>
                            <th class="px-5 py-3">Tanggal Aktivitas</th>
                            <th class="px-5 py-3">Jenis Aktivitas</th>
                            <th class="px-5 py-3">Catatan</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y divide-gray-200">
                        {{-- Contoh data (loop melalui $aktivitas_absensi yang dilewatkan dari controller) --}}
                        @forelse ($aktivitas_absensi as $index => $aktivitas)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-center align-middle">{{ $aktivitas_absensi->firstItem() + $index }}</td>
                                <td class="px-5 py-3 align-middle font-medium text-gray-900">{{ $aktivitas->mahasiswa->user->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 align-middle">{{ $aktivitas->pembimbing->nama_lengkap ?? 'N/A' }}</td>
                                <td class="px-5 py-3 align-middle">{{ \Carbon\Carbon::parse($aktivitas->tanggal)->isoFormat('D MMMM BBBB') }}</td>
                                <td class="px-5 py-3 align-middle">{{ $aktivitas->jenis_bimbingan }}</td>
                                <td class="px-5 py-3 align-middle">{{ Str::limit($aktivitas->catatan, 50, '...') }}</td>
                                <td class="px-5 py-3 text-center align-middle">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.aktivitas-absensi.show', $aktivitas->id) }}"
                                           class="action-button view-button inline-flex items-center">
                                            Lihat Detail
                                        </a>
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