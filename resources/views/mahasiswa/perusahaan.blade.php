<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIMMAGANG Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-blue-50 text-gray-800 pt-20">
{{-- Navbar --}}
@include('mahasiswa.template.navbar')

<main class="max-w-7xl mx-auto px-4 md:px-10 py-12">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-800">Daftar Perusahaan</h1>
            <div class="flex space-x-3">
                <form method="GET" action="{{ route('mahasiswa.perusahaan') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/email/kota..."
                           class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r text-sm -ml-px">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ route('mahasiswa.perusahaan') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded text-sm ml-2">
                        Reset
                    </a>
                    @endif
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

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3">Nama Perusahaan</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Telepon</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-center">Profil</th>
                </tr>
                </thead>
                <tbody class="text-gray-600">
                @forelse($companies as $company)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-5 py-3">{{ $company->nama_perusahaan }}</td>
                    <td class="px-5 py-3">{{ $company->email_perusahaan }}</td>
                    <td class="px-5 py-3">{{ $company->telepon ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                            @if($company->status_kerjasama == 'Aktif') bg-green-100 text-green-700
                            @elseif($company->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $company->status_kerjasama }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <button onclick="showCompanyProfile('{{ $company->id }}')"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1 rounded transition duration-200">
                            Lihat Profil
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-4 text-center text-gray-500">
                        @if(request('search'))
                        Tidak ada perusahaan ditemukan untuk pencarian "{{ request('search') }}".
                        @else
                        Belum ada data perusahaan.
                        @endif
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($companies->hasPages())
        <div class="mt-4">
            {{ $companies->links() }}
        </div>
        @endif
    </div>
</main>

<!-- Company Profile Modal -->
<div id="companyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Profil Perusahaan</h3>
            <button onclick="hideCompanyModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="companyProfileContent">
            <div class="p-4 text-center">
                <i class="fas fa-spinner fa-spin text-blue-500"></i> Memuat data perusahaan...
            </div>
        </div>
    </div>
</div>

<script>
    function showCompanyProfile(companyId) {
        // Show loading state immediately
        document.getElementById('companyModal').classList.remove('hidden');
        document.getElementById('companyProfileContent').innerHTML = `
            <div class="p-4 text-center">
                <i class="fas fa-spinner fa-spin text-blue-500"></i> Memuat data perusahaan...
            </div>
        `;

        // Set up headers with CSRF token
        const headers = new Headers({
            'Accept': 'text/html',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        });

        fetch(`/mahasiswa/perusahaan/${companyId}/profile`, {
            method: 'GET',
            headers: headers,
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('companyProfileContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('companyProfileContent').innerHTML = `
                <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Error:</strong>
                    </div>
                    <p class="mt-2">Gagal memuat profil perusahaan (Status: ${error.message})</p>
                    <p class="text-sm mt-1">Silakan refresh halaman dan coba lagi.</p>
                </div>
            `;
            });
    }

    function hideCompanyModal() {
        document.getElementById('companyModal').classList.add('hidden');
    }

    // Close modal when clicking outside content
    document.getElementById('companyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideCompanyModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideCompanyModal();
        }
    });
</script>

{{-- Footer --}}
@include('mahasiswa.template.footer')
</body>
</html>
