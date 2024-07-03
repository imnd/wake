<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Http\Requests\Auth\{
    LoginRequest,
    PasswordForgotRequest,
    PasswordRecoveryRequest,
    RegisterRequest
};
use App\Services\UserService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{
    Auth,
    Password
};

class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * Register new user
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Register user",
     *     description="Register new user",
     *     operationId="register",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function register(
        RegisterRequest $request,
        UserService $userService,
        PaymentService $paymentService,
    ): JsonResponse {
        $user = $userService->create($request->validated());
        $paymentService->createCustomer($request->only(['name', 'email']));

        return $this->responseCreated(
            $userService->getUserData($request, $user, $userService->attempt($request))
        );
    }

    /**
     * User login
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/users/me/session",
     *     summary="User login",
     *     description="User login",
     *     operationId="login",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *               @OA\Property(
     *                   property="user",
     *                   type="User",
     *               ),
     *               @OA\Property(
     *                   property="authorization",
     *                   type="object",
     *                   @OA\Property(
     *                       property="token",
     *                       type="string",
     *                       example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYm91cWV0L2FwaS92MS91c2Vycy9tZS9zZXNzaW9uIiwiaWF0IjoxNzEwOTAzOTgyLCJleHAiOjE3MTA5MDc1ODIsIm5iZiI6MTcxMDkwMzk4MiwianRpIjoiNXduZ3ZzMUp1ZVA2WDcwaiIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.Wci1wDugk51QydQRgcBR9XHJ5j6OkjSMmZ7eSkZUGt0",
     *                   ),
     *                   @OA\Property(
     *                       property="type",
     *                       type="string",
     *                       example="bearer",
     *                   )
     *               )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function login(
        LoginRequest $request,
        UserService $userService,
    ): JsonResponse {
        if (!$token = $userService->attempt($request)) {
            return $this->responseUnauthorized('Invalid Email/Password');
        }

        return $this->responseOk($userService->getUserData($request, Auth::user(), $token));
    }

    /**
     * User logout
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/users/logout",
     *     summary="User logout",
     *     description="User logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Successfully logged out",
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return $this->responseOk([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh token
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/user/refresh",
     *     summary="Refresh token",
     *     description="Refresh token",
     *     operationId="refresh",
     *     tags={"Auth"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYm91cWV0L2FwaS92MS91c2Vycy9tZS9zZXNzaW9uIiwiaWF0IjoxNzEwOTAzOTgyLCJleHAiOjE3MTA5MDc1ODIsIm5iZiI6MTcxMDkwMzk4MiwianRpIjoiNXduZ3ZzMUp1ZVA2WDcwaiIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.Wci1wDugk51QydQRgcBR9XHJ5j6OkjSMmZ7eSkZUGt0",
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function refresh(): JsonResponse
    {
        return $this->responseOk([
            'token' => Auth::refresh(),
        ]);
    }

    /**
     * Forgot password
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/user/forgot-password",
     *     summary="Send forgot password token",
     *     description="Send forgot password token",
     *     operationId="forgot-password",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function forgotPassword(
        PasswordForgotRequest $request,
        UserService $userService,
    ): JsonResponse {
        $userService->sendRecoveryToken($request);

        return $this->responseOk();
    }

    /**
     * Reset password
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/user/reset-password",
     *     summary="Send forgot password token",
     *     description="Send forgot password token",
     *     operationId="reset-password",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="password_confirmation",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="token",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function resetPassword(
        PasswordRecoveryRequest $request,
        UserService $userService,
    ): JsonResponse {
        $status = $userService->resetPassword($request);

        return $this->responseOk(
            $status === Password::PASSWORD_RESET
                ? 'The password reset successfully!'
                : __($status)
        );
    }
}
