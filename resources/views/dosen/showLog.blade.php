<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Detail Log Bimbingan - {{ $mahasiswa->name ?? $mahasiswa->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif;
        }
        .info-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
            text-align: left;
        }
        .section-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            background-color: #f9fafb;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        .detail-label {
            color: #6b7280;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        .detail-label i {
            margin-right: 0.5rem;
            color: #4f46e5;
            font-size: 1em;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        .data-table thead {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .data-table th, .data-table td {
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        .data-table tbody tr:hover {
            background-color: #f9fafb;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.6rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .warning-button {
            background-color: #f59e0b; /* amber-500 */
            color: white;
        }
        .warning-button:hover {
            background-color: #d97706; /* amber-600 */
        }
        .pill-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .pill-green {
            background-color: #d1fae5; /* green-100 */
            color: #059669; /* green-600 */
        }
        .info-footer {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
        }
        .info-footer p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    @include('dosen.template.navbar')

    <main class="flex-grow max-w-5xl mx-auto px-4 py-10 w-full">
        <div class="info-card">
            <div class="flex justify-between items-center mb-6">
                <h1 class="section-header !mb-0 !pb-0 !border-none text-2xl">Detail Log Bimbingan</h1>
                <a href="{{ route('dosen.data_log') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Daftar</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-id-card"></i> NIM Mahasiswa</span>
                    <span class="detail-value">{{ $mahasiswa->username }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-user"></i> Nama Mahasiswa</span>
                    <span class="detail-value">{{ $mahasiswa->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-graduation-cap"></i> Program Studi</span>
                    <span class="detail-value">{{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-signal"></i> Status Bimbingan</span>
                    <span class="detail-value"><span class="pill-badge pill-green">Aktif</span></span>
                </div>
            </div>
            
            {{-- Display only the most recent log entry --}}
            @php
                $recentLog = $logs->sortByDesc('waktu_bimbingan')->first();
            @endphp

            @if($recentLog)
            <div class="border-t border-dashed my-6 pt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Detail Sesi Bimbingan Terbaru ({{ $recentLog->waktu_bimbingan ? \Carbon\Carbon::parse($recentLog->waktu_bimbingan)->format('d M Y, H:i') : '' }})</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-handshake"></i> Metode Bimbingan</span>
                        <span class="detail-value">{{ $recentLog->metode_bimbingan }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-calendar-alt"></i> Waktu Bimbingan</span>
                        <span class="detail-value">{{ $recentLog->waktu_bimbingan ? \Carbon\Carbon::parse($recentLog->waktu_bimbingan)->isoFormat('dddd, D MMMM YYYY, HH:mm') : '-' }}</span>
                    </div>
                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fas fa-lightbulb"></i> Topik Bimbingan</span>
                        <span class="detail-value">{{ $recentLog->topik_bimbingan }}</span>
                    </div>
                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fas fa-align-left"></i> Deskripsi</span>
                        <span class="detail-value whitespace-pre-line">{{ $recentLog->deskripsi }}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="border-t border-dashed my-6 pt-6 text-center text-gray-500">
                Belum ada sesi bimbingan terbaru.
            </div>
            @endif
            
            <h2 class="text-lg font-semibold text-gray-700 mb-4 mt-8">Tabel Penilaian Log Bimbingan</h2>
            <div class="border rounded-lg overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Nilai</th>
                            <th>Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- This section will still show all logs in the table --}}
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $mahasiswa->username }}</td>
                            <td>{{ $mahasiswa->name }}</td>
                            <td class="font-medium text-center">{{ $log->nilai }}</td>
                            <td>{{ $log->komentar ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data log bimbingan untuk mahasiswa ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('dosen.data_log') }}" class="action-button warning-button">Keluar</a>
            </div>

            <div class="info-footer">
                <p><strong>Nilai:</strong> Angka evaluasi dari dosen untuk setiap sesi bimbingan. Nilai ini bersifat rahasia dan tidak akan ditampilkan di akun mahasiswa.</p>
                <p><strong>Komentar:</strong> Catatan atau masukan dari dosen mengenai progres mahasiswa dalam sesi bimbingan terkait.</p>
            </div>
        </div>
    </main>


</body>
</html>