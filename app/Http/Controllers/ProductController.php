<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\StoreReviewRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\MinifiedProductResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductReviewResource;
use App\Models\Product;
use App\Models\User;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function __construct()
    {
        // auth()->login(User::query()->inRandomOrder()->whereIsAdmin(true)->first());

        $this->middleware('auth:sanctum')->only([
            'store', 'update', 'review', 'destroy'
        ]);

        $this->middleware('admin')->only(
            [
                'store', 'update', 'destroy'
            ]
        );

        $this->middleware('product.draft')->only(
            [
                'show'
            ]
        );
    }


    public function index(ProductService $service)
    {
        return MinifiedProductResource::collection($service->published());
    }


    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function store(StoreProductRequest $request, ProductService $service)
    {
        // dd(auth()->user()->tokens());
        return new ProductResource($service->store($request));
    }


    public function review(Product $product, StoreReviewRequest $request, ProductService $service)
    {
        // dd($request->all());
        return new ProductReviewResource(
            $service->setProduct($product)->addReview($request)
        );
    }


    public function update(Product $product, UpdateProductRequest $request, ProductService $service)
    {
        $product = $service->setProduct($product)->update($request);

        return new ProductResource($product);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return responseOk();
    }


}
