<?php

namespace App\Services;

use App\Models\Memorial;
use App\Traits\MediaServiceTrait;
use App\Http\Requests\Memorials\{
    CreateRequest,
    UpdateRequest,
};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MemorialService
{
    use MediaServiceTrait;

    public function getList(int $userId, int $authId): Collection
    {
        $q = Memorial::where('user_id', $userId);

        if ($userId === $authId) {
            return $q
                ->where('status', '<>', Memorial::STATUS_DELETED)
                ->get();
        }

        return $q
            ->where('status', Memorial::STATUS_PUBLISHED)
            ->get();
    }

    public function create(CreateRequest $request, int $userId): Memorial
    {
        $this->setNotDefault($request);

        return Memorial::create(array_merge(['user_id' => $userId], $request->validated()));
    }

    public function update(UpdateRequest $request, Memorial $memorial): Memorial
    {
        $this->setNotDefault($request);
        $memorial->update($request->validated());

        return $memorial;
    }

    private function setNotDefault(Request $request): void
    {
        if (true === $request->default and $memorial = Memorial::default()->first()) {
            $memorial->update([
                'default' => false,
            ]);
        }
    }

    public function changeStatus(Memorial $memorial, string $status): Memorial
    {
        $memorial->update(compact('status'));

        return $memorial;
    }
}
