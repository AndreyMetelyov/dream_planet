<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Gem;

class CheckGroupYourself
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
        $gemId = $request->id;
        $owner = $request->user();
        $gem = Gem::find($gemId);

        if ($owner->id == $gem->owner) return $next($request);
        abort(403);
    }
}
