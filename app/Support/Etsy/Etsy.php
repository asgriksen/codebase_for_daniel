<?php

namespace App\Support\Etsy;

class Etsy extends Request
{
    /**
     * Create new Etsy instance.
     *
     * @param $access_token
     * @param $access_token_secret
     */
    public function __construct($access_token, $access_token_secret)
    {
        parent::__construct($access_token, $access_token_secret);
    }

    /**
     * Return all Etsy active listings.
     *
     * @return array|mixed
     */
    public function getActiveListings()
    {
        return $this->make(
            'listings/active?includes=Images',
            'get'
        );
    }

    /**
     * Return all Etsy active listings based on supplied keywords.
     *
     * @param array|string $keywords
     *
     * @return array|mixed
     */
    public function getActiveListingsByKeyword( $keywords )
    {
        $keywords = is_array($keywords) ? $keywords : [$keywords];
        $keywords = implode(',', $keywords);

        return $this->make(
            "listings/active?includes=Images&keywords=$keywords",
            "get"
        );
    }

    /**
     * Return all Etsy active listings based on supplied keywords inside of the title.
     *
     * @param array|string $keywords
     *
     * @return array|mixed
     */
    public function getActiveListingsByKeywordsInTitle( $keywords )
    {
        $keywords = is_array($keywords) ? $keywords : [$keywords];
        $keywords = implode(',', $keywords);

        return $this->make(
            "listings/active?includes=Images&title=$keywords",
            "get"
        );
    }

    /**
     * Fetch a listing by its ID number.
     *
     * @param $id
     *
     * @return array|mixed
     */
    public function getCategory( $id )
    {
        return $this->make(
            "categories/$id",
            'get'
        );
    }

    /**
     * Fetch a listing by its ID number.
     *
     * @param $id
     *
     * @return array|mixed
     */
    public function getListing( $id )
    {
        return $this->make(
            "listings/$id",
            'get'
        );
    }

    /**
     * Fetch a listing by its ID number.
     *
     * @param $id
     *
     * @return array|mixed
     */
    public function getListingWithImages( $id )
    {
        return $this->make(
            "listings/$id?includes=Images",
            'get'
        );
    }

    /**
     * Return the user details of the cart owner.
     *
     * @return array
     */
    public function getUserDetails()
    {
        $results = $this->make('users/__SELF__', 'get');

        return $results['count'] ? $results['results'][0] : [];
    }
}