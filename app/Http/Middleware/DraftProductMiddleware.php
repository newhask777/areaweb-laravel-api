<?php

namespace App\Http\Middleware;

use App\Enums\ProductStatus;
use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DraftProductMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var Product $product
         */
        $product = $request->route('product');

        if($product->isDraft())
        {
            return response()->json([
                'message' => 'Product not fouund',
            ], 404);
        }

        return $next($request);
    }
}
