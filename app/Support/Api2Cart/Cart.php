<?php

namespace App\Support\Api2Cart;

use Exception;

class Cart
{
    /**
     * @var Api2Cart Api
     */
    protected $api;

    /**
     * Create new Cart instance.
     */
    public function __construct()
    {
        $this->initializeApiConnection();
    }

    /**
     * Initialize Api2Cart Connection.
     *
     * @return void
     */
    public function initializeApiConnection()
    {
        $this->api = new Connector(config('services.api2cart.key'));
    }

    /**
     * Create new cart record on Api2Cart.
     *
     * @param array $params
     *
     * @return stdClass|Exception
     */
    public function create( $params = [] )
    {
        // Define the parameters we will accept.
        //
        $params = array_only($params, [
            'cart_id',
            'store_url',
            'verify',
            'store_key',
            'AdminAccount',
            'ApiPath',
            'ApiKey'
        ]);

        try
        {
            $result = $this->api->request('cart.create', $params);
        }
        catch( Exception $e )
        {
            return $e;
        }

        return $result;
    }
}