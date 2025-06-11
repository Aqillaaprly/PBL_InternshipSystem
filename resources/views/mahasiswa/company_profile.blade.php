<div class="space-y-6">
    <!-- Company Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pb-4 border-b border-gray-200">
        <!-- Logo -->
        <div class="flex-shrink-0">
            @if($company->logo_path)
            <img src="{{ asset('storage/' . $company->logo_path) }}"
                 alt="Logo {{ $company->nama_perusahaan }}"
                 class="w-24 h-24 rounded-lg object-cover border border-gray-200">
            @else
            <div class="w-24 h-24 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                <i class="fas fa-building text-gray-400 text-3xl"></i>
            </div>
            @endif
        </div>

        <!-- Basic Info -->
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">{{ $company->nama_perusahaan }}</h1>

            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-2 text-sm">
                @if($company->email_perusahaan)
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    {{ $company->email_perusahaan }}
                </div>
                @endif

                @if($company->telepon)
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-phone-alt mr-2 text-blue-500"></i>
                    {{ $company->telepon }}
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
            <span class="px-3 py-1 rounded-full text-sm font-medium {{
                $company->status_kerjasama == 'Aktif' ? 'bg-green-100 text-green-800' :
                ($company->status_kerjasama == 'Non-Aktif' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
            }}">
                {{ $company->status_kerjasama ?? 'Review' }}
            </span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Company Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Address Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                    Alamat Perusahaan
                </h2>
                <div class="text-gray-700 space-y-1">
                    <p class="whitespace-pre-line">{{ $company->alamat }}</p>
                    <p>
                        {{ $company->kota }}{{ $company->kota && $company->provinsi ? ', ' : '' }}
                        {{ $company->provinsi }}
                        {{ $company->kode_pos ? ' - ' . $company->kode_pos : '' }}
                    </p>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Deskripsi Perusahaan
                </h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($company->deskripsi)) !!}
                </div>
            </div>
        </div>

        <!-- Right Column - Additional Info -->
        <div class="space-y-6">
            <!-- Company Metadata -->
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-database text-blue-500 mr-2"></i>
                    Informasi Perusahaan
                </h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ID Perusahaan:</span>
                        <span class="font-medium">{{ $company->id }}</span>
                    </div>
                    @if($company->user)
                    <div class="flex justify-between">
                        <span class="text-gray-500">User ID:</span>
                        <span class="font-medium">{{ $company->user_id }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Bergabung:</span>
                        <span class="font-medium">{{ $company->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Terakhir Diperbarui:</span>
                        <span class="font-medium">{{ $company->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Active Job Openings -->
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center justify-between">
                    <span>
                        <i class="fas fa-briefcase text-blue-500 mr-2"></i>
                        Lowongan Tersedia
                    </span>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $company->lowongans->count() }} aktif
                    </span>
                </h2>

                @if($company->lowongans->count() > 0)
                <ul class="space-y-3">
                    @foreach($company->lowongans as $lowongan)
                    <li class="border-b border-gray-100 pb-3 last:border-b-0 last:pb-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $lowongan->judul }}</h3>
                                <p class="text-sm text-gray-500">{{ $lowongan->lokasi }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{
                                        $lowongan->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                    }}">
                                        {{ $lowongan->status }}
                                    </span>
                        </div>
                        <div class="mt-1 text-xs text-gray-400">
                            Dibuka {{ $lowongan->created_at->diffForHumans() }}
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                    <p>Tidak ada lowongan tersedia saat ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
