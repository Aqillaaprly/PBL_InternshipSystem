<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Perusahaan - {{ $company->nama_perusahaan ?? 'Perusahaan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-blue-50 text-gray-800 pt-20">
{{-- Navbar --}}
@include('mahasiswa.template.navbar')

<main class="max-w-7xl mx-auto px-4 md:px-10 py-12">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('mahasiswa.perusahaan.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-building mr-2"></i>
                    Daftar Perusahaan
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Profil Perusahaan</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="space-y-6">
            <!-- Company Header Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pb-6 border-b border-gray-200">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                    <img src="{{ asset('storage/' . $company->logo_path) }}"
                         alt="Logo {{ $company->nama_perusahaan }}"
                         class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                    @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($company->nama_perusahaan) }}&size=96&background=2563EB&color=fff"
                         alt="Logo Default"
                         class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                    @endif
                </div>

                <!-- Basic Info -->
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $company->nama_perusahaan ?? 'Nama Perusahaan' }}</h1>

                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                        @if($company->email_perusahaan)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                            <span>{{ $company->email_perusahaan }}</span>
                        </div>
                        @endif

                        @if($company->telepon)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone-alt mr-2 text-blue-500"></i>
                            <span>{{ $company->telepon }}</span>
                        </div>
                        @endif

                        @if($company->website)
                        <div class="flex items-center">
                            <i class="fas fa-globe mr-2 text-blue-500"></i>
                            <a href="{{ $company->website }}" target="_blank"
                               class="text-blue-600 hover:underline break-all">
                                {{ parse_url($company->website, PHP_URL_HOST) ?? $company->website }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="sm:self-start">
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{
                        $company->status_kerjasama == 'Aktif' ? 'bg-green-100 text-green-800' :
                        ($company->status_kerjasama == 'Non-Aktif' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
                    }}">
                        {{ $company->status_kerjasama ?? 'Review' }}
                    </span>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md" role="alert">
                <div class="flex">
                    <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3 text-green-500"></i></div>
                    <div>
                        <p class="font-bold">Sukses!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Company Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Address Card -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-3"></i>
                            Alamat Perusahaan
                        </h2>
                        <div class="text-gray-700 space-y-2">
                            <p class="whitespace-pre-line leading-relaxed">{{ $company->alamat ?? 'Alamat tidak tersedia' }}</p>
                            <p class="font-medium">
                                {{ $company->kota }}{{ $company->kota && $company->provinsi ? ', ' : '' }}
                                {{ $company->provinsi }}
                                {{ $company->kode_pos ? ' - ' . $company->kode_pos : '' }}
                            </p>
                        </div>
                    </div>

                    <!-- About Card -->
                    @if($company->about)
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-quote-left text-blue-500 mr-3"></i>
                            Tentang Perusahaan
                        </h2>
                        <p class="text-gray-700 leading-relaxed">
                            @if(filter_var($company->about, FILTER_VALIDATE_URL))
                            <a href="{{ $company->about }}" target="_blank" class="text-blue-600 hover:underline break-all">
                                {{ $company->about }}
                            </a>
                            @else
                            {{ $company->about }}
                            @endif
                        </p>
                    </div>
                    @endif

                    <!-- Description Card -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            Deskripsi Perusahaan
                        </h2>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($company->deskripsi ?? 'Tidak ada deskripsi tersedia')) !!}
                        </div>
                    </div>
                </div>

                <!-- Right Column - Additional Info -->
                <div class="space-y-6">
                    <!-- Company Metadata -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-database text-blue-500 mr-3"></i>
                            Informasi Perusahaan
                        </h2>
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">ID Perusahaan:</span>
                                <span class="font-medium text-gray-900">{{ $company->id }}</span>
                            </div>
                            @if($company->user)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">User ID:</span>
                                <span class="font-medium text-gray-900">{{ $company->user_id }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Bergabung:</span>
                                <span class="font-medium text-gray-900">{{ $company->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Terakhir Diperbarui:</span>
                                <span class="font-medium text-gray-900">{{ $company->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Active Job Openings -->
                    @if(isset($company->lowongans))
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center justify-between">
                            <span class="flex items-center">
                                <i class="fas fa-briefcase text-blue-500 mr-3"></i>
                                Lowongan Tersedia
                            </span>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                {{ $company->lowongans->count() }} aktif
                            </span>
                        </h2>

                        @if($company->lowongans->count() > 0)
                        <div class="space-y-4">
                            @foreach($company->lowongans as $lowongan)
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 text-sm">{{ $lowongan->judul }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ $lowongan->lokasi }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full font-medium {{
                                                $lowongan->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                            }}">
                                                {{ $lowongan->status }}
                                            </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    Dibuka {{ $lowongan->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p class="text-sm">Tidak ada lowongan tersedia saat ini</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="pt-6 border-t border-gray-200">
                <a href="{{ route('mahasiswa.perusahaan.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar Perusahaan
                </a>
            </div>
        </div>
    </div>
</main>

{{-- Footer --}}
@include('mahasiswa.template.footer')
</body>
</html>
