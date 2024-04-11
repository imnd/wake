<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MediaAuthorMiddleware
{
    use ResponseTrait;

    public function handle($request, Closure $next): Response
    {
        $medium = $request->route('medium');
        $model = $medium->model;
        if ($model->user_id === Auth::id() || $model->memorial->user_id === Auth::id()) {
            return $next($request);
        }

        return $this->responseForbidden();
    }
}
