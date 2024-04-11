<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BouquetType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'priority',
    ];

    public function bouquets(): HasMany
    {
        return $this->hasMany(Bouquet::class);
    }
}
