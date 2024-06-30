<?php
namespace WpIntegrity\StoreKit\Api;

use WP_REST_Controller;
use WP_Error;

/**
 * Base API Class
 *
 * Provides a base class for StoreKit API endpoints with permission checks.
 *
 * @since 2.0.0
 */
class Base extends WP_REST_Controller {

    /**
     * Check if the current user has the necessary permissions for API requests.
     *
     * This function checks if the current user has the 'manage_options' capability,
     * which is typically an admin-level capability in WordPress.
     *
     * @since 2.0.0
     *
     * @param WP_REST_Request $request The current request object.
     *
     * @return bool|WP_Error True if the user has permissions, otherwise a WP_Error object.
     */
    public function admin_permission_check( $request ) {
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }

        return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to access this endpoint.', 'storekit' ), [ 'status' => 403 ] );
    }
}
