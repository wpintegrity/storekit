<?php
namespace StoreKit;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        $storekit_sold_by_label = storekit_get_option( 'dk_sold_by_label', 'dokan', 'add-to-cart' );
        
        if( storekit()->has_dokan() ){

            if( $storekit_sold_by_label != 'none' && $storekit_sold_by_label == 'add-to-cart' ){
                add_action( 'woocommerce_after_shop_loop_item', [ $this, 'storekit_sold_by_product' ] );
            } elseif ( $storekit_sold_by_label != 'none' && $storekit_sold_by_label == 'product-price' ){
                add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'storekit_sold_by_product' ] );
            } elseif ( $storekit_sold_by_label != 'none' && $storekit_sold_by_label == 'product-title' ){
                add_action( 'woocommerce_shop_loop_item_title', [ $this, 'storekit_sold_by_product' ] );
            }

        }
    }

    /**
     * Render frontend app
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function enqueue_scripts( $atts, $content = '' ) {
        wp_enqueue_style( 'storekit-frontend' );
        wp_enqueue_script( 'storekit-frontend' );

    }

    /**
     *   
     * Sold by label on the shop/product loop
     *
     */
    function storekit_sold_by_product(){
        global $product;
        $vendor = dokan_get_vendor_by_product( $product->get_id() );
        $store_rating = $vendor->get_rating();

        ?>

        <div class="storekit_sold_by_container">
            <div class="storekit_sold_by_wrapper">
                <span class="storekit_sold_by_label">Store:&nbsp;</span>
                <img src="<?php echo esc_url( $vendor->get_avatar() ); ?>" alt="<?php echo esc_attr( $vendor->get_shop_name() ); ?>">&nbsp;
                <a href="<?php echo esc_attr( $vendor->get_shop_url() ); ?>"><?php echo esc_html( $vendor->get_shop_name() ); ?></a>
            </div>
            <div class="storekit_store_rating">
                <?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
            </div>
        </div>

        <?php

    }

}
