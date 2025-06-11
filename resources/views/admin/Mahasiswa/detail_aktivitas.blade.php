<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Aktivitas Mahasiswa: {{ $mahasiswa->name ?? 'N/A' }}</title>     
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS untuk pill badge */
        .pill-badge {
            display: inline-block;
            padding: 0.25em 0.75em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 9999px; /* Tailwind's rounded-full equivalent */
        }
        /* Tambahkan CSS untuk styling catatan_verifikasi_dosen */
        .whitespace-pre-line {
            white-space: pre-line;
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">

    {{-- INCLUDE NAVBAR --}}
    @include('admin.template.navbar')

<main class="pt-16 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="pb-4 mb-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Detail Kegiatan Magang Mahasiswa</h1>
                    {{-- Pastikan $mahasiswa tersedia dari controller --}}
                    <p class="text-gray-600">Mahasiswa: **{{ $mahasiswa->name ?? 'N/A' }}** (NIM: {{ $mahasiswa->username ?? 'N/A' }})</p>
                    @php
                        $companyName = 'Belum Ditentukan';
                        // Menggunakan 'pendaftars' sesuai relasi di model User
                        // Serta kolom 'status_lamaran' dan nilai 'Diterima' dari migrasi pendaftars
                        // Pastikan relasi pendaftars, lowongan, dan company dimuat di controller
                        $pendaftarDiterima = $mahasiswa->pendaftars->where('status_lamaran', 'Diterima')->first();
                        if ($pendaftarDiterima && $pendaftarDiterima->lowongan && $pendaftarDiterima->lowongan->company) {
                            $companyName = $pendaftarDiterima->lowongan->company->nama_perusahaan;
                        }
                    @endphp
                    <p class="text-gray-600">Perusahaan Magang: {{ $companyName }}</p>
                </div>
                <a href="{{ route('admin.aktivitas-mahasiswa.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Daftar Mahasiswa</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Menggunakan $aktivitas yang diteruskan dari controller --}}
            @if ($aktivitas->isEmpty())
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Informasi:</strong>
                    <span class="block sm:inline">Mahasiswa ini belum memiliki catatan aktivitas magang.</span>
                </div>
            @else
                <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-300 rounded-lg">
                    <thead class="bg-blue-100 text-blue-800">
                        <tr>
                            <th class="px-4 py-2 text-left border-b">Tanggal</th>
                            <th class="px-4 py-2 text-left border-b">Deskripsi Kegiatan</th>
                            <th class="px-4 py-2 text-left border-b">Jam Kerja</th>
                            <th class="px-4 py-2 text-left border-b">Status Verifikasi</th>
                            <th class="px-4 py-2 text-left border-b">Bukti Kegiatan</th>
                            <th class="px-4 py-2 text-left border-b">Catatan Dosen</th>
                            <th class="px-4 py-2 text-left border-b">Catatan Perusahaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Menggunakan $aktivitas sebagai nama koleksi yang benar --}}
                        @forelse ($aktivitas as $kegiatan) {{-- Mengganti $aktivitasMagang dengan $aktivitas, dan $aktivitas internal loop dengan $kegiatan --}}
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $kegiatan->tanggal }}</td>
                            <td class="px-4 py-2 border-b whitespace-pre-line">{{ $kegiatan->deskripsi_kegiatan }}</td>
                            <td class="px-4 py-2 border-b">{{ $kegiatan->jam_kerja ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">
                                @php
                                    $statusClass = '';
                                    if ($kegiatan->status_verifikasi == 'pending') {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($kegiatan->status_verifikasi == 'terverifikasi') { // Sesuai dengan nilai yang divalidasi di controller
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($kegiatan->status_verifikasi == 'ditolak') { // Sesuai dengan nilai yang divalidasi di controller
                                        $statusClass = 'bg-red-100 text-red-800';
                                    }
                                @endphp
                                <span class="pill-badge {{ $statusClass }}">
                                    {{ ucfirst($kegiatan->status_verifikasi) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border-b">
                                @if ($kegiatan->bukti_kegiatan)
                                    <a href="{{ asset('storage/' . $kegiatan->bukti_kegiatan) }}" target="_blank" class="text-blue-500 hover:underline">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                            {{-- Gunakan nama field yang sesuai dengan yang disimpan di database --}}
                            <td class="px-4 py-2 border-b whitespace-pre-line">{{ $kegiatan->catatan_dosen ?? '-' }}</td> {{-- Mengganti catatan_verifikasi_dosen dengan catatan_dosen --}}
                            <td class="px-4 py-2 border-b whitespace-pre-line">{{ $kegiatan->catatan_verifikasi_perusahaan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-500">Belum ada aktivitas magang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            @endif
        </div>
    </main>
    @include('admin.template.footer')

    {{-- JavaScript untuk Bootstrap Modal (Pastikan jQuery, Popper.js, dan Bootstrap JS terload) --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
</html>
