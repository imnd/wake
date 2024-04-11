<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BouquetAuthorMiddleware
{
    use ResponseTrait;

    public function handle($request, Closure $next): Response
    {
        $bouquet = $request->route('bouquet');
        if ($bouquet->user_id === Auth::id() || $bouquet->memorial->user_id === Auth::id()) {
            return $next($request);
        }

        return $this->responseForbidden();
    }
}
