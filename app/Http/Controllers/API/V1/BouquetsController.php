<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bouquets\CreateRequest;
use App\Http\Requests\Bouquets\UpdateRequest;
use App\Http\Resources\Bouquet\BouquetResource;
use App\Http\Resources\Bouquet\PaymentResource;
use App\Models\Bouquet;
use App\Models\Memorial;
use App\Services\BouquetService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BouquetsController extends Controller
{
    use ResponseTrait;

    /**
     * Memorial bouquets
     * Returns bouquets related to memorial which have `status=paid`
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/bouquets",
     *     summary="Get memorial bouquets",
     *     description="Get memorial bouquets",
     *     operationId="get-bouquets",
     *     tags={"Bouquets"},
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="MEMORIAL_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of bouquets",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 ref="#/components/schemas/BouquetResource"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function index(Memorial $memorial): JsonResponse
    {
        return $this->responseOk(BouquetResource::collection($memorial->paidBouquets));
    }

    /**
     * Create memorial bouquet
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/memorials/{MEMORIAL_ID}/bouquets",
     *     summary="Create bouquet",
     *     description="Create memorial bouquet",
     *     operationId="create-bouquet",
     *     tags={"Bouquets"},
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="MEMORIAL_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="condolences",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="anonymous",
     *         required=false,
     *         @OA\Schema(
     *           type="boolean"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="from",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="status",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="type_id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Created"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public function create(
        Memorial $memorial,
        CreateRequest $request,
        BouquetService $service,
    ): JsonResponse {
        $bouquet = $service->create($request, [
            'user_id' => Auth::id(),
            'memorial_id' => $memorial->id,
        ]);

        return $this->responseCreated(new BouquetResource($bouquet));
    }

    /**
     * Update bouquet
     *
     * @return JsonResponse
     * @OA\Put(
     *     path="/api/v1/bouquets/{BOUQUET_ID}",
     *     summary="Update bouquet",
     *     description="Update bouquet",
     *     operationId="update-bouquet",
     *     tags={"Bouquets"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="BOUQUET_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         in="query",
     *         name="anonymous",
     *         required=false,
     *         @OA\Schema(
     *              type="boolean"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="condolences",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="from",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="status",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="type_id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfull operation",
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/BouquetResource"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function update(
        UpdateRequest $request,
        BouquetService $service,
        Bouquet $bouquet,
    ) {
        $service->update($request, $bouquet);

        return $this->responseOk(new BouquetResource($bouquet));
    }

    /**
     * Get bouquet payment info
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/bouquets/{BOUQUET_ID}/payment-info",
     *     summary="Get bouquet payment info",
     *     description="Get bouquet payment info",
     *     operationId="get-bouquet-payment-info",
     *     tags={"Bouquets"},
     *
     *     @OA\SecurityScheme(
     *         securityScheme="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *
     *     @OA\Parameter(
     *         in="path",
     *         name="BOUQUET_ID",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/PaymentResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function paymentInfo(Bouquet $bouquet): ?JsonResponse
    {
        if ($bouquet->user_id !== Auth::id()) {
            return $this->responseForbidden();
        }

        return $this->responseOk(new PaymentResource($bouquet));
    }
}
