<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ProductImageRepository;
use App\Contracts\Repositories\ProductRepository;
use App\Http\Requests;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ProductController extends APIBaseController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductImageRepository
     */
    private $productImageRepository;

    /**
     * Create new ProductController instance.
     *
     * @param ProductRepository      $productRepository
     * @param ProductImageRepository $productImageRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository
    ){
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $category_id = Input::get('category_id', false);

        // Fetch all of the users products.
        //
        $products = $category_id
                  ? $this->productRepository->byUserAndCategoryIdWith( Auth::user(), $category_id, ['categories'] )
                  : $this->productRepository->byUserWith( Auth::user(), ['categories'] );

        // Return the response
        //
        return $this->response( $products ? $products : [] );
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
     * @param  StoreProductRequest $request
     *
     * @return Response
     */
    public function store( StoreProductRequest $request )
    {
        // Create the product record.
        //
        $product = $this->productRepository->create( $request->except('images') );

        // Attach images if supplied.
        //
        if( $request->get('images', false) );
        {
            foreach( $request->images as $image )
            {
                $this->productImageRepository->createForProudct( $product, $image );
            }
        }

        // Load the images so they can be included in the response.
        //
        $product->load('images');

        // Return the response.
        //
        return $this->response( $product );
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
        // Create the product record.
        //
        $product = $this->productRepository->showByIdAndUser( $id, Auth::user() );

        // Return the response.
        //
        return $this->response( $product );
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
     * @param  UpdateProductRequest $request
     * @param  int                  $id
     *
     * @return Response
     */
    public function update( UpdateProductRequest $request, $id )
    {
        // Create the product record.
        //
        $product = $this->productRepository->showByIdAndUser( $id, Auth::user() );

        // Update the product record.
        //
        $this->productRepository->update( $product , $request->except('images') );

        // Return the response.
        //
        return $this->response( $product );
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
        // Fetch the product record from the database.
        //
        $product = $this->productRepository->byIdAndUser( $id, Auth::user() );

        // Delete the product record.
        //
        $delete = $this->productRepository->delete( $product );

        // Return the response
        //
        return $this->response(['deleted' => (bool) $delete]);
    }
}
