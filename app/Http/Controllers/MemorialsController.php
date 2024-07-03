<?php

namespace App\Http\Controllers;

use App\Http\Resources\Memorial\ViewMemorialResource;
use App\Models\Memorial;
use Illuminate\Contracts\View\View;

class MemorialsController extends Controller
{
    public function show(string $uuid): View
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1) {
            return abort(404, 'There is no such page');
        }

        if (!$memorial = Memorial::where('uuid', $uuid)->first()) {
            return abort(404, 'There is no such page');
        }

        $memorialResource = (new ViewMemorialResource($memorial))->resolve();
        return view('memorial', [
            'memorial' => $memorialResource,
            'horizontal' => $this->getIsHorizontal($memorialResource['images']),
            'imagesCount' => count($memorialResource['images']),
        ]);
    }

    private function getIsHorizontal($images): bool
    {
        $horizontal = false;
        switch (count($images)) {
            case 2:
            case 3:
            case 4:
                $horizontal = (count($images) === 2);
                foreach ($images as $key => $image) {
                    if ($image['original_width'] > $image['original_height']) {
                        $horizontal = false;
                        array_splice($images, $key, 1);
                        $images = array_merge([$image], $images);
                        break;
                    }
                }
                break;
        }

        return $horizontal;
    }
}
