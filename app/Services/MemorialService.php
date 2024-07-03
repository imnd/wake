<?php

namespace App\Services;

use App\Helpers\Statuses;
use App\Http\Requests\Memorials\{CreateRequest, UpdateRequest,};
use App\Models\Memorial;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemorialService
{
    public function getList(int $userId, int $authId): Collection
    {
        $q = Memorial::where('user_id', $userId);

        if ($userId === $authId) {
            return $q
                ->where('status', '<>', Statuses::STATUS_DELETED)
                ->get();
        }

        return $q
            ->where('status', Statuses::STATUS_PAID)
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

    public function bindViewer($memorial): array
    {
        $viewer = User::find(Auth::id());
        try {
            $memorial->viewers()->save($viewer);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 7) {
                return [
                    'result' => 'error',
                    'message' => 'This user already bound to this memorial.',
                ];
            }
        }
        return [
            'result' => 'success',
        ];
    }
}
