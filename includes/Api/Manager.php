<?php
namespace WpIntegrity\StoreKit\Api;

/**
 * Api Manager Class
 */
class Manager {
    /**
     * All Api Classes
     * 
     * @var array
     */
    protected $classes;

    /**
     * Class construction
     */
    public function __construct() {
        $this->classes = [
            WooOptions::class,
            DokanOptions::class
        ];
        
        add_action( 'rest_api_init', [ $this, 'register_apis'] );
    }

    public function register_apis() {
        foreach( $this->classes as $class ) {
            $object = new $class;
            $object->register_routes();
        }
    }
}