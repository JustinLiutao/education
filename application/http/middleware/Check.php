<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        $request->middleware = 'BB';
        return $next($request);
    }
}
