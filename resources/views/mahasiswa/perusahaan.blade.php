<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMMAGANG Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-800 pt-20">

{{-- Navbar --}}
@include('mahasiswa.template.navbar')

{{-- Optional Job Card Include --}}
@include('mahasiswa.jobcardCompany')

<div class="pt-20 pb-10 px-4 md:px-10 max-w-7xl mx-auto">
    @forelse ($companies as $company)
    <div class="bg-white rounded-lg shadow-md p-6 mb-10">
        <div class="flex flex-col md:flex-row gap-6">
            {{-- Company Logo --}}
            @if ($company->logo_path)
            <div class="md:w-1/3 flex justify-center items-start">
                <img src="{{ asset($company->logo_path) }}" alt="{{ $company->nama_perusahaan }}" class="max-h-32 object-contain rounded-md">
            </div>
            @endif

            {{-- Company Info --}}
            <div class="md:w-2/3">
                <h2 class="text-2xl font-bold text-blue-800 mb-2">{{ $company->nama_perusahaan }}</h2>
                <p class="text-gray-700 mb-1"><strong>Alamat:</strong> {{ $company->alamat }}</p>
                <p class="text-gray-700 mb-1"><strong>Kota:</strong> {{ $company->kota }}</p>
                <p class="text-gray-700 mb-1"><strong>Provinsi:</strong> {{ $company->provinsi }}</p>
                <p class="text-gray-700 mb-1"><strong>Kode Pos:</strong> {{ $company->kode_pos }}</p>
                <p class="text-gray-700 mb-1"><strong>Telepon:</strong> {{ $company->telepon }}</p>
                <p class="text-gray-700 mb-1"><strong>Email:</strong> <a href="mailto:{{ $company->email_perusahaan }}" class="text-blue-500 hover:underline">{{ $company->email_perusahaan }}</a></p>
                @if ($company->website)
                <p class="text-gray-700 mb-1"><strong>Website:</strong>
                    <a href="{{ $company->website }}" target="_blank" class="text-blue-500 hover:underline">
                        {{ $company->website }}
                    </a>
                </p>
                @endif
                <p class="text-gray-700 mt-4"><strong>Deskripsi:</strong> {{ $company->deskripsi }}</p>
            </div>
        </div>
    </div>
    @empty
    <p class="text-center text-gray-500 italic">No companies found.</p>
    @endforelse
</div>

{{-- Footer --}}
@include('mahasiswa.template.footer')

</body>
</html>
