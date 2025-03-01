<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\StoreReviewRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\MinifiedProductResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
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
        $products = Product::query()
            ->select([
                'id', 'name', 'price', 'count'
            ])
            ->whereStatus(ProductStatus::Published)
            ->get();
;
        return MinifiedProductResource::collection($products);
    }


    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function store(StoreProductRequest $request)
    {
//        dd(auth()->user()->tokens());

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
