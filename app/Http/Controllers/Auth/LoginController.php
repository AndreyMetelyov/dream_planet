<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        $user->last_login_at = DB::raw('CURRENT_TIMESTAMP');
        $user->save();
        if ($request->user()->group == 'elf') return redirect()->route('user', $user->id);
        if ($request->user()->group == 'gnome' && $user->is_master_gnome) return redirect()->route('gems');
        if ($request->user()->group == 'gnome' && !$user->is_master_gnome) return redirect()->route('addGemFormView');
        return redirect(RouteServiceProvider::HOME);
    }
}
