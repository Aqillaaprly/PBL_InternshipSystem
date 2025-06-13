<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Aktivitas Magang - Mahasiswa {{ $bimbingan->mahasiswa->nama ?? '-' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif; /* Using Inter font */
        }
        .info-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2.5rem; /* Increased padding for more space */
            position: relative;
            z-index: 5;
            text-align: left;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: #6b7280; /* Gray-500 */
            font-size: 0.9rem; /* Slightly larger for readability */
            font-weight: 600; /* Semi-bold */
            display: flex;
            align-items: center;
            margin-bottom: 0.35rem; /* Adjusted margin */
        }
        .detail-label i {
            margin-right: 0.6rem; /* More space for icon */
            color: #4f46e5; /* Indigo-600 for icons */
            font-size: 1.1em; /* Slightly larger icon */
        }
        .detail-value {
            color: #1f2937; /* Gray-800 */
            font-weight: 500;
            font-size: 1rem;
            line-height: 1.5; /* Better line spacing */
        }
        .whitespace-pre-line {
            white-space: pre-line;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.7rem 1.5rem; /* Increased padding */
            font-size: 0.9rem; /* Slightly larger font */
            font-weight: 600; /* Semi-bold */
            border-radius: 0.6rem; /* More rounded */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .edit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
        }
        .edit-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
        .delete-button {
            background-color: #ef4444; /* Red-500 */
            color: white;
        }
        .delete-button:hover {
            background-color: #dc2626; /* Red-600 */
        }
        .section-header {
            font-size: 1.5rem; /* Larger section titles */
            font-weight: 700; /* Bold */
            color: #1f2937; /* Gray-800 */
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb; /* Subtle separator */
        }
        .pill-badge {
            padding: 0.25rem 0.75rem; /* Slightly larger badge */
            border-radius: 9999px; /* Fully rounded */
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('dosen.template.navbar')

    <main class="max-w-6xl mx-auto px-4 py-10 mt-16">
        <div class="info-card"> {{-- Applied info-card class --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="section-header">Detail Aktivitas Magang</h1> {{-- Applied section-header class --}}
                <a href="{{ route('dosen.absensi.index') }}" class="text-sm text-blue-600 hover:underline">
                    &larr; Kembali ke Rekap Akumulasi
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="detail-item"> {{-- Applied detail-item class --}}
                    <span class="detail-label"><i class="fas fa-user-graduate"></i> Mahasiswa:</span> {{-- Applied detail-label and icon --}}
                    <span class="detail-value">{{ $bimbingan->mahasiswa->name ?? '-' }}</span> {{-- Applied detail-value --}}
                </div>
                <div class="detail-item"> {{-- Applied detail-item class --}}
                    <span class="detail-label"><i class="fas fa-chalkboard-teacher"></i> Pembimbing:</span> {{-- Applied detail-label and icon --}}
                    <span class="detail-value">{{ $bimbingan->pembimbing->nama_lengkap ?? '-' }}</span> {{-- Applied detail-value --}}
                </div>
                <div class="detail-item"> {{-- Applied detail-item class --}}
                    <span class="detail-label"><i class="fas fa-building"></i> Perusahaan:</span> {{-- Applied detail-label and icon --}}
                    <span class="detail-value">{{ $bimbingan->company->nama_perusahaan ?? '-' }}</span> {{-- Applied detail-value --}}
                </div>
                <div class="detail-item"> {{-- Applied detail-item class --}}
                    <span class="detail-label"><i class="fas fa-calendar-alt"></i> Periode Magang:</span> {{-- Applied detail-label and icon --}}
                    <span class="detail-value">{{ $bimbingan->periode_magang }}</span> {{-- Applied detail-value --}}
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
                            <td class="px-4 py-2 border-b whitespace-pre-line">{{ $aktivitas->deskripsi_kegiatan }}</td> {{-- Applied whitespace-pre-line --}}
                            <td class="px-4 py-2 border-b">{{ $aktivitas->jam_kerja ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">
                                @php
                                    $statusClass = '';
                                    if ($aktivitas->status_verifikasi == 'pending') {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($aktivitas->status_verifikasi == 'verified') {
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($aktivitas->status_verifikasi == 'rejected') {
                                        $statusClass = 'bg-red-100 text-red-800';
                                    }
                                @endphp
                                <span class="pill-badge {{ $statusClass }}"> {{-- Applied pill-badge and dynamic status class --}}
                                    {{ ucfirst($aktivitas->status_verifikasi) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border-b">
                                @if ($aktivitas->bukti_kegiatan)
                                    <a href="{{ asset('storage/' . $aktivitas->bukti_kegiatan) }}" target="_blank" class="text-blue-500 hover:underline">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b whitespace-pre-line">{{ $aktivitas->catatan_verifikasi_dosen ?? '-' }}</td> {{-- Applied whitespace-pre-line --}}
                            <td class="px-4 py-2 border-b whitespace-pre-line">{{ $aktivitas->catatan_verifikasi_perusahaan ?? '-' }}</td> {{-- Applied whitespace-pre-line --}}
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

</body>
</html>