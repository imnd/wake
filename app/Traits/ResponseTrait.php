<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    protected function responseUnauthorized($message = 'Unauthorized'): JsonResponse
    {
        return response()->json(compact('message'), Response::HTTP_UNAUTHORIZED);
    }

    protected function responseForbidden($message = 'Forbidden'): JsonResponse
    {
        return response()->json(compact('message'), Response::HTTP_FORBIDDEN);
    }

    protected function responseBadRequest($message = 'Bad request'): JsonResponse
    {
        return response()->json(compact('message'), Response::HTTP_BAD_REQUEST);
    }

    protected function responseCreated($contents = []): JsonResponse
    {
        return response()->json($contents, Response::HTTP_CREATED);
    }

    protected function responseOk($contents = []): JsonResponse
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

    protected function responseError($message = 'Server error'): JsonResponse
    {
        return response()->json(compact('message'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
