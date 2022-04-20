<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Restaurant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $model = explode("\\", get_class(auth()->user()));
        $instanceOf =  end($model);

        if($instanceOf != "Restaurant") {
            return abort(403, "You are not authorize to access this route");
        }

        return $next($request);
    }
}
