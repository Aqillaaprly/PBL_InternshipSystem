<a href="{{ route('perusahaan.pendaftar') }}" class="cursor-pointer block">
    <div class="bg-white p-8 rounded-xl shadow-lg text-center hover:bg-blue-50 hover:shadow-xl transition-all duration-300 border border-gray-100">
        <div class="mb-4">
            <p class="text-4xl font-bold text-blue-600 mb-2">{{ $jumlahTotalPendaftar ?? 0 }}</p>
            <p class="text-lg text-gray-700 font-medium">Total Pendaftar</p>
        </div>
    </div>
</a>

