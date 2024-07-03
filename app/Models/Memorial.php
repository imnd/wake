<?php

namespace App\Models;

use App\Helpers\Statuses;
use App\Helpers\Str;
use App\Traits\MediaModelTrait;
use App\Traits\PaidTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia,};
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Memorial extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use MediaModelTrait;
    use PaidTrait;

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
        'uuid',
        'goal_sum',
        'payment_intent_id',
    ];

    protected $casts = [
        'default' => 'boolean',
        'day_of_birth' => 'date',
        'day_of_death' => 'date',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function registerMediaConversions(SpatieMedia $media = null): void
    {
        $this->addMediaConversions('memorial');
    }

    public function setMiddleNameAttribute($value)
    {
        $this->attributes['middle_name'] = $value ?: '';
    }

    # SCOPES

    public function scopeDefault(Builder $query): void
    {
        $query->where('default', true);
    }

    public function scopeNotDeleted(Builder $query): void
    {
        $query->where('status', '<>', Statuses::STATUS_DELETED);
    }

    # RELATIONS

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

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
        return $this->bouquets()->paid();
    }
}
