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
        $id = $request->session()->pull('id');
        if(!empty($id)) {
        // if(!empty($request['id'])) {
            $product = \App\Product::with('specs', 'discount', 'user_rating')->where('id', $id)->first();
            // $product = \App\Product::with('specs', 'discount', 'user_rating')->where('id', $request['id'])->first();
            Utility::OpenGraph($product);
            Utility::ld_json($product);
        }

        return $next($request);
    }
}
