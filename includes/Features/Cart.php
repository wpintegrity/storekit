<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Cart functions Manager Class.
 *
 * Handles cart-related functionalities for StoreKit.
 *
 * @since 2.0.0
 */
class Cart {

    /**
     * Class constructor.
     *
     * Initializes actions and hooks for the cart.
     *
     * @since 2.0.0
     */
    public function __construct() {
        add_action( 'woocommerce_cart_actions', [ $this, 'clear_cart_button' ] );
        add_action( 'wp_head', [ $this, 'clear_cart_session' ] );
        add_action( 'woocommerce_cart_loaded_from_session', [ $this, 'sort_cart_by_vendor_store_name' ], 100 );
    }

    /**
     * Add a clear cart button to the cart page.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function clear_cart_button() {
        if ( Options::get_option( 'clear_cart_button', 'woocommerce', true ) === true ) :
        ?>
            <button type="submit" class="button" name="clear_cart" value="<?php esc_attr_e( 'Clear cart', 'storekit' ); ?>"><?php esc_html_e( 'Clear cart', 'storekit' ); ?></button>
        <?php
        endif;
    }

    /**
     * Clear cart session when clear cart button is clicked.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function clear_cart_session() {
        if ( isset( $_REQUEST['clear_cart'] ) ) {
            WC()->cart->empty_cart();
        }
    }

    /**
     * Sort products by vendor name in the cart.
     *
     * @since 1.0.0
     *
     * @param WC_Cart $cart WooCommerce Cart object.
     * @return void
     */
    public function sort_cart_by_vendor_store_name( $cart ) {
        if ( ! storekit()->has_dokan() ) {
            return;
        }

        $sort_order = Options::get_option( 'sort_product_by_vendor', 'dokan', 'none' );
        if ( $sort_order === 'none' ) {
            return;
        }

        $products_in_cart = [];
        foreach ( $cart->get_cart() as $key => $item ) {
            $vendor = dokan_get_vendor_by_product( $item['data']->get_id() );
            $products_in_cart[ $key ] = $vendor->get_shop_name();
        }

        if ( $sort_order === 'asc' ) {
            asort( $products_in_cart );
        } elseif ( $sort_order === 'desc' ) {
            arsort( $products_in_cart );
        }

        $cart_contents = [];
        foreach ( $products_in_cart as $cart_key => $vendor_store_name ) {
            $cart_contents[ $cart_key ] = $cart->cart_contents[ $cart_key ];
        }

        $cart->set_cart_contents( $cart_contents );
        $cart->set_session();
    }
}
