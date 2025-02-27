<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    public function __construct()
    {
        // TODO: убрать когда ьы будем работать с авторизацией
        auth()->login(User::query()->inRandomOrder()->whereIsAdmin(true)->first());
    }



    public function index()
    {
        $products = Product::query()
            ->select([
                'id', 'name', 'price'
            ])
//            ->where('status', 'published')
            ->whereStatus(ProductStatus::Published)
            ->get();

        return $products->map(fn(Product $product)=> [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'rating' => $product->rating()
        ]);
    }


    public function show(Product $product)
    {
        // TODO: переневти в middleware
        if($product->status == ProductStatus::Draft)
        {
           return response()->json([
              'message' => 'Product not fouund',
           ], 404);
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'rating' => $product->rating(),
            'images' => $product->images->map(fn(ProductImage $image) => $image->path),
            'price' => $product->price,
            'count' => $product->count,
            'reviews' => $product->reviews->map(fn(ProductReview $review) => [
                'id' => $review->id,
                'userName' => $review->user->name,
                'text' => $review->text,
                'rating' => $review->rating,
            ]),
        ];
    }


    public function store(Request $request)
    {

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

        return response()->json([
            'id' => $product->id,
            'message' => 'Product was crated',
        ], 201);
    }


    public function review(Product $product, Request $request)
    {
//        dd($request->all());

        return $product->reviews()->create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'rating' => $request->rating
        ]);
    }

}
