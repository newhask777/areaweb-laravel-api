<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\StoreReviewRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function __construct()
    {
        // TODO: убрать когда ьы будем работать с авторизацией
//        auth()->login(User::query()->inRandomOrder()->whereIsAdmin(true)->first());

        $this->middleware('auth:sanctum')->only([
            'store', 'update', 'review', 'destroy'
        ]);
    }


    public function index()
    {
        $products = Product::query()
            ->select([
                'id', 'name', 'price', 'count'
            ])
//            ->where('status', 'published')
            ->whereStatus(ProductStatus::Published)
            ->get();

//        dd($products);

        return $products->map(fn(Product $product)=> [
//            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'count' => $product->count,
            'rating' => $product->rating(),
            'status' => $product->status(true)
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


    public function store(StoreProductRequest $request)
    {
        dd(auth()->user()->tokens());

        $token = $request->bearerToken('token');
        $user = User::query()->whereApiToken($token)->first();

//        dd($user);

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


    public function review(Product $product, StoreReviewRequest $request)
    {
//        dd($request->all());

        return $product->reviews()->create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'rating' => $request->rating
        ]);
    }


    public function update(Product $product, UpdateProductRequest $request)
    {
        if($request->method() === 'PUT')
        {
//            dd($request->all());
            $product->update([
//            'id' => $product->id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'count' => $request->count,
                'rating' => $request->rating,
                'status' => $request->enum('status', ProductStatus::class )
            ]);

        }
        else
        {
            $data = [];

            if($request->has('name'))
            {
                $data['name'] = $request->name;
            }

            if($request->has('description'))
            {
                $data['description'] = $request->description;
            }

            if($request->has('price'))
            {
                $data['price'] = $request->price;
            }

            if($request->has('count'))
            {
                $data['count'] = $request->count;
            }

            if($request->has('rating'))
            {
                $data['rating'] = $request->rating;
            }

            if($request->has('status'))
            {
                $data['status'] = $request->status;
            }

//            dd($data);

            $product->update($data);

        }

    }


    public function destroy(Product $product)
    {
        $product->delete();
    }


}
