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
        add_filter( 'upload_size_limit', [ $this, 'dokan_vendor_file_upload_size' ] );
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
            $user  = wp_get_current_user();
            $roles = (array) $user->roles;
    
            if( in_array( 'seller', $roles ) ){
                $limit_file_upload_size = (int) Options::get_option( 'limit_file_upload_size', 'dokan' );

                // Check if the limit_file_upload_size is less than or equal to zero or empty
                if ($limit_file_upload_size <= 0 || empty($limit_file_upload_size)) {
                    $size = wp_convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) );
                } else {
                    $size = 1048576 * $limit_file_upload_size;
                }
            }
        }
    
        return $size;
    }
}