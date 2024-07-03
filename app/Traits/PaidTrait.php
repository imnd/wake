<?php

namespace App\Traits;

use App\Helpers\Statuses;
use Illuminate\Database\Eloquent\Builder;

trait PaidTrait
{
    public function scopePaid(Builder $query): void
    {
        $query->where('status', Statuses::STATUS_PAID);
    }

    public function isPaid(): bool
    {
        return $this->status === Statuses::STATUS_PAID;
    }
}
