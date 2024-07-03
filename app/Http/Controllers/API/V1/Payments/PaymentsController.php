<?php

namespace App\Http\Controllers\API\V1\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\BouquetPaymentRequest;
use App\Http\Requests\Payments\MemorialPaymentRequest;
use App\Models\Bouquet;
use App\Models\Memorial;
use App\Services\PaymentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    use ResponseTrait;

    /**
     * First step of payment for the bouquet
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/payments/bouquet",
     *     summary="payment for the bouquet",
     *     description="Payment for the bouquet",
     *     operationId="bouquet-payment",
     *     tags={"Payments"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="amount",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="bouquet_id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     * )
     */
    public function bouquet(
        BouquetPaymentRequest $request,
        PaymentService $service,
    ): JsonResponse {
        $bouquet = Bouquet::findOrFail($request->bouquet_id);

        return $this->responseOk(
            $service->setPayment(Auth::user(), $bouquet, $request->amount)
        );
    }

    /**
     * First step of payment for the memorial
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/payments/memorial",
     *     summary="payment for the memorial",
     *     description="Payment for the memorial",
     *     operationId="memorial-payment",
     *     tags={"Payments"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="amount",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="memorial_id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     * )
     */
    public function memorial(
        MemorialPaymentRequest $request,
        PaymentService $service,
    ): JsonResponse {
        $memorial = Memorial::findOrFail($request->memorial_id);

        return $this->responseOk(
            $service->setPayment(Auth::user(), $memorial, $request->amount)
        );
    }
}
