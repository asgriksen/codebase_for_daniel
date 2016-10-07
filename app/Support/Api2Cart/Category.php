<?php

namespace App\Support\Api2Cart;

use Exception;

class Category
{
    /**
     * @var Api2Cart API
     */
    protected $api;

    /**
     * Create new Product instance.
     *
     * @param $store_key
     */
    public function __construct( $store_key )
    {
        $this->initializeApiConnection( $store_key );
    }

    /**
     * Initialize Api2Cart Connection.
     *
     * @return void
     */
    public function initializeApiConnection( $store_key )
    {
        $this->api = new Connector(
            config('services.api2cart.key'),
            $store_key
        );
    }

    /**
     * Search for product(s) in the cart.
     *
     * @param array $params
     *
     * @return Exception|string
     */
    public function find( $params = [] )
    {
        try
        {
            $results = $this->api->request('category.find', $params);
        }
        catch( Exception $e )
        {
            return $e;
        }

        return $results;
    }

    /**
     * Return a list of products.
     *
     * @param array $params
     *
     * @return Exception
     */
    public function getList( $params = [] )
    {
        try
        {
            $results = $this->api->request('category.list', $params);
        }
        catch( Exception $e )
        {
            return $e;
        }

        return $results;
    }

    /**
     * Get information about a particular product.
     *
     * @param int    $id     The id number of the product.
     * @param string $params The comma delimited list of parameters.
     *
     * @return stdClass|Exception
     */
    public function getInfo( $id, $params = 'id,parent_id,name,description' )
    {
        $params = [
            'id'     => $id,
            'params' => $params
        ];

        try
        {
            $result = $this->api->request('category.info', $params);
        }
        catch( Exception $e )
        {
            return $e;
        }

        return $result;
    }
}