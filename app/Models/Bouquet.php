<?php

namespace App\Models;

use App\Traits\MediaModelTrait;
use App\Traits\PaidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\{
    HasMedia,
    InteractsWithMedia
};
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Bouquet extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use MediaModelTrait;
    use PaidTrait;

    public const MAX_MEDIA = 3;

    protected $fillable = [
        'type_id',
        'user_id',
        'memorial_id',
        'anonymous',
        'condolences',
        'from',
        'status',
        'payment_intent_id',
        'payment_method',
        'amount',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'anonymous'  => 'boolean',
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

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }
}
