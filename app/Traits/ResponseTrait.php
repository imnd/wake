<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    protected function responseUnauthorized(): JsonResponse
    {
        return response()->json([
            'message' => 'Unauthorized',
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function responseForbidden(): JsonResponse
    {
        return response()->json([
            'message' => 'Forbidden',
        ], Response::HTTP_FORBIDDEN);
    }

    protected function responseCreated($contents): JsonResponse
    {
        return response()->json($contents, Response::HTTP_CREATED);
    }

    protected function responseOk($contents): JsonResponse
    {
        return response()->json($contents, Response::HTTP_OK);
    }

    protected function responseNoContent(): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    protected function responseNotFound(): JsonResponse
    {
        return response()->json([], Response::HTTP_NOT_FOUND);
    }
}
