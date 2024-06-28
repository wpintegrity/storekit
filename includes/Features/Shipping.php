<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Shipping Functions Manager Class.
 *
 * Manages shipping-related functionalities, such as hiding shipping methods when free shipping is available.
 */
class Shipping {

    /**
     * Constructor function.
     *
     * Initializes actions for managing shipping methods.
     */
    public function __construct() {
        add_action( 'woocommerce_package_rates', [ $this, 'hide_shipping_when_free_is_available' ], 100 );
    }
    
    /**
     * Hide shipping methods when free shipping is available.
     *
     * @since 1.0.0
     *
     * @param array $rates Array of shipping rates available for the package.
     * @return array Modified array of shipping rates.
     */
    public function hide_shipping_when_free_is_available( $rates ) {
        $wc_hide_shipping = Options::get_option( 'hide_shipping_methods', 'woocommerce', false );

        if ( $wc_hide_shipping === true ) {
            $free = array();

            foreach ( $rates as $rate_id => $rate ) {
                if ( 'free_shipping' === $rate->method_id || strpos( $rate->id, 'free_shipping' ) !== false ) {
                    $free[$rate_id] = $rate;
                    break;
                }
            }

            return ! empty( $free ) ? $free : $rates;
        }

        return $rates;
    }
}
