<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Pastikan model Role di-import

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect berdasarkan role
        if ($user->role) { // Periksa apakah pengguna memiliki peran
            switch (strtolower($user->role->name)) { // Ubah nama peran menjadi huruf kecil untuk perbandingan case-insensitive
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'mahasiswa':
                    return redirect()->intended(route('mahasiswa.dashboard'));
                case 'perusahaan':
                    return redirect()->intended(route('perusahaan.dashboard'));
                case 'dosen':
                    return redirect()->intended(route('dosen.dashboard'));
                default:
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect('/login')->with('error', 'Peran tidak dikenal atau dasbor tidak tersedia.');
            }
        } else {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('error', 'Pengguna tidak memiliki peran yang valid.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
