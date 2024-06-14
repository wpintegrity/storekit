<?php

namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Stock functions Manager Class
 */
class Stock {

    /**
     * Constructor function
     */
    public function __construct() {
        add_action( 'save_post_product', [ $this, 'default_product_stock' ] );
        add_action( 'dokan_new_product_added', [ $this, 'default_product_stock_for_vendors' ] );
        add_action( 'woocommerce_is_sold_individually', [ $this, 'storekit_product_sold_individually' ], 10, 2 );
    }

    /**
     * Set default product stock
     *
     * @param int $post_id
     * 
     * @since 1.0
     */
    function default_product_stock( $post_id ){
        $product_stock = Options::get_option( 'default_product_stock', 'woocommerce', '' );
        $post_author = get_post_field( 'post_author', $post_id );
        $user = get_userdata( $post_author );
        $user_roles = $user->roles;

        if( $product_stock > 0 && in_array( 'administrator', $user_roles ) ){
            update_post_meta( $post_id, '_manage_stock', 'yes' );
            update_post_meta( $post_id, '_stock', $product_stock );
        }
    }

    /**
     * Default Product Stock for Dokan Vendors
     * 
     * @param int $post_id
     * 
     * @since 1.0
     */
    function default_product_stock_for_vendors( $post_id ) {
        $dk_product_stock = Options::get_option('default_product_stock', 'dokan', '');
        $post_author = get_post_field('post_author', $post_id);
        $user = get_userdata($post_author);
        $user_roles = $user->roles;

        if ($dk_product_stock > 0 && in_array('seller', $user_roles)) {
            update_post_meta($post_id, '_manage_stock', 'yes');
            update_post_meta($post_id, '_stock', $dk_product_stock);
        }
    }

    /**
     * Product Sold Individually
     * 
     * @since 1.0.1
     */
    function storekit_product_sold_individually( $individually, $product ) {
        $wc_sold_individually = Options::get_option( 'product_individual_sale', 'woocommerce', false );
        $dk_sold_individually = Options::get_option( 'product_individual_sale', 'dokan', false );
        $post_author = get_post_field('post_author', $product->get_id());
        $user = get_userdata($post_author);
        $user_roles = $user->roles;
        
        if ( storekit()->has_dokan() ) {
            if ( in_array('seller', $user_roles) && $dk_sold_individually == true ) {    
                $individually = true;
            } elseif ( in_array('administrator', $user_roles) && $wc_sold_individually == true ) {
                $individually = true;
            }
        } elseif ( $wc_sold_individually == true ) {
            $individually = true;
        }
        
        return $individually;
    }
}