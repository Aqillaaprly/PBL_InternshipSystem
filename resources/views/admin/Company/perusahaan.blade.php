<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Perusahaan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

<main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-800">Manajemen Perusahaan</h1>
            <div class="flex space-x-3">
                <form method="GET" action="{{ route('admin.perusahaan.index') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email/kota..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r text-sm -ml-px">Cari</button>
                </form>
                <a href="{{ route('admin.perusahaan.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700 whitespace-nowrap">+ Tambah Perusahaan</a>
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
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Nama Perusahaan</th>
                    <th class="px-5 py-3">Email Perusahaan</th>
                    <th class="px-5 py-3">Telepon</th>
                    <th class="px-5 py-3">Status Kerjasama</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
                </thead>
                <tbody class="text-gray-600">
                @forelse($companies as $index => $company_item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-5 py-3 text-center">{{ $companies->firstItem() + $index }}</td>
                        <td class="px-5 py-3">{{ $company_item->nama_perusahaan }}</td>
                        <td class="px-5 py-3">{{ $company_item->email_perusahaan }}</td>
                        <td class="px-5 py-3">{{ $company_item->telepon ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                @if($company_item->status_kerjasama == 'Aktif') bg-green-100 text-green-700
                                @elseif($company_item->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ $company_item->status_kerjasama }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex item-center justify-center space-x-1">
                                {{-- Pastikan $company_item->id valid --}}
                                <a href="{{ route('admin.perusahaan.show', $company_item->id) }}" class="bg-sky-100 text-sky-600 text-xs font-medium px-3 py-1 rounded hover:bg-sky-200">Show</a>
                                <a href="{{ route('admin.perusahaan.edit', $company_item->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                                <form action="{{ route('admin.perusahaan.destroy', $company_item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus perusahaan ini? Menghapus perusahaan ini juga akan menghapus user terkait jika ada.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-4 text-center text-gray-500">
                             @if(request('search'))
                                Tidak ada data perusahaan ditemukan untuk pencarian "{{ request('search') }}".
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
        <div class="mt-6">
            {{ $companies->links() }}
        </div>
        @endif
    </div>
</main>
@include('admin.template.footer')
</body>
</html>