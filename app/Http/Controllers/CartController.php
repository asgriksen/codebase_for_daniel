<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\CartCategoryRepository;
use App\Contracts\Repositories\CartRepository;
use App\Contracts\Repositories\ProductRepository;
use App\Http\Requests;
use App\Http\Requests\StoreCartRequest;
use App\Support\Api2Cart\Product;
use Auth;
use Illuminate\Http\Request;
use Input;

class CartController extends APIBaseController
{
    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CartCategoryRepository
     */
    private $cartCategoryRepository;

    /**
     * Create new CartController instance.
     *
     * @param CartRepository         $cartRepository
     * @param ProductRepository      $productRepository
     * @param CartCategoryRepository $cartCategoryRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        CartCategoryRepository $cartCategoryRepository
    ){
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->cartCategoryRepository = $cartCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Fetch all cart records for the user.
        $carts = $this->cartRepository->byUser( Auth::user() );

        // Return the results.
        return $this->response($carts ? $carts : []);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCartRequest $request
     *
     * @return Response
     */
    public function store( StoreCartRequest $request )
    {
        // Define the data we want to inject into the record.
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        // Create the cart record and associate it with the user.
        $cart = $this->cartRepository->createForUser( Auth::user() , $data );

        // Return the newly created record.
        return $this->response( $cart );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show( $id )
    {
        // Fetch all cart record for the user.
        $cart = $this->cartRepository->byIdAndUser( $id, Auth::user() );

        // Return the results.
        return $this->response($cart ? $cart : []);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy( $id )
    {
        //
    }

    /**
     * Return list of products within the cart's Api2Cart record.
     *
     * @param $cart_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listProducts( $cart_id )
    {
        // Fetch the cart record from the database.
        //
        $cart = $this->cartRepository->byIdAndUser( $cart_id, Auth::user() );

        // Obtain the list of product ids submitted by the user.
        //
        $params = array_filter([
            'start'  => Input::get('start', 0),
            'count'  => Input::get('count', 50),
            'params' => Input::get('params', 'id,name,price'),
        ], 'strlen');

        // Bail if no search parameters provided.
        if( ! count($params) )
        {
            return $this->response(['You must provide filter parameters.']);
        }

        // Take the cart record, connect to its Api2Cart account and fetch products
        // based on a list of provided product ids.
        //
        $products = $this->productRepository->listCartProductsFromApi2Cart( $cart, $params );

        // Return the response.
        //
        return $this->response(['products' => $products]);
    }

    /**
     * Find products in Etsy.
     *
     * @param $cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function findEtsyProducts( $cart )
    {
        // Obtain the list of product ids submitted by the user.
        //
        $params = array_filter([
            'etsy_keywords' => Input::get('etsy_keywords', null),
        ], 'strlen');

        // Bail if no search parameters provided.
        if( ! count($params) )
        {
            return $this->response(['You must provide search keywords.']);
        }

        // Take the cart record, connect to its Etsy account and fetch products.
        //
        return $this->productRepository->findCartProductsInEtsy( $cart, $params );
    }

    /**
     * Find products in Api2Cart.
     *
     * @param $cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function findApi2CartProducts( $cart )
    {
        // Obtain the list of product ids submitted by the user.
        //
        $params = array_filter([
            'find_value'  => Input::get('find_value', null),
            'find_where'  => Input::get('find_where', null),
            'find_params' => Input::get('find_params', null),
        ], 'strlen');

        // Bail if no search parameters provided.
        if( ! count($params) )
        {
            return $this->response(['You must provide search parameters.']);
        }

        // Take the cart record, connect to its Api2Cart account and fetch products.
        //
        return $this->productRepository->findCartProductsInApi2Cart( $cart, $params );
    }

    /**
     * Import specified products for a particular cart record from Api2Cart.
     *
     * @param $cart_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function findProducts( $cart_id )
    {
        // Fetch the cart record from the database.
        //
        $cart = $this->cartRepository->byIdAndUser( $cart_id, Auth::user() );

        // Fetch the products.
        //
        $products = $cart->isEtsy()
                  ? $this->findEtsyProducts($cart)
                  : $this->findApi2CartProducts($cart);

        // Return the response.
        //
        return $this->response([
            'is_etsy'  => $cart->isEtsy(),
            'products' => $products
        ]);
    }

    /**
     * Import specified products for a particular cart record from Api2Cart.
     *
     * @param $cart_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function importProducts( $cart_id )
    {
        // Fetch the cart record from the database.
        //
        $cart = $this->cartRepository->byIdAndUser( $cart_id , Auth::user() );

        // Obtain the list of product ids submitted by the user.
        //
        $product_ids = Input::get('product_ids');

        // If only 1 product ID was passed then we need to place that
        // ID inside of an array.
        if( ! is_array($product_ids)) $product_ids = [$product_ids];

        // Take the cart record, connect to its Api2Cart account and fetch products
        // based on a list of provided product ids.
        //
        $imported = $cart->isEtsy()
                  ? $this->productRepository->importCartProductsByListingIdFromEtsy( $cart, $product_ids )
                  : $this->productRepository->importCartProductsByIdFromApi2Cart( $cart, $product_ids );

        // Return the response.
        //
        return $this->response(['imported' => $imported]);
    }

    /**
     * Return all categories of the cart.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories( $id )
    {
        // Fetch all cart record for the user.
        $cart = $this->cartRepository->byIdAndUser( $id, Auth::user() );

        // Fetch categories by cart within users account.
        $categories = $this->cartCategoryRepository->byCartAndUser( $cart , Auth::user() );

        // Return the response.
        return $this->response( $categories );
    }
}
