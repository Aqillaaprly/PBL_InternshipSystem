<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Perusahaan - {{ $company->nama_perusahaan ?? 'Informasi Perusahaan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('perusahaan.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-xl shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-800">Detail Perusahaan: {{ $company->nama_perusahaan ?? 'N/A' }}</h1>
                <a href="{{ route('perusahaan.dashboard') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Dashboard</a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Memastikan $company adalah objek yang valid dan memiliki ID sebelum menampilkan detail --}}
            @if(isset($company) && $company->id)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <strong class="text-gray-700 block mb-1">Nama Perusahaan:</strong>
                        <p class="text-gray-900">{{ $company->nama_perusahaan ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Email Perusahaan:</strong>
                        <p class="text-gray-900">{{ $company->email_perusahaan ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Telepon:</strong>
                        <p class="text-gray-900">{{ $company->telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Website:</strong>
                        @if($company->website)
                            <p class="text-gray-900"><a href="{{ $company->website }}" target="_blank" class="text-blue-500 hover:underline">{{ $company->website }}</a></p>
                        @else
                            <p class="text-gray-900">-</p>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700 block mb-1">Alamat:</strong>
                        <p class="text-gray-900">{{ $company->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Kota:</strong>
                        <p class="text-gray-900">{{ $company->kota ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Provinsi:</strong>
                        <p class="text-gray-900">{{ $company->provinsi ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Kode Pos:</strong>
                        <p class="text-gray-900">{{ $company->kode_pos ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Status Kerjasama:</strong>
                        <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full
                            @if($company->status_kerjasama == 'Aktif') bg-green-100 text-green-700
                            @elseif($company->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $company->status_kerjasama ?? '-' }}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700 block mb-1">Deskripsi:</strong>
                        <p class="text-gray-900 whitespace-pre-line">{{ $company->deskripsi ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700 block mb-1">Logo:</strong>
                        @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo {{ $company->nama_perusahaan ?? 'Perusahaan' }}" class="mt-1 h-24 w-auto rounded border">
                        @else
                            <p class="text-gray-500 mt-1">Logo tidak tersedia.</p>
                        @endif
                    </div>

                    {{-- Informasi Akun Login Terkait --}}
                    @if($company->user)
                        <div class="md:col-span-2 border-t pt-4 mt-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Akun Login Terkait</h3>
                            <div>
                                <strong class="text-gray-700 block mb-1">Username Akun:</strong>
                                <p class="text-gray-900">{{ $company->user->username ?? '-' }}</p>
                            </div>
                            <div class="mt-2">
                                <strong class="text-gray-700 block mb-1">Email Akun:</strong>
                                <p class="text-gray-900">{{ $company->user->email ?? '-' }}</p>
                            </div>
                            <div class="mt-2">
                                <strong class="text-gray-700 block mb-1">Nama Kontak Akun:</strong>
                                <p class="text-gray-900">{{ $company->user->name ?? '-' }}</p>
                            </div>
                             <div>
                                <strong class="text-gray-700 block mb-1">Role Akun:</strong>
                                <p class="text-gray-800">{{ $company->user->role->name ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="text-gray-700 block mb-1">Akun Dibuat:</strong>
                                <p class="text-gray-800">{{ $company->user->created_at ? $company->user->created_at->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="md:col-span-2 border-t pt-4 mt-4">
                             <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Akun Login Terkait</h3>
                            <p class="text-gray-500">Perusahaan ini belum memiliki akun login terkait.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('perusahaan.profil.edit') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md shadow-sm">
                        Edit Perusahaan
                    </a>
                </div>
            @else
                {{-- Pesan jika $company tidak valid atau tidak ditemukan --}}
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p class="font-bold">Data Perusahaan Tidak Ditemukan</p>
                    <p>Tidak dapat menampilkan detail karena data perusahaan tidak valid atau tidak ditemukan.</p>
                </div>
            @endif
        </div>
    </main>

    @include('perusahaan.template.footer')
</body>
</html>