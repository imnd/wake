<?php

namespace App\Models;

use App\Traits\MediaModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\{
    HasMedia,
    InteractsWithMedia,
};

class Bouquet extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use MediaModelTrait;

    public const STATUS_PAID = 'paid';
    public const STATUS_UNPAID = 'unpaid';

    protected $fillable = [
        'user_id',
        'memorial_id',
        'type_id',
        'condolences',
        'from',
        'status',
    ];

    public function registerMediaConversions(SpatieMedia $media = null): void
    {
        $this->addMediaConversions('bouquet');
    }

    # RELATIONS

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(BouquetType::class, 'type_id');
    }

    public function memorial()
    {
        return $this->belongsTo(Memorial::class);
    }

    public function scopePaid(Builder $query): void
    {
        $query->where('status', self::STATUS_PAID);
    }
}
