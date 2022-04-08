<?php
namespace WooKit;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_filter( 'wc_get_template', [ $this, 'wookit_get_template'], 99, 5 );

        $wookit_woocommerce_product_video = wookit_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );
        if( $wookit_woocommerce_product_video == 'on' ){
            add_action( 'woocommerce_before_add_to_cart_form', [ $this, 'wookit_show_soundcloud_player' ] );
        }

        $wookit_sold_by_label = wookit_get_option( 'dk_sold_by_label', 'dokan', 'add-to-cart' );
        
        if( $wookit_sold_by_label != 'none' && $wookit_sold_by_label == 'add-to-cart' ){
            add_action( 'woocommerce_after_shop_loop_item', [ $this, 'wookit_sold_by_product' ] );
        } elseif ( $wookit_sold_by_label != 'none' && $wookit_sold_by_label == 'product-price' ){
            add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'wookit_sold_by_product' ] );
        } elseif ( $wookit_sold_by_label != 'none' && $wookit_sold_by_label == 'product-title' ){
            add_action( 'woocommerce_shop_loop_item_title', [ $this, 'wookit_sold_by_product' ] );
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
        wp_enqueue_style( 'wookit-frontend' );
        wp_enqueue_script( 'wookit-frontend' );

        if( is_product() ){
            wp_enqueue_style( 'wookit-magnific-popup' );
            wp_enqueue_script( 'wookit-magnific-popup' );

            wp_enqueue_style( 'wookit-flexslider' );
            wp_enqueue_script( 'wookit-flexslider' );
        }

    }

    /**
     * 
     * Override the default product-image.php template file
     * 
     */
    public function wookit_get_template( $located, $template_name, $args, $template_path, $default_path ){

        $wookit_woocommerce_product_video = wookit_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );

        if ( 'single-product/product-image.php' == $template_name && $wookit_woocommerce_product_video == 'on') {
            remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

            $located = WOOKIT_PATH . '/templates/product-gallery.php';
        }

        return $located;
    }    

    /**
     * 
     * Show soundcloud player on the single product page
     * 
     */
    function wookit_show_soundcloud_player(){
        global $post;
        $wookit_sc_title   = get_post_meta( $post->ID, '_wookit_product_audio_title', true );
        $wookit_sc_url     = get_post_meta( $post->ID, '_wookit_product_audio_url', true );

        if( !empty( $wookit_sc_url ) ):
        ?>

        <iframe width="100%" height="166" scrolling="no" frameborder="no" class="wookit_soundcloud-player" src="https://w.soundcloud.com/player/?url= <?php echo urlencode( $wookit_sc_url ); ?>"></iframe>
            
        <div class="wookit_soundcloud-info"><a href="<?php echo $wookit_sc_url; ?>" title="<?php echo $wookit_sc_title; ?>" target="_blank" style="color: #cccccc; text-decoration: none;"><?php echo $wookit_sc_title; ?></a></div>

        <?php
        endif;
    }

    /**
     *   
     * Sold by label on the shop/product loop
     *
     */
    function wookit_sold_by_product(){
        global $product;
        $vendor = dokan_get_vendor_by_product( $product->get_id() );
        $store_rating = $vendor->get_rating();

        ?>

        <div class="wookit_sold_by_container">
            <div class="wookit_sold_by_wrapper">
                <span class="wookit_sold_by_label">Store:&nbsp;</span>
                <img src="<?php echo esc_url( $vendor->get_avatar() ); ?>" alt="<?php echo esc_attr( $vendor->get_shop_name() ); ?>">&nbsp;
                <a href="<?php echo esc_attr( $vendor->get_shop_url() ); ?>"><?php echo esc_html( $vendor->get_shop_name() ); ?></a>
            </div>
            <div class="wookit_store_rating">
                <?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
            </div>
        </div>

        <?php

    }

}
