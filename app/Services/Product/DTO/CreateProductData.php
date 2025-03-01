<?php

namespace App\Services\Product\DTO;



use App\Enums\ProductStatus;
use Spatie\LaravelData\Data;

class CreateProductData extends Data
{
//    public function __construct(
//        public string $name,
//        public string $description,
//        public int $price,
//        public int $count,
//        public ProductStatus $status,
//        public array $images,
//    )
//    {
//    }

    public string $name;
    public string $description;
    public int $price;
    public int $count;
    public ProductStatus $status;
    public array $images;
}
