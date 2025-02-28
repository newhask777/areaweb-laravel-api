<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'count', 'price', 'status',
    ];


    protected $casts = [
        'status' => ProductStatus::class
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }


    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->select('path');
    }

    public function rating(): int
    {
        return $this->reviews()->count('rating');
    }

    public function set_rating($rating, $id)
    {
        $rt = ProductReview::query()->select('rating')->whereId($id);
//            ->update(['rating' => $rating]);
//       dd($rt);

    }

    public function status($status=false): ProductStatus
    {
        if($status === true){
            return ProductStatus::Published;
        }
        else{
            return ProductStatus::Draft;
        }

    }

}
