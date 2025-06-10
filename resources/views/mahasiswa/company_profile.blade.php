<div class="space-y-4">
    <div class="flex items-center space-x-4">
        @if($company->logo_path)
        <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo Perusahaan" class="w-20 h-20 object-cover rounded">
        @endif
        <div>
            <h2 class="text-xl font-bold">{{ $company->nama_perusahaan }}</h2>
            <p class="text-gray-600">{{ $company->email_perusahaan }}</p>
            <p class="text-gray-600">{{ $company->telepon }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="font-semibold">Alamat</h3>
            <p>{{ $company->alamat ?? '-' }}, {{ $company->kota ?? '-' }}, {{ $company->provinsi ?? '-' }} {{ $company->kode_pos ?? '' }}</p>
        </div>
        <div>
            <h3 class="font-semibold">Kontak</h3>
            <p>Telepon: {{ $company->telepon ?? '-' }}</p>
            <p>Website: {{ $company->website ?: '-' }}</p>
        </div>
    </div>

    <div>
        <h3 class="font-semibold">Deskripsi Perusahaan</h3>
        <p class="text-gray-700 whitespace-pre-line">{{ $company->deskripsi ?? 'Tidak ada deskripsi' }}</p>
    </div>

    <div>
        <h3 class="font-semibold">Status Kerjasama</h3>
        <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
            @if($company->status_kerjasama == 'Aktif') bg-green-100 text-green-700
            @elseif($company->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-700
            @else bg-yellow-100 text-yellow-700 @endif">
            {{ $company->status_kerjasama }}
        </span>
    </div>

    @if($company->lowongans->count() > 0)
    <div>
        <h3 class="font-semibold">Lowongan Tersedia</h3>
        <ul class="list-disc pl-5 space-y-1">
            @foreach($company->lowongans as $lowongan)
            <li>{{ $lowongan->judul }} ({{ $lowongan->lokasi }})</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
