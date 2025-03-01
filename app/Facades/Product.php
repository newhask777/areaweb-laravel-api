<?php

namespace App\Facades;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\StoreReviewRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\Product\ProductService setProduct(\App\Models\Product $product)
 * @method static \App\Models\Product[] published(array $fields=['id', 'name', 'price', 'count'])
 * @method static \App\Models\Product store(StoreProductRequest $request)
 * @method static \App\Models\Product update(UpdateProductRequest $request)
 * @method static \App\Models\ProductReview addReview(StoreReviewRequest $request)
 *
 * @see \App\Services\Product\ProductService

 */
class Product extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'product';
    }
}
