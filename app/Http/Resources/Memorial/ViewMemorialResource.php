<?php

namespace App\Http\Resources\Memorial;

use App\Http\Resources\Bouquet\ViewBouquetResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewMemorialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $shortDateFormat = 'M d, Y';
        $fullDateFormat = 'F d, Y';
        $birthDate = Carbon::create($this->day_of_birth);
        $deathDate = Carbon::create($this->day_of_death);

        // Take the first 4 pictures from the gallery
        $imagePreviews = $this->getImagePreviews()->slice(0, 4);
        $images = [];
        foreach ($imagePreviews as $imagePreview) {
            $images[] = [
                'path' => $imagePreview->getUrl('image-preview'),
                'original_width' => $imagePreview->original_width,
                'original_height' => $imagePreview->original_height,
            ];
        }
        return [
            'title' => $this->title,
            'text' => $this->text,
            'full_name' => "{$this->first_name} {$this->last_name}",
            'birth_date_short' => $birthDate->format($shortDateFormat),
            'death_date_short' => $deathDate->format($shortDateFormat),
            'birth_date_full' => $birthDate->format($fullDateFormat),
            'death_date_full' => $deathDate->format($fullDateFormat),
            'age' => $birthDate->diffInYears($deathDate),
            'birth_place' => $this->place_of_birth,
            'death_place' => $this->place_of_death,
            'avatar' => $this->avatar ? "/storage/{$this->avatar}" : "",
            'images' => $images,
            'bouquets' => ViewBouquetResource::collection($this->paidBouquets->slice(0, 3))->toArray($request),
        ];
    }
}
