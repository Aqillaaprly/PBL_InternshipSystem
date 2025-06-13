<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Pendaftar - {{ $pendaftar->user->name ?? ($pendaftar->user->username ?? 'Pendaftar Tidak Ditemukan') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: Add some smooth scroll behavior for better UX */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-5xl mx-auto px-4 py-10 mt-20">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6">
                <h1 class="text-2xl sm:text-3xl font-bold">
                    Dokumen Pendaftar: {{ $pendaftar->user->name ?? $pendaftar->user->username }}
                </h1>
                <p class="text-sm mt-1">
                    Melamar sebagai <strong>{{ $pendaftar->lowongan->judul ?? 'N/A' }}</strong>
                    @if($pendaftar->lowongan && $pendaftar->lowongan->company)
                        di {{ $pendaftar->lowongan->company->nama_perusahaan ?? 'N/A' }}
                    @endif
                </p>
                <p class="text-sm mt-1">
                    Status Lamaran:
                    <span class="font-semibold
                        @if ($pendaftar->status_lamaran == 'Diterima') text-green-300
                        @elseif ($pendaftar->status_lamaran == 'Ditolak') text-red-300
                        @elseif ($pendaftar->status_lamaran == 'Pending') text-yellow-300
                        @elseif ($pendaftar->status_lamaran == 'Wawancara') text-blue-300
                        @elseif ($pendaftar->status_lamaran == 'Ditinjau') text-indigo-300
                        @else text-gray-300 @endif">
                            {{ $pendaftar->status_lamaran }}
                    </span>
                </p>
            </div>

            <div class="p-6 sm:p-8">
                <div class="mb-6">
                    <a href="{{ route('admin.pendaftar.index') }}" class="text-sm text-blue-600 hover:underline">
                        &larr; Kembali ke Daftar Pendaftar
                    </a>
                </div>

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if (session('info'))
                    <div class="bg-blue-100 text-blue-800 p-4 rounded mb-4">{{ session('info') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                        <strong>Oops! Ada beberapa masalah:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Dokumen --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Validasi Dokumen</h2>

                @if($pendaftar->dokumenPendaftars && $pendaftar->dokumenPendaftars->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama Dokumen</th>
                                    <th class="px-4 py-3 text-left">File</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-200">
                                @foreach($pendaftar->dokumenPendaftars->sortBy('nama_dokumen') as $dokumen)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $dokumen->nama_dokumen }}</td>
                                    <td class="px-4 py-3">
                                        @if($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path))
                                            <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                                {{ basename($dokumen->file_path) }}
                                            </a>
                                        @else
                                            <span class="text-red-500">File tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                                            @if($dokumen->status_validasi == 'Valid') bg-green-100 text-green-800
                                            @elseif($dokumen->status_validasi == 'Tidak Valid') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $dokumen->status_validasi }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center space-y-1">
                                        {{-- Update Form --}}
                                        <form action="{{ route('admin.pendaftar.dokumen.updateStatus', [$pendaftar->id, $dokumen->id]) }}" method="POST" class="inline-flex items-center space-x-2 scroll-form">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status_validasi" class="text-xs border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                                <option value="Valid" {{ $dokumen->status_validasi == 'Valid' ? 'selected' : '' }}>Valid</option>
                                                <option value="Tidak Valid" {{ $dokumen->status_validasi == 'Tidak Valid' ? 'selected' : '' }}>Tidak Valid</option>
                                                <option value="Menunggu" {{ $dokumen->status_validasi == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            </select>
                                            <button type="submit" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md">Update</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-4">Belum ada dokumen yang diunggah.</p>
                @endif
            </div>
        </div>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.scroll-form');

            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    // Store current scroll position before submission
                    sessionStorage.setItem('scrollPosition', window.scrollY);
                });
            });

            // Restore scroll position after page load
            const savedScrollPosition = sessionStorage.getItem('scrollPosition');
            if (savedScrollPosition) {
                window.scrollTo(0, parseInt(savedScrollPosition));
                sessionStorage.removeItem('scrollPosition'); // Clean up
            }
        });
    </script>
</body>
</html> 