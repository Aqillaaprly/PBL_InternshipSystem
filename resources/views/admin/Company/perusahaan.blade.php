<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Perusahaan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-aktif {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-800 */
        }
        .status-tidak-aktif {
            background-color: #fee2e2; /* red-100 */
            color: #991b1b; /* red-800 */
        }
        .status-dalam-pembahasan { /* Mengganti 'Review' menjadi 'Dalam Pembahasan' agar sesuai data */
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

<body class="bg-blue-50 text-gray-800">

    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-4 sm:mb-0">Manajemen Data Perusahaan</h1>
                <a href="{{ route('admin.perusahaan.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-md text-sm hover:bg-blue-700 whitespace-nowrap shadow-sm sm:ml-auto">+ Tambah Perusahaan</a>
            </div>
            
            {{-- Form Pencarian Tunggal --}}
            <form method="GET" action="{{ route('admin.perusahaan.index') }}" class="mb-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-grow">
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari Perusahaan</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama, email, username, kota, provinsi..." class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm shadow-sm">Cari</button>
                    </div>
                </div>
            </form>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

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
                            <th class="px-5 py-3">Provinsi</th>
                            <th class="px-5 py-3">Status Kerjasama</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="overflow-x: auto; text-gray-600 divide-y divide-gray-200">
                        @forelse ($companies as $index => $company)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-center align-middle">{{ $companies->firstItem() + $index }}</td>
                                <td class="px-5 py-4 align-middle">
                                    @if ($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                                        <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo {{ $company->nama_perusahaan }}" class="h-10 w-10 object-contain rounded-md">
                                    @else
                                        <div class="h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center text-gray-400 text-xs">No Logo</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-medium text-gray-900 align-middle">{{ $company->nama_perusahaan }}</td>
                                <td class="px-5 py-4 align-middle">{{ $company->email_perusahaan }}</td>
                                <td class="px-5 py-4 align-middle">{{ $company->telepon ?? '-' }}</td>
                                <td class="px-5 py-4 align-middle">{{ $company->kota ?? '-' }}</td>
                                <td class="px-5 py-4 align-middle">{{ $company->provinsi ?? '-' }}</td>
                                <td class="px-5 py-4 text-center align-middle">
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                        @if ($company->status_kerjasama == 'Aktif') status-aktif
                                        @elseif ($company->status_kerjasama == 'Non-Aktif') status-tidak-aktif
                                        @else status-dalam-pembahasan @endif"> 
                                        {{ $company->status_kerjasama }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center align-middle">
                                    <div class="flex item-center justify-center space-x-2">
                                        <a href="{{ route('admin.perusahaan.show', $company->id) }}" class="text-xs bg-sky-100 text-sky-600 hover:bg-sky-200 px-3 py-1.5 rounded-md font-medium">Detail</a>
                                        <a href="{{ route('admin.perusahaan.edit', $company->id) }}" class="text-xs bg-yellow-100 text-yellow-600 hover:bg-yellow-200 px-3 py-1.5 rounded-md font-medium">Ubah</a>
                                        <form action="{{ route('admin.perusahaan.destroy', $company->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini? Ini juga akan menghapus lowongan dan akun login terkait jika ada.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-3 py-1.5 rounded-md font-medium">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-4 text-center text-gray-500">
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

            @if ($companies->hasPages())
                <div class="mt-6">
                    {{ $companies->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </main>

    @include('admin.template.footer')

</body>
</html>