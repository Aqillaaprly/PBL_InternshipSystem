<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Perusahaan - SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .status-aktif {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-800 */
        }
        .status-tidak-aktif {
            background-color: #fee2e2; /* red-100 */
            color: #991b1b; /* red-800 */
        }
        .status-dalam-pembahasan {
            background-color: #fef3c7; /* yellow-100 */
            color: #92400e; /* yellow-800 */
        }
        /* Ensure table cells do not wrap text */
        .min-w-full th, .min-w-full td {
            white-space: nowrap;
        }
        /* Add horizontal scroll if content overflows */
        .overflow-x-auto {
            overflow-x: auto;
        }
    </style>
</head>

<body class="bg-blue-50 text-gray-800 pt-20">
{{-- Navbar --}}
@include('mahasiswa.template.navbar')

<main class="max-w-7xl mx-auto px-4 md:px-10 py-12">
    <div class="bg-white p-8 rounded-xl shadow-lg">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-4 sm:mb-0">Daftar Perusahaan</h1>
        </div>

        {{-- Enhanced Search Form --}}
        <form method="GET" action="{{ route('mahasiswa.perusahaan.index') }}" class="mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Perusahaan</label>
                    <input type="text" name="search" id="search"
                           value="{{ request('search') }}"
                           placeholder="Nama, email, kota, provinsi..."
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm shadow-sm transition duration-200">
                        <i class="fas fa-search mr-1"></i>
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('mahasiswa.perusahaan.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm transition duration-200">
                        <i class="fas fa-times mr-1"></i>
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Success and Error Messages --}}
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3 text-green-500"></i></div>
                <div>
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-exclamation-circle fa-lg mr-3 text-red-500"></i></div>
                <div>
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Enhanced Table with Logo Column --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Logo</th>
                    <th class="px-5 py-3">Nama Perusahaan</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Telepon</th>
                    <th class="px-5 py-3">Kota</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-center">Profil</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-200">
                @forelse($companies as $index => $company)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-5 py-4 text-center align-middle">{{ $companies->firstItem() + $index }}</td>
                    <td class="px-5 py-4 align-middle">
                        @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                        <img src="{{ asset('storage/' . $company->logo_path) }}"
                             alt="Logo {{ $company->nama_perusahaan }}"
                             class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                        @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($company->nama_perusahaan) }}&size=48&background=2563EB&color=fff"
                             alt="Logo Default"
                             class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                        @endif
                    </td>
                    <td class="px-5 py-4 font-medium text-gray-900 align-middle">
                        <div class="max-w-xs">
                            <p class="font-semibold truncate">{{ $company->nama_perusahaan }}</p>
                            @if($company->kota || $company->provinsi)
                            <p class="text-xs text-gray-500 truncate">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $company->kota }}{{ $company->kota && $company->provinsi ? ', ' : '' }}{{ $company->provinsi }}
                            </p>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 align-middle">
                        <span class="text-sm">{{ $company->email_perusahaan }}</span>
                    </td>
                    <td class="px-5 py-4 align-middle">
                        <span class="text-sm">{{ $company->telepon ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-4 align-middle">
                        <span class="text-sm">{{ $company->kota ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center align-middle">
                        <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                            @if($company->status_kerjasama == 'Aktif') status-aktif
                            @elseif($company->status_kerjasama == 'Non-Aktif') status-tidak-aktif
                            @else status-dalam-pembahasan @endif">
                            {{ $company->status_kerjasama ?? 'Review' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center align-middle">
                        <a href="{{ route('mahasiswa.perusahaan.show', $company->id) }}"
                           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-md transition duration-200 shadow-sm">
                            <i class="fas fa-eye mr-1"></i>
                            Lihat Profil
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-building text-4xl text-gray-300 mb-3"></i>
                            @if(request('search'))
                            <p class="text-sm">Tidak ada perusahaan ditemukan untuk pencarian "<strong>{{ request('search') }}</strong>".</p>
                            <p class="text-xs text-gray-400 mt-1">Coba gunakan kata kunci yang berbeda atau reset pencarian.</p>
                            @else
                            <p class="text-sm">Belum ada data perusahaan yang tersedia.</p>
                            <p class="text-xs text-gray-400 mt-1">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Enhanced Pagination --}}
        @if($companies->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $companies->firstItem() }} hingga {{ $companies->lastItem() }} dari {{ $companies->total() }} perusahaan
            </div>
            <div>
                {{ $companies->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</main>

{{-- Footer --}}
@include('mahasiswa.template.footer')
</body>
</html>
