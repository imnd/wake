<?php

namespace App\Models;

use App\Traits\MediaModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\{
    HasMedia,
    InteractsWithMedia,
};

class Memorial extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use MediaModelTrait;

    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';
    public const STATUS_DELETED = 'deleted';
    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';
    public const GENDER_OTHER = 'other';

    protected $fillable = [
        'user_id',
        'title',
        'text',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'place_of_birth',
        'place_of_death',
        'day_of_birth',
        'day_of_death',
        'default',
        'status',
        'avatar',
    ];

    protected $casts = [
        'default' => 'boolean',
        'day_of_birth' => 'date',
        'day_of_death' => 'date',
    ];

    public function registerMediaConversions(SpatieMedia $media = null): void
    {
        $this->addMediaConversions('memorial');
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    # SCOPES

    public function scopeDefault(Builder $query): void
    {
        $query->where('default', true);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeNotDeleted(Builder $query): void
    {
        $query->where('status', '<>', self::STATUS_DELETED);
    }

    # RELATIONS

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bouquets(): HasMany
    {
        return $this->hasMany(Bouquet::class);
    }

    public function paidBouquets(): HasMany
    {
        return $this->bouquets()->where('status', Bouquet::STATUS_PAID);
    }
}
