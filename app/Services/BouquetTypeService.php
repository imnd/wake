<?php

namespace App\Services;

use App\Models\BouquetType;
use Illuminate\Database\Eloquent\Collection;

class BouquetTypeService
{
    public function getList(): Collection
    {
        return BouquetType::orderBy('priority')->get();
    }
}
