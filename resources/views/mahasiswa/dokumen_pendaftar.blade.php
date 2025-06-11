<div class="space-y-4 bg-[#F0F8FF] p-4 rounded-lg">
    @foreach($dokumen as $doc)
    <div class="border-b pb-4">
        <div class="flex justify-between items-start">
            <div>
                <h4 class="font-medium">{{ $doc->nama_dokumen }}</h4>
                <p class="text-sm text-gray-500 mt-1">{{ basename($doc->file_path) }}</p>
            </div>
            <span class="text-xs font-medium px-2 py-1 rounded-full
                @if($doc->status_validasi == 'Valid') bg-green-100 text-green-600
                @elseif($doc->status_validasi == 'Tidak Valid') bg-red-100 text-red-500
                @elseif($doc->status_validasi == 'Perlu Revisi') bg-yellow-100 text-yellow-600
                @else bg-gray-100 text-gray-600 @endif">
                {{ $doc->status_validasi }}
            </span>
        </div>
        <div class="mt-2">
            <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
               class="text-blue-600 hover:text-blue-800 text-sm">
                Lihat Dokumen
            </a>
        </div>
    </div>
    @endforeach
</div>
