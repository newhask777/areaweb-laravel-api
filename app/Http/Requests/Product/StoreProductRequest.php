<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatus;
use App\Http\Requests\ApiRequest;
use App\Services\Product\DTO\CreateProductData;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['string'],
            'price' => ['required', 'numeric', 'min:1', 'max:50000'],
            'count' => ['required', 'integer', 'min:0', 'max:5000'],
            'status' => ['required', new Enum(ProductStatus::class)],
            'images.*' => ['nullable']
        ];
    }


    public function data(): CreateProductData
    {
        return CreateProductData::from($this->validated());
    }
}
