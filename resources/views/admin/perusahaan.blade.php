<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Perusahaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f0f6ff]">
    @include('admin.template.navbar')
    
<main class="max-w-screen-xl mx-auto px-8 py-12 mt-6">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Perusahaan</h1>
            <div class="flex space-x-3">
                <input type="text" placeholder="Search" class="border border-gray-300 rounded px-4 py-2" />
                <button class="border border-gray-300 px-4 py-2 rounded">Filter</button>
                <button class="bg-blue-600 text-white px-5 py-2 rounded">+ Tambah</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Nama Perusahaan</th>
                    <th class="px-5 py-3">Posisi</th>
                    <th class="px-5 py-3">Kuota Tersedia</th>
                    <th class="px-5 py-3">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($companies as $index => $company)
                    <tr class="border-b">
                        <td class="px-5 py-3">{{ $companies->firstItem() + $index }}</td>
                        <td class="px-5 py-3">{{ $company->nama_perusahaan }}</td>
                        <td class="px-5 py-3">{{ $company->email_perusahaan }}</td>
                        <td class="px-5 py-3">{{ $company->telepon }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full
                                @if($company->status_kerjasama == 'Aktif') bg-green-100 text-green-600 @elseif($company->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-500 @else bg-yellow-100 text-yellow-600 @endif">
                                {{ $company->status_kerjasama }}
                            </span>
                        </td>
                        <td class="px-5 py-3 space-x-1">
                            {{-- Ganti link ke route yang sesuai --}}
                            <a href="{{-- route('admin.perusahaan.show', $company->id) --}}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">Show</a>
                            <a href="{{-- route('admin.perusahaan.edit', $company->id) --}}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                            <form action="{{-- route('admin.perusahaan.destroy', $company->id) --}}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-3 text-center text-gray-500">Tidak ada data perusahaan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="flex justify-between items-center mt-6">
            <p class="text-sm text-gray-500">Page</p>
            <div class="space-x-2">
                <button class="px-3 py-1 border rounded">1</button>
                <button class="px-3 py-1 border rounded">2</button>
                <button class="px-3 py-1 border rounded">3</button>
            </div>
            <p class="text-sm text-gray-500">Result</p>
        </div>
    </div>
</main>

</body>
</html>
