<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Stock Functions Manager Class.
 *
 * Manages default product stock and sold individually settings.
 */
class Stock {

    /**
     * Constructor function.
     *
     * Initializes actions for managing default product stock and sold individually settings.
     */
    public function __construct() {
        add_action( 'save_post_product', [ $this, 'default_product_stock' ] );
        add_action( 'dokan_new_product_added', [ $this, 'default_product_stock_for_vendors' ] );
        add_action( 'woocommerce_is_sold_individually', [ $this, 'storekit_product_sold_individually' ], 10, 2 );
    }

    /**
     * Set default product stock.
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID of the product being saved.
     */
    public function default_product_stock( $post_id ) {
        $product_stock = Options::get_option( 'default_product_stock', 'woocommerce' );

        $post_author   = get_post_field( 'post_author', $post_id );
        $user          = get_userdata( $post_author );
        $user_roles    = $user->roles;

        if ( $product_stock > 0 && in_array( 'administrator', $user_roles ) ) {
            update_post_meta( $post_id, '_manage_stock', 'yes' );
            update_post_meta( $post_id, '_stock', $product_stock );
        }
    }

    /**
     * Default Product Stock for Dokan Vendors.
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID of the product being saved.
     */
    public function default_product_stock_for_vendors( $post_id ) {
        $dokan_product_stock = Options::get_option( 'default_product_stock', 'dokan' );

        $post_author         = get_post_field( 'post_author', $post_id );
        $user                = get_userdata( $post_author );
        $user_roles          = $user->roles;

        if ( $dokan_product_stock > 0 && in_array( 'seller', $user_roles ) ) {
            update_post_meta( $post_id, '_manage_stock', 'yes' );
            update_post_meta( $post_id, '_stock', $dokan_product_stock );
        }
    }

    /**
     * Filter whether a product is sold individually.
     *
     * @since 1.0.0
     *
     * @param bool   $individually Whether the product is sold individually.
     * @param object $product      The product object.
     * @return bool Whether the product is sold individually.
     */
    public function storekit_product_sold_individually( $individually, $product ) {
        $woo_sold_individually   = Options::get_option( 'product_individual_sale', 'woocommerce', 'no' );
        $dokan_sold_individually = Options::get_option( 'product_individual_sale', 'dokan', 'no' );
    
        $post_author = get_post_field( 'post_author', $product->get_id() );
        $user        = get_userdata( $post_author );
    
        if ( $user ) { // Ensure $user is not null
            $user_roles  = $user->roles;
        } else {
            $user_roles = array(); // Set to an empty array if $user is null
        }
    
        if ( storekit()->has_dokan() && in_array( 'seller', $user_roles, true ) && 'yes' === $dokan_sold_individually ) {
            $individually = true;
        } elseif ( ! storekit()->has_dokan() && in_array( 'administrator', $user_roles, true ) && 'yes' === $woo_sold_individually ) {
            $individually = true;
        } elseif ( 'yes' === $woo_sold_individually ) {
            $individually = true;
        }
    
        if ( $individually && is_cart() ) {
            echo wp_kses_post( '<i>Single Unit</i>' );
        }
    
        return $individually;
    }       
}
