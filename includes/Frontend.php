<?php
namespace StoreKit;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_filter( 'wc_get_template', [ $this, 'storekit_get_template'], 99, 5 );

        $storekit_woocommerce_product_video = storekit_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );
        if( $storekit_woocommerce_product_video == 'on' ){
            add_action( 'woocommerce_before_add_to_cart_form', [ $this, 'storekit_show_soundcloud_player' ] );
        }

        $storekit_sold_by_label = storekit_get_option( 'dk_sold_by_label', 'dokan', 'add-to-cart' );
        
        if( $storekit_sold_by_label != 'none' && $storekit_sold_by_label == 'add-to-cart' ){
            add_action( 'woocommerce_after_shop_loop_item', [ $this, 'storekit_sold_by_product' ] );
        } elseif ( $storekit_sold_by_label != 'none' && $storekit_sold_by_label == 'product-price' ){
            add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'storekit_sold_by_product' ] );
        } elseif ( $storekit_sold_by_label != 'none' && $storekit_sold_by_label == 'product-title' ){
            add_action( 'woocommerce_shop_loop_item_title', [ $this, 'storekit_sold_by_product' ] );
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

        if( is_product() ){
            wp_enqueue_style( 'storekit-magnific-popup' );
            wp_enqueue_script( 'storekit-magnific-popup' );

            wp_enqueue_style( 'storekit-flexslider' );
            wp_enqueue_script( 'storekit-flexslider' );
        }

    }

    /**
     * 
     * Override the default product-image.php template file
     * 
     */
    public function storekit_get_template( $located, $template_name, $args, $template_path, $default_path ){

        $storekit_woocommerce_product_video = storekit_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );

        if ( 'single-product/product-image.php' == $template_name && $storekit_woocommerce_product_video == 'on') {
            remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

            $located = STOREKIT_PATH . '/templates/product-gallery.php';
        }

        return $located;
    }    

    /**
     * 
     * Show soundcloud player on the single product page
     * 
     */
    function storekit_show_soundcloud_player(){
        global $post;
        $storekit_sc_title   = get_post_meta( $post->ID, '_storekit_product_audio_title', true );
        $storekit_sc_url     = get_post_meta( $post->ID, '_storekit_product_audio_url', true );

        if( !empty( $storekit_sc_url ) ):
        ?>

        <iframe width="100%" height="166" scrolling="no" frameborder="no" class="storekit_soundcloud-player" src="https://w.soundcloud.com/player/?url= <?php echo urlencode( $storekit_sc_url ); ?>"></iframe>
            
        <div class="storekit_soundcloud-info"><a href="<?php echo $storekit_sc_url; ?>" title="<?php echo $storekit_sc_title; ?>" target="_blank" style="color: #cccccc; text-decoration: none;"><?php echo $storekit_sc_title; ?></a></div>

        <?php
        endif;
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
