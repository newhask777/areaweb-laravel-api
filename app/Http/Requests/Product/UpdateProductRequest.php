<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatus;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric'],
            'count' => ['nullable', 'integer'],
            'rating' => ['nullable'],
            'status' => ['nullable', new Enum(ProductStatus::class)],
        ];
    }
}
