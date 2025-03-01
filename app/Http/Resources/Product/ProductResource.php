<?php

namespace App\Http\Resources\Product;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'rating' => $this->rating(),
            'images' => $this->imagesList(),
            'price' => $this->price,
            'count' => $this->count,
            'reviews' => ProductReviewResource::collection($this->reviews)
        ];
    }
}
