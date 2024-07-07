<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;  // Ensure the correct Request class is imported
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle user authenticated event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect($this->defaultRedirectUri());
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect($this->defaultRedirectUri());
        }

        return view('auth.login');
    }

    /**
     * Determine the default redirect URI after login based on user roles.
     *
     * @return string
     */
    protected function defaultRedirectUri(): string
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            return route('super.home');
        } elseif ($user->hasRole('admin')) {
            return route('admin.home');
        } elseif ($user->hasRole('user')) {
            return route('home');
        }

        // Fallback to general dashboard or home route if no specific role is found
        foreach (['dashboard', 'home'] as $uri) {
            if (Route::has($uri)) {
                return route($uri);
            }
        }

        $routes = Route::getRoutes()->get('GET');

        foreach (['dashboard', 'home'] as $uri) {
            if (isset($routes[$uri])) {
                return '/' . $uri;
            }
        }

        return '/';
    }
}
