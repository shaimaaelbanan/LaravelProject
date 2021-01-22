<?php

namespace App\Http\Middleware;
use App\traits\generalTrait;
use Closure;

class checkToken
{
    use generalTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth('api')->check())
            return $next($request);
        return $this->returnError(401, "Unauthorized");
    }
}
