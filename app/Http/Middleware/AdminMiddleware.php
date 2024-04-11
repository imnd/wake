<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::user()->is_admin) {
            return $next($request);
        }

        return $this->responseForbidden();
    }
}
