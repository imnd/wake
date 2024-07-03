<?php

namespace App\Http\Resources\Bouquet;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewBouquetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'from'  => $this->from,
            'type'  => (new BouquetTypeResource($this->type))->toArray($request),
            'created' => Carbon::create($this->created_at)->format('M d, Y, g:i'),
            'payment_method' => $this->payment_method,
        ];
    }
}
