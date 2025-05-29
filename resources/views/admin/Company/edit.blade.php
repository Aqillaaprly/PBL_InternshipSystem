<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Perusahaan - {{ $company->nama_perusahaan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @include('admin.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Perusahaan: {{ $company->nama_perusahaan }}</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                    <strong class="font-bold">Oops! Ada beberapa masalah dengan input Anda.</strong>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.perusahaan.update', $company->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Informasi Perusahaan --}}
                    <div>
                        <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $company->nama_perusahaan) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="email_perusahaan" class="block text-sm font-medium text-gray-700 mb-1">Email Resmi Perusahaan <span class="text-red-500">*</span></label>
                        <input type="email" name="email_perusahaan" id="email_perusahaan" value="{{ old('email_perusahaan', $company->email_perusahaan) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon Perusahaan</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $company->telepon) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website <span class="text-red-500">*</span></label>
                        <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" required placeholder="https://example.com" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('alamat', $company->alamat) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <input type="text" name="kota" id="kota" value="{{ old('kota', $company->kota) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $company->provinsi) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $company->kode_pos) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Perusahaan</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('deskripsi', $company->deskripsi) }}</textarea>
                </div>

                <div class="mt-6">
                    <label for="logo_path" class="block text-sm font-medium text-gray-700 mb-1">Logo Perusahaan (Kosongkan jika tidak ingin mengubah)</label>
                    @if($company->logo_path)
                        <div class="mb-2">
                            @if(Str::startsWith($company->logo_path, ['http://', 'https://']))
                                <img src="{{ $company->logo_path }}" alt="Current Logo" class="h-20 w-auto rounded">
                            @else
                                <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Current Logo" class="h-20 w-auto rounded">
                            @endif
                        </div>
                    @endif
                    <input type="file" name="logo_path" id="logo_path" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="mt-6">
                    <label for="status_kerjasama" class="block text-sm font-medium text-gray-700 mb-1">Status Kerjasama <span class="text-red-500">*</span></label>
                    <select name="status_kerjasama" id="status_kerjasama" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Aktif" {{ old('status_kerjasama', $company->status_kerjasama) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ old('status_kerjasama', $company->status_kerjasama) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="Review" {{ old('status_kerjasama', $company->status_kerjasama) == 'Review' ? 'selected' : '' }}>Review</option>
                    </select>
                </div>

                {{-- Informasi Akun User untuk Perusahaan --}}
                {{-- User details are typically associated with the company record --}}
                @if ($company->user)
                <div class="mt-8 border-t pt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun Login Perusahaan</h2>
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $company->user->username) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah username.</p>
                    </div>
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah password.</p>
                    </div>
                    <div class="mt-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                @else
                 <div class="mt-8 border-t pt-6">
                    <p class="text-sm text-yellow-600">Perusahaan ini belum memiliki akun user terkait. Anda bisa membuatnya secara terpisah jika diperlukan.</p>
                 </div>
                @endif


                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin.perusahaan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">Batal</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                        Update Perusahaan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>