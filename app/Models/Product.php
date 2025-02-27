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


    public function image(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
