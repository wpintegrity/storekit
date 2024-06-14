<?php

namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Upload functions Manager Class
 */
class Upload {

    /**
     * Constructor function
     */
    public function __construct() {
        add_action( 'upload_size_limit', [ $this, 'dokan_vendor_file_upload_size' ] );
    }
    
    /**
     * Limit vendor file upload size
     *
     * @param int $size Upload size limit in bytes.
     * @return int
     */
    public function dokan_vendor_file_upload_size( $size ) {
        if( ! storekit()->has_dokan() ){
            return $size;
        }
    
        if( is_user_logged_in() ){
            $user = wp_get_current_user();
            $roles = (array) $user->roles;
    
            if( in_array( 'seller', $roles ) ){
                $size = 1048576 * storekit_get_option( 'limit_file_upload_size', 'dokan', '1' );
            }
        }
    
        return $size;
    }

}