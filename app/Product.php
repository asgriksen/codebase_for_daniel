<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avail_sale',
        'cart_id',
        'categories',
        'categories_ids',
        'conversions',
        'description',

        // Refers to the id of the external shopping cart (Viral, Api2Cart, etc.)
        'external_id',

        'meta_description',
        'meta_keywords',
        'meta_title',
        'name',
        'ordered_count',
        'photo',
        'price',
        'product_options',
        'product_variants',
        'quantity',
        'revenue_daily',
        'revenue_monthly',
        'revenue_weekly',
        'short_description',
        'special_price',
        'u_brand',
        'u_model',
        'view_count',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'categories'        => 'json',
        'categories_ids'    => 'json',
        'product_options'   => 'json',
        'product_variants'  => 'json',
        'special_price'     => 'json',
    ];

    /**
     * Return the cart the product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cart()
    {
        return $this->belongsTo( Cart::class );
    }

    /**
     * Return all the categories that the product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany( CartCategory::class, 'cart_category_product' );
    }

    /**
     * Return the category names.
     *
     * @param $categories
     *
     * @return array
     */
    public function getCategoriesArray( )
    {
        return json_decode( $this->attributes['categories'] );
    }

    /**
     * Return the category id numbers.
     *
     * @param $categories_ids
     *
     * @return array
     */
    public function getCategoriesIdsAttribute( $categories_ids )
    {
        return json_decode($categories_ids);
    }

    /**
     * Return all images of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany( ProductImage::class );
    }
}