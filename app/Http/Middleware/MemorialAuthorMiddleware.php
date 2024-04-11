<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MemorialAuthorMiddleware
{
    use ResponseTrait;

    public function handle($request, Closure $next): Response
    {
        $memorial = $request->route('memorial');
        if ($memorial->user_id === Auth::id()) {
            return $next($request);
        }

        return $this->responseForbidden();
    }
}
