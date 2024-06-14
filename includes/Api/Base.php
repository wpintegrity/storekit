<?php
namespace WpIntegrity\StoreKit\Api;

use WP_REST_Controller;
use WP_Error;

/**
 * Base API Class
 */
class Base extends WP_REST_Controller {
    /**
     * Permission check for the API requests
     * 
     * @param WP_REST_Request $request
     * 
     * @return bool|WP_Error
     */
    public function admin_permission_check( $request ) {
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }
        
        return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to access this endpoint.' ), [ 'status' => 403 ] );
    }
}