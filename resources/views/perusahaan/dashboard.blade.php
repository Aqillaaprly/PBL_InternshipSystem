<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Perusahaan - SIMMAGANG</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    
    @include('perusahaan.template.navbar') 

    <main class="flex flex-col min-h-screen">
        <div class="p-6 max-w-7xl mx-auto w-full mt-16"> 
            <div class="w-full mb-6">
                <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg"
                     alt="Header"
                     class="w-full h-48 object-cover rounded-b-lg shadow" />
            </div>

            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, {{ $company->nama_perusahaan ?? Auth::user()->username ?? 'Perusahaan' }}!</h1>
                <p class="text-sm text-gray-600">Dashboard Sistem Informasi Manajemen Magang Mahasiswa</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Pusat</h2>
                    <p class="text-gray-700 mb-3">Magang Pusat adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.</p>
                    <p class="text-gray-700">Perusahaan-perusahaan yang akan di jadikan tempat magang sudah terorganisir oleh kampus</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Mandiri</h2>
                    <p class="text-gray-700 mb-3">Magang Mandiri adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.</p>
                    <p class="text-gray-700">Mahasiswa mencari sendiri perusahaan-perusahan yang akan dijadikan untuk penerapan keilmuan atau kompetensi.</p>
                </div>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg mt-6  border border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-blue-800 mb-4 sm:mb-0">Daftar Pendaftar Magang Terbaru</h2> {{-- Updated title --}}
                    <a href="{{ route('perusahaan.pendaftar.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Pendaftar</a>
                </div>

                @if (session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('success') }}</span></div> @endif
                @if (session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div> @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3">No</th>
                                <th class="px-5 py-3">Nama Mahasiswa</th>
                                <th class="px-5 py-3">Lowongan</th>
                                <th class="px-5 py-3">Perusahaan</th>
                                <th class="px-5 py-3">Tanggal Daftar</th>
                                <th class="px-5 py-3 text-center">Status Lamaran</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 divide-y divide-gray-200">
                            @forelse ($recentPendaftars as $index => $pendaftar)
                                <tr class="hover:bg-gray-50">   
                                    <td class="px-5 py-3 text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="px-5 py-3 align-middle font-medium text-gray-900">{{ $pendaftar->user->name ?? ($pendaftar->user->username ?? 'N/A') }}</td>
                                    <td class="px-5 py-3 align-middle">{{ $pendaftar->lowongan->judul ?? 'N/A' }}</td>
                                    <td class="px-5 py-3 align-middle">{{ $pendaftar->lowongan->company->nama_perusahaan ?? 'N/A' }}</td>
                                    <td class="px-5 py-3 align-middle">{{ $pendaftar->tanggal_daftar ? \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->isoFormat('D MMM YY') : 'N/A' }}</td>
                                    <td class="px-5 py-3 text-center align-middle">
                                        <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                            @if ($pendaftar->status_lamaran == 'Diterima') bg-green-100 text-green-700
                                            @elseif ($pendaftar->status_lamaran == 'Ditolak') bg-red-100 text-red-700
                                            @elseif ($pendaftar->status_lamaran == 'Pending') bg-yellow-100 text-yellow-700
                                            @elseif ($pendaftar->status_lamaran == 'Wawancara') bg-blue-100 text-blue-700
                                            @elseif ($pendaftar->status_lamaran == 'Ditinjau') bg-indigo-100 text-indigo-700
                                            @else bg-gray-200 text-gray-700 @endif">
                                            {{ $pendaftar->status_lamaran }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center align-middle">
                                        <div class="flex item-center justify-center space-x-1 sm:space-x-2">
                                            <a href="{{ route('perusahaan.pendaftar.show', $pendaftar->id) }}"
                                               class="text-xs bg-sky-100 text-sky-600 hover:bg-sky-200 px-3 py-1.5 rounded-md font-medium">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                         @if(request('search'))
                                            Tidak ada pendaftar ditemukan untuk pencarian "{{ request('search') }}".
                                        @else
                                            Belum ada data pendaftar terbaru untuk perusahaan Anda.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    @include('perusahaan.template.footer') 
</body>
</html>