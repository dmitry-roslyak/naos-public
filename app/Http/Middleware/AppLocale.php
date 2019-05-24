<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Traits\Utility;

class AppLocale
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
        $user = \Auth::user();
        $locale = $user ? $user : Utility::locale();
        \App::setLocale($locale['language']);

        return $next($request);
    }
}
