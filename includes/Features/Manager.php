<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Features Manager Class
 */
class Manager {
    /**
     * Class constructor
     */
    public function __construct() {
        new VendorDashboard();
        new Upload();
        // new Cart();
        new Stock();
        new Shipping();
        new Registration();
        new Notices();
        new Products();
        new Miscellaneous();
        new Frontend();

        if( Options::get_option( 'manage_profile_avatar', 'woocommerce' ) === true ) {
            new ProfileAvatar();
        }
    }
}