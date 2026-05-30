<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /** Maks percobaan login per IP per interval (detik). */
    private const MAX_ATTEMPTS = 5;
    private const DECAY_SECONDS = 60;

    public function showForm(): Response|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ── Rate limiting: tolak jika terlalu banyak percobaan ────────────────
        $rateLimiterKey = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateLimiterKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        // ── Autentikasi ───────────────────────────────────────────────────────
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($rateLimiterKey, self::DECAY_SECONDS);

            return back()->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])->onlyInput('email');
        }

        // ── Cek status akun — blokir user nonaktif / cuti ─────────────────────
        $user = Auth::user();

        if ($user->status_aktif !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();

            $pesan = match ($user->status_aktif) {
                'nonaktif' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                'cuti'     => 'Akun Anda sedang dalam status cuti.',
                default    => 'Akun Anda tidak dapat digunakan saat ini.',
            };

            return back()->withErrors(['email' => $pesan])->onlyInput('email');
        }

        // ── Login berhasil — reset rate limiter & perbarui sesi ──────────────
        RateLimiter::clear($rateLimiterKey);
        $request->session()->regenerate();

        $user->update(['last_login_at' => now()]);

        ActivityLog::record('login', \App\Models\User::class, $user->id);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        ActivityLog::record('logout', \App\Models\User::class, Auth::id());

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
