<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    use ResponseTrait;

    public function handle($request, Closure $next): Response
    {
        $user = $request->route('user') ?? Auth::user();

        if ($user->id === Auth::id()) {
            return $next($request);
        }

        return $this->responseForbidden();
    }
}
