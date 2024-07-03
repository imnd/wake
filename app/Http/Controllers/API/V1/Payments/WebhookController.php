<?php

namespace App\Http\Controllers\API\V1\Payments;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    use ResponseTrait;

    /**
     * Handle different types of webhook events
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/payments/webhook",
     *     summary="webhook",
     *     description="webhook",
     *     operationId="webhook",
     *     tags={"Payments"},
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
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function handle(PaymentService $service, Request $request): JsonResponse
    {
        $event = json_decode($request->getContent(), true);

        switch ($event['type']) {
            case 'payment_method.attached':
                $service->attachPaymentMethod($event['data']);
                return $this->responseNoContent();

            case 'payment_intent.succeeded':
                $bouquet = $service->finalizePayment($event['data']);
                return $this->responseOk($bouquet);
        }

        return $this->responseNoContent();
    }
}
