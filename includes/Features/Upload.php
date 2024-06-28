<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Upload Functions Manager Class.
 *
 * Manages file upload size limit for Dokan vendors.
 */
class Upload {

    /**
     * Constructor function.
     *
     * Initializes actions to limit file upload size for Dokan vendors.
     */
    public function __construct() {
        add_filter( 'upload_size_limit', [ $this, 'dokan_vendor_file_upload_size' ] );
    }
    
    /**
     * Limit vendor file upload size.
     *
     * @since 1.0.0
     *
     * @param int $size Upload size limit in bytes.
     * @return int Modified upload size limit.
     */
    public function dokan_vendor_file_upload_size( $size ) {
        if ( ! storekit()->has_dokan() ) {
            return $size;
        }
    
        if ( is_user_logged_in() ) {
            $user  = wp_get_current_user();
            $roles = (array) $user->roles;
    
            if ( in_array( 'seller', $roles ) ) {
                $limit_file_upload_size = (int) Options::get_option( 'limit_file_upload_size', 'dokan' );

                // If limit_file_upload_size is not set or less than or equal to zero, use PHP upload_max_filesize.
                if ( $limit_file_upload_size <= 0 || empty( $limit_file_upload_size ) ) {
                    $size = wp_convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) );
                } else {
                    $size = 1048576 * $limit_file_upload_size; // Convert megabytes to bytes.
                }
            }
        }
    
        return $size;
    }
}
