// app/Http/Controllers/Mahasiswa/MahasiswaController.php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BimbinganMagang;

class MahasiswaController extends Controller
{
    public function lihatPembimbing()
    {
        $userMahasiswa = Auth::user();
        $bimbinganAktif = BimbinganMagang::with(['pembimbing.user', 'company'])
                                        ->where('mahasiswa_user_id', $userMahasiswa->id)
                                        ->where('status_bimbingan', 'Aktif')
                                        ->first();

        return view('mahasiswa.dosen_pembimbing', compact('bimbinganAktif'));
    }
}

File Blade dosen_pembimbing.blade.php:
HTML
{{-- Di resources/views/mahasiswa/dosen_pembimbing.blade.php --}}
 <!DOCTYPE html>
<html lang="id">
<head>
    <title>Dosen Pembimbing Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('mahasiswa.template.navbar') {{-- Sesuaikan path navbar mahasiswa --}}
    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <h1>Informasi Bimbingan Magang Aktif</h1>
        @if($bimbinganAktif && $bimbinganAktif->pembimbing && $bimbinganAktif->pembimbing->user)
            <p>Dosen Pembimbing: {{ $bimbinganAktif->pembimbing->user->name }} (NIP: {{ $bimbinganAktif->pembimbing->nip }})</p>
            <p>Email Pembimbing: {{ $bimbinganAktif->pembimbing->email_institusi }}</p>
            <p>Tempat Magang: {{ $bimbinganAktif->company->nama_perusahaan ?? 'Belum ada info perusahaan' }}</p>
            <p>Periode: {{ $bimbinganAktif->periode_magang }}</p>
        @else
            <p>Anda belum memiliki dosen pembimbing yang aktif untuk periode magang saat ini.</p>
        @endif
    </main>
    @include('mahasiswa.template.footer') {{-- Sesuaikan path footer mahasiswa --}}
</body>
</html>
