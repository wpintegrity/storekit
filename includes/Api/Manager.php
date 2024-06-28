<?php
namespace WpIntegrity\StoreKit\Api;

/**
 * API Manager Class
 *
 * Manages registration of various API classes.
 * 
 * @since 2.0.0
 */
class Manager {
    /**
     * Array of API classes to register.
     *
     * @var array
     * 
     * @since 2.0.0
     */
    protected $classes;

    /**
     * Constructor.
     *
     * Initializes the list of API classes to register and hooks into 'rest_api_init' action.
     * 
     * @since 2.0.0
     */
    public function __construct() {
        $this->classes = [
            WooOptions::class,
            DokanOptions::class,
            CheckStatus::class
        ];
        
        // Hook into WordPress REST API initialization.
        add_action( 'rest_api_init', [ $this, 'register_apis' ] );
    }

    /**
     * Register APIs.
     *
     * Iterates over the list of API classes and registers their routes.
     * 
     * @since 2.0.0
     * @return void
     */
    public function register_apis() {
        foreach ( $this->classes as $class ) {
            $api_instance = new $class(); // Instantiate the API class.
            
            // Check if the class has a method 'register_routes' and call it.
            if ( method_exists( $api_instance, 'register_routes' ) ) {
                $api_instance->register_routes();
            }
        }
    }
}