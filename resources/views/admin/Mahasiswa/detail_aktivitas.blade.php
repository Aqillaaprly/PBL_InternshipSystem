<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Aktivitas Mahasiswa: {{ $mahasiswa->name ?? 'N/A' }}</title>    
    <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-blue-50 text-gray-800">

    {{-- INCLUDE NAVBAR --}}
    @include('admin.template.navbar')
<main class="pt-16 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="pb-4 mb-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Detail Kegiatan Magang Mahasiswa</h1>
                    <p class="text-gray-600">Mahasiswa: **{{ $mahasiswa->name ?? 'N/A' }}** (NIM: {{ $mahasiswa->username ?? 'N/A' }})</p>
                    @php
                        $companyName = 'Belum Ditentukan';
                        // Menggunakan 'pendaftars' sesuai relasi di model User
                        // Serta kolom 'status_lamaran' dan nilai 'Diterima' dari migrasi pendaftars
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

            @if ($aktivitas->isEmpty())
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Informasi:</strong>
                    <span class="block sm:inline">Mahasiswa ini belum memiliki catatan aktivitas magang.</span>
                </div>
            @else
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full bg-white text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3 text-left">No</th>
                                <th class="px-5 py-3 text-left">Tanggal</th>
                                <th class="px-5 py-3 text-left">Jenis Aktivitas</th>
                                <th class="px-5 py-3 text-left">Catatan / Deskripsi</th>
                                <th class="px-5 py-3 text-left">Dosen Pembimbing</th>
                                <th class="px-5 py-3 text-center">Status Verifikasi</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aktivitas as $index => $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-5 py-4">{{ $index + 1 }}</td>
                                    <td class="px-5 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                    <td class="px-5 py-4">{{ $item->jenis_bimbingan }}</td>
                                    <td class="px-5 py-4">{{ Str::limit($item->catatan, 100) }}</td>
                                    <td class="px-5 py-4">{{ $item->dosenPembimbing->name ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{
                                            $item->status_verifikasi == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            ($item->status_verifikasi == 'terverifikasi' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')
                                        }}">
                                            {{ ucfirst($item->status_verifikasi) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button type="button" class="bg-indigo-100 text-indigo-600 text-xs font-medium px-3 py-1.5 rounded hover:bg-indigo-200" data-toggle="modal" data-target="#detailAktivitasModal{{ $item->id }}">
                                                Lihat
                                            </button>
                                            @if($item->status_verifikasi == 'pending')
                                            <button type="button" class="bg-green-100 text-green-600 text-xs font-medium px-3 py-1.5 rounded hover:bg-green-200" data-toggle="modal" data-target="#verifyModal{{ $item->id }}">
                                                Verifikasi
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="detailAktivitasModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="detailAktivitasModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailAktivitasModalLabel{{ $item->id }}">Detail Aktivitas Magang</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Mahasiswa:</strong> {{ $item->mahasiswa->user->name ?? 'N/A' }} (NIM: {{ $item->mahasiswa->user->username ?? 'N/A' }})</p>
                                                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</p>
                                                <p><strong>Jenis Aktivitas:</strong> {{ $item->jenis_bimbingan }}</p>
                                                <p><strong>Catatan / Deskripsi:</strong></p>
                                                <p>{{ $item->catatan }}</p>
                                                <p><strong>Dosen Pembimbing:</strong> {{ $item->dosenPembimbing->name ?? 'N/A' }}</p>
                                                <p><strong>Status Verifikasi:</strong> {{ ucfirst($item->status_verifikasi) }}</p>
                                                @if($item->bukti_kegiatan)
                                                    <p><strong>Bukti Kegiatan:</strong> <a href="{{ asset('storage/' . $item->bukti_kegiatan) }}" target="_blank" class="text-blue-500 hover:underline">Lihat Bukti</a></p>
                                                    {{-- Asumsi bukti_kegiatan adalah path gambar --}}
                                                    <img src="{{ asset('storage/' . $item->bukti_kegiatan) }}" alt="Bukti Kegiatan" class="max-w-full h-auto mt-2 rounded-lg shadow-sm">
                                                @else
                                                    <p><strong>Bukti Kegiatan:</strong> Tidak ada</p>
                                                @endif
                                                <p><strong>Catatan Verifikasi Dosen:</strong> {{ $item->catatan_dosen ?? '-' }}</p>
                                                <p><strong>Catatan Verifikasi Perusahaan:</strong> {{ $item->catatan_perusahaan ?? '-' }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="verifyModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="verifyModalLabel{{ $item->id }}">Verifikasi Aktivitas Magang</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.aktivitas-mahasiswa.verify', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group mb-4">
                                                        <label for="status_verifikasi{{ $item->id }}" class="block text-gray-700 text-sm font-bold mb-2">Status Verifikasi:</label>
                                                        <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status_verifikasi{{ $item->id }}" name="status_verifikasi" required>
                                                            <option value="terverifikasi" {{ $item->status_verifikasi == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                                                            <option value="ditolak" {{ $item->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                            <option value="pending" {{ $item->status_verifikasi == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="catatan_dosen{{ $item->id }}" class="block text-gray-700 text-sm font-bold mb-2">Catatan Verifikasi (Dosen):</label>
                                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="catatan_dosen{{ $item->id }}" name="catatan_dosen" rows="3">{{ $item->catatan_dosen }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan Verifikasi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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