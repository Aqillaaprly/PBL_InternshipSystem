    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Detail Perusahaan - {{ $company->nama_perusahaan }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-blue-50 text-gray-800">
        @include('admin.template.navbar')

        <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
            <div class="bg-white p-8 rounded-xl shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-blue-800">Detail Perusahaan: {{ $company->nama_perusahaan }}</h1>
                    <a href="{{ route('admin.perusahaan.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Perusahaan</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <strong class="text-gray-700 block mb-1">Nama Perusahaan:</strong>
                        <p class="text-gray-900">{{ $company->nama_perusahaan }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Email Perusahaan:</strong>
                        <p class="text-gray-900">{{ $company->email_perusahaan }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Telepon:</strong>
                        <p class="text-gray-900">{{ $company->telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block mb-1">Website:</strong>
                        <p class="text-gray-900"><a href="{{ $company->website }}" target="_blank" class="text-blue-500 hover:underline">{{ $company->website }}</a></p>
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
                            {{ $company->status_kerjasama }}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700 block mb-1">Deskripsi:</strong>
                        <p class="text-gray-900 whitespace-pre-line">{{ $company->deskripsi ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700 block mb-1">Logo:</strong>
                        @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo {{ $company->nama_perusahaan }}" class="mt-1 h-24 w-auto rounded">
                        @else
                            <p class="text-gray-500 mt-1">Logo tidak tersedia.</p>
                        @endif
                    </div>

                    @if($company->user)
                    <div class="md:col-span-2 border-t pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Akun Login Terkait</h3>
                        <div>
                            <strong class="text-gray-700 block mb-1">Username Akun:</strong>
                            <p class="text-gray-900">{{ $company->user->username }}</p>
                        </div>
                        <div class="mt-2">
                            <strong class="text-gray-700 block mb-1">Email Akun:</strong>
                            <p class="text-gray-900">{{ $company->user->email }}</p>
                        </div>
                         <div class="mt-2">
                            <strong class="text-gray-700 block mb-1">Nama Akun:</strong>
                            <p class="text-gray-900">{{ $company->user->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    {{-- <a href="{{ route('admin.Company.edit', $company->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md">
                        Edit Perusahaan
                    </a> --}}
                    {{-- <form action="{{ route('admin.Company.destroy', $company->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus perusahaan ini? Aksi ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md">
                            Hapus Perusahaan
                        </button>
                    </form> --}}
                </div>
            </div>
        </main>

        @include('admin.template.footer')
    </body>
    </html>
    