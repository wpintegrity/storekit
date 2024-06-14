<?php

namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Shipping functions Manager Class
 */
class Shipping {

    /**
     * Constructor function
     */
    public function __construct() {
        add_action( 'woocommerce_package_rates', [ $this, 'hide_shipping_when_free_is_available' ], 100 );
    }
    
    /**
     * Hide shipping methods when free shipping is available
     * 
     * @since 1.0
     */
    function hide_shipping_when_free_is_available( $rates ) {
        $wc_hide_shipping = Options::get_option( 'hide_shipping_methods', 'woocommerce', false );

        if ( $wc_hide_shipping === true ) {
            $free = array();

            foreach ($rates as $rate_id => $rate) {
                if ( 'free_shipping' === $rate->method_id || strpos($rate->id, 'free_shipping' ) !== false ) {
                    $free[$rate_id] = $rate;
                    break;
                }
            }

            return !empty($free) ? $free : $rates;
        }

        return $rates;
    }

}