<?php

namespace Pharaonic\Laravel\Helpers\Middlewares;

use Closure;
use Illuminate\Http\Request;

class SimpleLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale') ?? null;
        if ($locale) {
            $request->getRouteResolver()()->forgetParameter('locale');
            app()->setlocale($locale);
        }

        return $next($request);
    }
}
