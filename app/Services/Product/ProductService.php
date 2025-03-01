<?php

namespace App\Services\Product;

use App\Enums\ProductStatus;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\StoreReviewRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    private Product $product;


    public function published(array $fields=['id', 'name', 'price', 'count']): Collection
    {
        return Product::query()
            ->select($fields)
            ->whereStatus(ProductStatus::Published)
            ->get();
    }


    public function store(StoreProductRequest $request): Product
    {
        $token = $request->bearerToken('token');
        $user = User::query()->whereApiToken($token)->first();

        /**
         * @var Product $product
         */
        $product = auth()->user()->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'count' => $request->count,
            'status' => $request->status,
        ]);

        foreach ($request->file('images') as $image)
        {
            $path = $image->storePublicly('images');

            $product->images()->create([
                'path' => config('app.url') . Storage::url($path),
            ]);
        }

        return $product;
    }


    public function update(UpdateProductRequest $request): Product
    {
        if ($request->method() === 'PUT') {
//            dd($request->all());
            $this->product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'count' => $request->count,
                'rating' => $request->rating,
                'status' => $request->enum('status', ProductStatus::class)
            ]);

        } else {
            $data = [];

            if ($request->has('name')) {
                $data['name'] = $request->name;
            }

            if ($request->has('description')) {
                $data['description'] = $request->description;
            }

            if ($request->has('price')) {
                $data['price'] = $request->price;
            }

            if ($request->has('count')) {
                $data['count'] = $request->count;
            }

            if ($request->has('rating')) {
                $data['rating'] = $request->rating;
            }

            if ($request->has('status')) {
                $data['status'] = $request->status;
            }

//            dd($data);

            $this->product->update($data);

        }

        return $this->product;
    }


    public function addReview(StoreReviewRequest $request): ProductReview
    {
        return $this->product->reviews()->create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'rating' => $request->rating
        ]);
    }


    /**
     * @param Product $product
     * @return ProductService
     */
    public function setProduct(Product $product): ProductService
    {
        $this->product = $product;
        return $this;
    }


}
