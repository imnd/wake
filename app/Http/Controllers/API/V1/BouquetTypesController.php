<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bouquet\BouquetTypeResource;
use App\Services\BouquetTypeService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class BouquetTypesController extends Controller
{
    use ResponseTrait;

    /**
     * Bouquet types list
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/bouquet-types",
     *     summary="Get all bouquet types",
     *     description="Get all bouquet types",
     *     operationId="get-bouquet-types",
     *     tags={"Bouquet types"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Bouquet types list",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 ref="#/components/schemas/BouquetTypeResource"
     *             ),
     *         ),
     *     ),
     * )
     */
    public function index(BouquetTypeService $service): JsonResponse
    {
        return $this->responseOk(BouquetTypeResource::collection($service->getList()));
    }
}
