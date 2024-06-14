<?php

namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Products functions Manager Class
 */
class Products {

    /**
     * Constructor function
     */
    public function __construct() {
        if( Options::get_option( 'external_product_new_tab', 'woocommerce' ) === true ){
            add_filter( 'woocommerce_loop_add_to_cart_args', [ $this, 'open_external_products_in_new_tab'], 10, 2 );
            remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
            add_action( 'woocommerce_external_add_to_cart', [ $this, 'storekit_external_add_to_cart'] );
        }
    }

    /**
     * Open external products in a new tab.
     *
     * @param string $args The product link attributes.
     * @param string The modified product link HTML.
     * 
     * @since 2.0.0
     */
    public function open_external_products_in_new_tab( $args, $product ) {
        if ( $product->is_type('external') ) {
            $args[ 'attributes' ][ 'target' ] = '_blank';
        }

        return $args;
    }

    /**
	 * Output the external product add to cart button.
     * 
     * @since 2.0.0
	 */
	public function storekit_external_add_to_cart() {
		global $product;

		if ( ! $product->add_to_cart_url() ) {
			return;
		}

		Options::get_templates(
			'add-to-cart/woo-external.php',
			[
				'product_url' => $product->add_to_cart_url(),
				'button_text' => $product->single_add_to_cart_text(),
            ]
		);
	}
}