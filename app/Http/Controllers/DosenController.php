namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function mahasiswaBimbingan()
    {
        $userDosen = Auth::user();
        // Menggunakan relasi yang sudah dibuat di model User
        $mahasiswaBimbingan = $userDosen->mahasiswaYangDibimbing()
                                    ->with(['mahasiswa.detailMahasiswa', 'company'])
                                    ->where('status_bimbingan', 'Aktif') // Hanya yang aktif
                                    ->paginate(10);

        return view('dosen.mahasiswa_bimbingan', compact('mahasiswaBimbingan'));
    }
}

File Blade mahasiswa_bimbingan.blade.php:
HTML
{{-- Di resources/views/dosen/mahasiswa_bimbingan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Mahasiswa Bimbingan Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('dosen.template.navbar') {{-- Sesuaikan path navbar dosen --}}
    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <h1>Mahasiswa Bimbingan Aktif</h1>
        @if($mahasiswaBimbingan->count() > 0)
            <ul>
                @foreach($mahasiswaBimbingan as $bimbingan)
                    <li>
                        {{ $bimbingan->mahasiswa->name }} ({{ $bimbingan->mahasiswa->username }})
                        - Magang di: {{ $bimbingan->company->nama_perusahaan ?? 'Belum ada info perusahaan' }}
                        - Periode: {{ $bimbingan->periode_magang }}
                    </li>
                @endforeach
            </ul>
            {{ $mahasiswaBimbingan->links() }}
        @else
            <p>Belum ada mahasiswa bimbingan yang aktif.</p>
        @endif
    </main>
    @include('dosen.template.footer') {{-- Sesuaikan path footer dosen --}}
</body>
</html>
