<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $group)
    {
        if ($group == 'mgnome' && $request->user()->hasGroup('gnome', true)) return $next($request);
        if ($request->user()->hasGroup($group)) return $next($request);
        abort(403);
        /*
        $user = Auth::user();
        dd($request->user()->hasRole('elf'));
        //dd($user->is_master_gnome);
        if ($user->group == 'elf') return $next($request);
        else return abort(404); //return redirect('home');
        */
    }
}
