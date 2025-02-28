<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiRequest;

class StoreReviewRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:0', 'max:5']
        ];
    }
}
