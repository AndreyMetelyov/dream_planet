<?php

namespace App\Http\Middleware;

use Closure;

class CheckGroupYourselfOrMgnome
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->hasGroup('gnome', true)) return $next($request);
        if ($request->user()->id == $request->id) return $next($request);
        abort(403);
    }
}
