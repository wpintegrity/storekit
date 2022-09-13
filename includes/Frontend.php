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

        $wc_product_featured_video  = storekit_get_option( 'wc_product_featured_video', 'woocommerce', 'on' );
        $dk_product_featured_video  = storekit_get_option( 'dk_product_featured_video', 'dokan', 'on' );
        if( $wc_product_featured_video == 'on' || $dk_product_featured_video == 'on' ){
            add_filter( 'wc_get_template', [ $this, 'storekit_product_gallery_template'], 99, 5 );
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
     * @since 1.0
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

    /**
     * If the template being loaded is the product image template, load the product gallery template
     * instead
     * 
     * @param located The path of the file that WooCommerce was going to use.
     * @param template_name The name of the template (ex: single-product/product-image.php)
     * @param args (array) Arguments passed to the template.
     * @param template_path The path to the template file.
     * @param default_path The default path to the template file.
     * 
     * @return The product gallery template.
     * 
     * @since 2.0
     * 
     */

    function storekit_product_gallery_template( $located, $template_name, $args, $template_path, $default_path ) {
        if ( 'single-product/product-image.php' == $template_name ) {
            $located = STOREKIT_PATH . '/templates/product-gallery.php';
        }
        return $located;
    }

}
