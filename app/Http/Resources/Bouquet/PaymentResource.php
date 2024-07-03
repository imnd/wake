<?php

namespace App\Http\Resources\Bouquet;

use App\Http\Resources\MediaResource;
use App\Http\Resources\Memorial\ShortMemorialResource;
use App\Models\Bouquet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int     $id
 * @property string  $payment_method
 * @property integer $amount
 * @property mixed $created_at
 *
 * @OA\Schema(
 *     @OA\Xml(name="BouquetResource"),
 *     @OA\Property(property="id", type="int8", example="53"),
 *     @OA\Property(property="payment_method", type="string", example="cashapp"),
 *     @OA\Property(property="amount", type="double", example="150"),
 *     @OA\Property(property="created_at", type="date", example="created_at"),
 * )
 */
class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount / 100,
            'created_at' => $this->created_at,
        ];
    }
}
