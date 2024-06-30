<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Features Manager Class.
 *
 * Initializes and manages various StoreKit features.
 *
 * @since 2.0.0
 */
class Manager {
    /**
     * Class constructor.
     *
     * Instantiates various feature classes.
     *
     * @since 2.0.0
     */
    public function __construct() {
        // Initialize core features
        new VendorDashboard();
        new Upload();
        new Cart();
        new Stock();
        new Shipping();
        new Registration();
        new Notices();
        new Products();
        new Miscellaneous();
        new Frontend();

        // Conditionally initialize the Profile Avatar feature
        if ( Options::get_option( 'manage_profile_avatar', 'woocommerce' ) === true ) {
            new ProfileAvatar();
        }
    }
}
