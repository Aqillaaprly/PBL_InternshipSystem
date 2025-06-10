<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembimbing - {{ $pembimbing->nama_lengkap ?? ($pembimbing->user->name ?? 'Informasi Pembimbing') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #f7f8fc; }
        .profile-header {
            background: linear-gradient(to right, #687EEA, #3B5998);
            color: white;
            padding: 1rem 1rem;
            border-radius: 1rem 1rem 0 0;
            margin-bottom: -1rem;
            position: relative;
            z-index: 10;
        }
        .info-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            padding-top: 3rem;
            position: relative;
            z-index: 5;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6b7280;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }
        .info-value {
            color: #111827;
            font-weight: 500;
            text-align: right;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
        }
        .badge-aktif {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-nonaktif {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .action-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .action-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-3xl mx-auto px-4 py-8 mt-20">
        <div class="profile-header text-center">
            <h1 class="text-2xl sm:text-3xl font-bold">Detail Pembimbing</h1>
        </div>

        <div class="info-card">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if(isset($pembimbing) && $pembimbing->id)
                <div class="space-y-4 text-sm">
                    <div class="info-item"><span class="info-label">Nama Lengkap</span><span class="info-value">{{ $pembimbing->nama_lengkap ?? '-' }}</span></div>
                    <div class="info-item"><span class="info-label">NIP</span><span class="info-value">{{ $pembimbing->nip ?? '-' }}</span></div>
                    <div class="info-item"><span class="info-label">Email Institusi</span><span class="info-value">{{ $pembimbing->email_institusi ?? '-' }}</span></div>
                    <div class="info-item"><span class="info-label">Nomor Telepon</span><span class="info-value">{{ $pembimbing->nomor_telepon ?? '-' }}</span></div>
                    <div class="info-item"><span class="info-label">Jabatan Fungsional</span><span class="info-value">{{ $pembimbing->jabatan_fungsional ?? '-' }}</span></div>
                    <div class="info-item"><span class="info-label">Program Studi Homebase</span><span class="info-value">{{ $pembimbing->program_studi_homebase ?? '-' }}</span></div>
                    <div class="info-item"><span class="info-label">Bidang Keahlian Utama</span><span class="info-value">{{ $pembimbing->bidang_keahlian_utama ?? '-' }}</span></div>
                    <div class="info-item"><span class="info-label">Kuota Bimbingan Aktif</span><span class="info-value">{{ $pembimbing->kuota_aktif ?? 0 }}</span></div>
                    <div class="info-item"><span class="info-label">Maksimal Kuota</span><span class="info-value">{{ $pembimbing->maks_kuota_bimbingan ?? 0 }}</span></div>
                    <div class="info-item"><span class="info-label">Status Aktif</span>
                        <span class="info-value">
                            <span class="badge {{ $pembimbing->status_aktif ? 'badge-aktif' : 'badge-nonaktif' }}">
                                {{ $pembimbing->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </span>
                    </div>
                </div>

                {{-- NEW SECTION: Mahasiswa Bimbingan --}}
                <div class="border-t border-gray-200 pt-6 mt-6 text-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Mahasiswa Bimbingan</h2>
                    
                    </div>

                    @if($pembimbing->bimbinganMagangs->isNotEmpty())
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full text-sm text-left">
                                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">NIM</th>
                                        <th class="px-4 py-2">Nama Mahasiswa</th>
                                        <th class="px-4 py-2">Program Studi</th>
                                        <th class="px-4 py-2">Perusahaan</th>
                                        <th class="px-4 py-2 text-center">Status Bimbingan</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600">
                                    @foreach($pembimbing->bimbinganMagangs as $index => $bimbingan)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                                            <td class="px-4 py-2">{{ $bimbingan->mahasiswa->detailMahasiswa->nim ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ $bimbingan->mahasiswa->name ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ $bimbingan->mahasiswa->detailMahasiswa->program_studi ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ $bimbingan->company->nama_perusahaan ?? '-' }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <span class="badge
                                                    @if($bimbingan->status_bimbingan == 'Aktif') bg-green-100 text-green-700
                                                    @elseif($bimbingan->status_bimbingan == 'Selesai') bg-blue-100 text-blue-700
                                                    @else bg-red-100 text-red-700 @endif">
                                                    {{ $bimbingan->status_bimbingan }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Pembimbing ini belum memiliki mahasiswa bimbingan.</p>
                    @endif
                </div>

                <div class="mt-10 flex justify-center">
                    <a href="{{ route('admin.pembimbings.edit', $pembimbing->id) }}" class="action-button">
                        Edit Pembimbing
                    </a>
                </div>
            @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mt-4" role="alert">
                    <p class="font-bold">Data Pembimbing Tidak Ditemukan</p>
                    <p>Data tidak valid atau tidak ditemukan.</p>
                </div>
            @endif
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>