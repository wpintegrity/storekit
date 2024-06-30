<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Miscellaneous Class.
 *
 * Handles miscellaneous functionalities related to admin bar menus.
 */
class Miscellaneous {

    /**
     * Constructor function.
     *
     * Initializes actions to display admin bar menus.
     */
    public function __construct() {
        if ( Options::get_option( 'my_account_admin_menu', 'woocommerce' ) !== false ) {
            add_action( 'admin_bar_menu', [ $this, 'admin_bar_menus' ], 32 );
        }
    }
    
    /**
     * Add "Visit My Account" link to the admin bar.
     *
     * @since 2.0.0
     *
     * @param WP_Admin_Bar $wp_admin_bar WordPress admin bar object.
     */
    public function admin_bar_menus( $wp_admin_bar ) {
        if ( ! is_admin() || ! is_admin_bar_showing() ) {
            return;
        }

        // Show only when the user is a member of this site, or they're a super admin.
        if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
            return;
        }

        // Add "Visit My Account" link to the admin bar.
        $wp_admin_bar->add_node(
            [
                'parent' => 'site-name',
                'id'     => 'view-account-page',
                'title'  => __( 'Visit My Account', 'storekit' ),
                'href'   => wc_get_page_permalink( 'myaccount' ),
            ]
        );
    }
}
