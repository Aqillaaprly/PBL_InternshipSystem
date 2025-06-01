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
        {{-- Company Header --}}
        <div class="mb-6">
            @if ($company->image)
            <img src="{{ $company->image }}" alt="{{ $company->name }}" class="w-full max-h-64 object-cover rounded-md mb-4">
            @endif
            <h2 class="text-2xl font-bold text-blue-800 mb-2">{{ $company->name }}</h2>
            <p class="text-gray-700">{{ $company->description }}</p>
        </div>

        {{-- Jobs Section --}}
        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $company->name }} Graduate Jobs & Opportunities
            </h3>

            @forelse ($company->lowongans as $job)
            <div class="border rounded-lg p-4 mb-4 bg-gray-50 shadow-sm">
                <h4 class="text-lg font-bold text-blue-700">{{ $job->judul }}</h4>
                <p class="text-sm text-gray-600">Location: {{ $job->lokasi }}</p>
                <p class="text-sm text-gray-600">Start: {{ \Carbon\Carbon::parse($job->tanggal_mulai)->format('d M Y') }}</p>
                <p class="text-sm text-gray-600">End: {{ \Carbon\Carbon::parse($job->tanggal_tutup)->format('d M Y') }}</p>
                @if ($company->website)
                <a href="{{ $company->website }}" target="_blank" class="inline-block mt-2 text-blue-500 hover:underline">
                    Visit Website
                </a>
                @endif
            </div>
            @empty
            <p class="text-gray-500 italic">No job listings available for this company.</p>
            @endforelse
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
