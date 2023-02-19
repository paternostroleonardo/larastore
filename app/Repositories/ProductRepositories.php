<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepositories extends BaseRepository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }
}