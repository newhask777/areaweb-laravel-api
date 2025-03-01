<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\StoreReviewRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\MinifiedProductResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductReviewResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Auth;
use App\Facades\Product as ProductFacade;


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


    public function index()
    {
        return MinifiedProductResource::collection(
            ProductFacade::published()
        );
    }


    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function store(StoreProductRequest $request)
    {
        // dd(auth()->user()->tokens());
        return new ProductResource(ProductFacade::store($request));
    }




    public function update(Product $product, UpdateProductRequest $request)
    {
        $product = ProductFacade::setProduct($product)->update($request);

        return new ProductResource($product);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return responseOk();
    }



    public function review(Product $product, StoreReviewRequest $request)
    {
        // dd($request->all());
        return new ProductReviewResource(
            ProductFacade::setProduct($product)->addReview($request)
        );
    }


}
