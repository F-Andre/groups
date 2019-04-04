<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (is_object($request->user()) and $request->user()->admin)
        {
            return $next($request);
        }
        return redirect(route('blog.index'));
    }
}
