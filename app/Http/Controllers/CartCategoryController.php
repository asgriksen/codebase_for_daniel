<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\CartCategoryRepository;
use App\Contracts\Repositories\CartRepository;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CartCategoryController extends APIBaseController
{
    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var CartCategoryRepository
     */
    private $cartCategoryRepository;

    /**
     * Create new CartCategoryController instance.
     *
     * @param CartRepository      $cartRepository
     * @param CartCategoryRepository $cartCategoryRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        CartCategoryRepository $cartCategoryRepository
    ){
        $this->cartRepository = $cartRepository;
        $this->cartCategoryRepository = $cartCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $cart_id = Input::get('cart_id', false);

        // Fetch all cart record for the user.
        $cart = $this->cartRepository->byIdAndUser( $cart_id, Auth::user() );

        if( $cart == null ) {
            return $this->setError(['cart_id' => ['Empty or invalid cart_id.']])
                    ->error( null, 401);
        }
        else {

            // Fetch categories by cart within users account.
            $categories = $this->cartCategoryRepository->byCartAndUserWithNested( $cart , Auth::user(), null );

            // Return the response.
            return $this->response( $categories );
        }
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
        // Create the cart category record.
        $category = $this->cartCategoryRepository->byIdAndUser( $id, Auth::user() );

        $cart = $this->cartRepository->byIdAndUser( $category->cart_id, Auth::user() );

        //
        $category = $this->cartCategoryRepository->byIdAndUserWithNested( $id, $cart, Auth::user() );

        // Return the response.
        //
        return $this->response( $category );
    }
}
