<?php
namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Frontend Pages Handler.
 *
 * Handles the frontend functionalities for StoreKit.
 *
 * @since 2.0.0
 */
class Frontend {

    /**
     * Class constructor.
     *
     * Initializes actions and hooks for the frontend.
     *
     * @since 2.0.0
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        $storekit_sold_by_label = Options::get_option( 'sold_by_label', 'dokan', 'none' );

        if ( storekit()->has_dokan() ) {
            if ( $storekit_sold_by_label != 'none' ) {
                switch ( $storekit_sold_by_label ) {
                    case 'add-to-cart':
                        add_action( 'woocommerce_after_shop_loop_item', [ $this, 'storekit_sold_by_product' ] );
                        break;
                    case 'product-price':
                        add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'storekit_sold_by_product' ] );
                        break;
                    case 'product-title':
                        add_action( 'woocommerce_shop_loop_item_title', [ $this, 'storekit_sold_by_product' ] );
                        break;
                }
            }
        }
    }

    /**
     * Enqueue frontend scripts and styles.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'storekit-frontend' );

        if ( is_account_page() ) {
            wp_enqueue_script( 'storekit-frontend' );
        }
    }

    /**
     * Display the "Sold by" label on the shop/product loop.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function storekit_sold_by_product() {
        global $product;
        $vendor = dokan_get_vendor_by_product( $product->get_id() );
        $store_rating = $vendor->get_rating();
        ?>

        <div class="storekit_sold_by_container">
            <div class="storekit_sold_by_wrapper">
                <span class="storekit_sold_by_label"><?php esc_html_e( 'Store:', 'storekit' ); ?>&nbsp;</span>
                <img src="<?php echo esc_url( $vendor->get_avatar() ); ?>" alt="<?php echo esc_attr( $vendor->get_shop_name() ); ?>">&nbsp;
                <a href="<?php echo esc_url( $vendor->get_shop_url() ); ?>"><?php echo esc_html( $vendor->get_shop_name() ); ?></a>
            </div>
            <div class="storekit_store_rating">
                <?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
            </div>
        </div>

        <?php
    }
}
