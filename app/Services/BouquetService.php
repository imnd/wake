<?php

namespace App\Services;

use App\Http\Requests\Bouquets\{
    CreateRequest, UpdateRequest
};
use App\Models\Bouquet;

class BouquetService
{
    public function create(CreateRequest $request, array $data): Bouquet
    {
        return Bouquet::create(array_merge($request->validated(), $data));
    }

    public function update(UpdateRequest $request, Bouquet $bouquet): Bouquet
    {
        $bouquet->update($request->validated());

        return $bouquet;
    }
}
