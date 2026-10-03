<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $this->rememberNext($request);

        return view('site.auth.login');
    }

    /** Remember a local ?next=/path so guests return to where they were after login/register. */
    private function rememberNext(Request $request): void
    {
        $next = $request->query('next');

        if (is_string($next) && str_starts_with($next, '/') && ! str_starts_with($next, '//')) {
            $request->session()->put('url.intended', url($next));
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard'));
    }

    public function showRegister(Request $request)
    {
        $this->rememberNext($request);

        return view('site.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($data + ['role' => User::ROLE_STUDENT]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('success', 'Welcome aboard! Your account has been created.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
