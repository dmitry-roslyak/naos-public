<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Traits\Utility;

class Markup
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
        if($request->is('detail/*')) {
            preg_match('/[0-9]+/',$request->path(), $id);
            $product = \App\Product::with('specs', 'discount', 'user_rating')->where('id', $id)->first();
            Utility::OpenGraph($product);
            Utility::ld_json($product);
        }

        return $next($request);
    }
}
