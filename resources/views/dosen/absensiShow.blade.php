<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Aktivitas Magang - Mahasiswa {{ $bimbingan->mahasiswa->nama ?? '-' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('dosen.template.navbar')

    <main class="max-w-6xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-xl shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-800">Detail Aktivitas Magang</h1>
               <a href="{{ route('dosen.absensi.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Rekap Akumulasi</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <strong class="text-gray-700">Mahasiswa:</strong>
                    <p class="text-gray-800">{{ $bimbingan->mahasiswa->nama ?? '-' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">Pembimbing:</strong>
                    <p class="text-gray-800">{{ $bimbingan->pembimbing->nama ?? '-' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">Company:</strong>
                    <p class="text-gray-800">{{ $bimbingan->company->nama ?? '-' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">Periode Magang:</strong>
                    <p class="text-gray-800">{{ $bimbingan->periode_magang }}</p>
                </div>
            </div>

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
                        @forelse ($aktivitasMagang as $aktivitas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $aktivitas->tanggal }}</td>
                            <td class="px-4 py-2 border-b">{{ $aktivitas->deskripsi_kegiatan }}</td>
                            <td class="px-4 py-2 border-b">{{ $aktivitas->jam_kerja ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ ucfirst($aktivitas->status_verifikasi) }}</td>
                            <td class="px-4 py-2 border-b">
                                @if ($aktivitas->bukti_kegiatan)
                                    <a href="{{ asset('storage/' . $aktivitas->bukti_kegiatan) }}" target="_blank" class="text-blue-500 underline">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b">{{ $aktivitas->catatan_verifikasi_dosen ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $aktivitas->catatan_verifikasi_perusahaan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-500">Belum ada aktivitas magang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    @include('dosen.template.footer')
</body>
</html>