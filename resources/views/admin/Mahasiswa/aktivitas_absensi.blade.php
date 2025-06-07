<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aktivitas & Absensi Mahasiswa - Admin SIMMAGANG</title>
    
    {{-- Tailwind CSS dari CDN (sesuai permintaan Anda) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Bootstrap CSS (untuk modal, jika ada) --}}
    
</head>
<body class="bg-blue-50 text-gray-800">

    {{-- INCLUDE NAVBAR (sesuai permintaan Anda) --}}
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Aktivitas & Absensi Mahasiswa</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('admin.aktivitas-mahasiswa.index') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIM..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
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
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIM</th>
                            <th class="px-5 py-3">Nama Lengkap</th>
                            <th class="px-5 py-3">Email Institusi</th>
                            <th class="px-5 py-3">Program Studi</th>
                            <th class="px-5 py-3">Perusahaan Magang</th>
                            <th class="px-5 py-3">Status Lamaran</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($mahasiswas as $index => $mahasiswa)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4">{{ $mahasiswas->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->username ?? '-' }}</td>
                                <td class="px-5 py-4 text-left">{{ $mahasiswa->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-left">{{ $mahasiswa->email ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    @php
                                        $companyName = 'N/A';
                                        // DIUBAH: Menggunakan 'pendaftars' bukan 'pendaftarans'
                                        $pendaftarDiterima = $mahasiswa->pendaftars->where('status_lamaran', 'Diterima')->first();
                                        if ($pendaftarDiterima && $pendaftarDiterima->lowongan && $pendaftarDiterima->lowongan->company) {
                                            $companyName = $pendaftarDiterima->lowongan->company->nama_perusahaan;
                                        }
                                    @endphp
                                    {{ $companyName }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($pendaftarDiterima)
                                        <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                            @if($pendaftarDiterima->status_lamaran == 'Diterima') bg-green-100 text-green-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ $pendaftarDiterima->status_lamaran }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs bg-gray-100 text-gray-700">
                                            Belum Diterima
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex item-center justify-center space-x-1">
                                        {{-- Tombol Detail yang mengarah ke halaman baru --}}
                                        <a href="{{ route('admin.aktivitas-mahasiswa.show', $mahasiswa->id) }}"
                                           class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada mahasiswa ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada mahasiswa yang diterima magang.
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

    {{-- INCLUDE FOOTER --}}
    @include('admin.template.footer')

</body>
</html>