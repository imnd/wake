<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MemorialAuthorOrViewerMiddleware
{
    use ResponseTrait;

    public function handle($request, Closure $next): Response
    {
        $memorial = $request->route('memorial');
        // check if user is author
        if ($memorial->user_id === Auth::id()) {
            return $next($request);
        }

        // check if user is viewer
        if (in_array(Auth::id(), $memorial->viewers->pluck('id')->toArray())) {
            return $next($request);
        }

        return $this->responseForbidden();
    }
}
