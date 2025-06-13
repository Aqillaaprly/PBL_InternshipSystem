<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Mahasiswa - {{ $mahasiswa->name ?? $mahasiswa->username }}</title>
    {{-- Tailwind CSS dan Font Awesome tetap disertakan karena beberapa style mungkin masih menggunakannya --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Google Font untuk 'Inter' --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            width: 20px; /* Menyamakan lebar ikon */
            text-align: center;
        }
        .detail-value {
            color: #1f2937; /* Gray-800 */
            font-weight: 500;
            font-size: 1rem;
            line-height: 1.5; /* Better line spacing */
            padding-left: calc(0.6rem + 20px); /* Meluruskan teks value dengan teks label */
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
<body>
    @include('dosen.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="info-card">
            <div class="flex justify-between items-start mb-4">
                 <h1 class="section-header">Detail Mahasiswa</h1>
                 <a href="{{ route('dosen.data_mahasiswabim', $mahasiswa-> id) }}" class="text-sm text-indigo-600 hover:underline whitespace-nowrap">&larr; Kembali ke Daftar</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                {{-- Data Utama Mahasiswa --}}
                <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-user"></i> Nama Lengkap</span>
                    <span class="detail-value">{{ $mahasiswa->name ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-id-card"></i> NIM (Username)</span>
                    <span class="detail-value">{{ $mahasiswa->username ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-envelope"></i> Email</span>
                    <span class="detail-value">{{ $mahasiswa->email ?? '-' }}</span>
                </div>
                 <div class="detail-item">
                    <span class="detail-label"><i class="fa-solid fa-user-shield"></i> Role</span>
                    <span class="detail-value">{{ $mahasiswa->role->name ?? '-' }}</span>
                </div>


                {{-- Detail Mahasiswa jika ada --}}
                @if($mahasiswa->detailMahasiswa)
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-chalkboard-user"></i> Kelas</span>
                        <span class="detail-value">{{ $mahasiswa->detailMahasiswa->kelas ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-graduation-cap"></i> Program Studi</span>
                        <span class="detail-value">{{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-phone"></i> Nomor HP</span>
                        <span class="detail-value">{{ $mahasiswa->detailMahasiswa->nomor_hp ?? '-' }}</span>
                    </div>
                     <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-calendar-alt"></i> Akun Dibuat</span>
                        <span class="detail-value">{{ $mahasiswa->created_at ? $mahasiswa->created_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fa-solid fa-map-marker-alt"></i> Alamat</span>
                        <span class="detail-value whitespace-pre-line">{{ $mahasiswa->detailMahasiswa->alamat ?? '-' }}</span>
                    </div>
                @else
                    <p class="text-gray-500 md:col-span-2 mt-4">Detail spesifik mahasiswa (seperti kelas, prodi, dll.) tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </main>


</body>
</html>