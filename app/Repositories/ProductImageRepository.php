<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductImageRepository as Contract;
use App\ProductImage;
use App\Support\Repository\Traits\Repositories;

class ProductImageRepository implements Contract
{
    use Repositories;

    /**
     * @var ProductImage
     */
    private $model;

    /**
     * @param ProductImage $productImage
     */
    public function __construct( ProductImage $productImage )
    {
        $this->model = $productImage;
    }

    /**
     * Create product image record and associate it with a product record.
     *
     * @param Product $product
     * @param array   $attributes
     *
     * @return ProductImage
     */
    public function createForProduct( Product $product, $attributes = [] )
    {
        return $product->images()->create( $this->newInstance( $attributes ) );
    }
}