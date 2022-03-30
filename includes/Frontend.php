<?php
namespace WooComToolkit;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_filter( 'wc_get_template', [ $this, 'wctk_get_template'], 99, 5 );

        $wctk_woocommerce_product_video = wctk_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );
        if( $wctk_woocommerce_product_video == 'on' ){
            add_action( 'woocommerce_before_add_to_cart_form', [ $this, 'wctk_show_soundcloud_player' ] );
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
        wp_enqueue_style( 'woocom-toolkit-frontend' );
        wp_enqueue_script( 'woocom-toolkit-frontend' );

        if( is_product() ){
            wp_enqueue_style( 'woocom-toolkit-magnific-popup' );
            wp_enqueue_script( 'woocom-toolkit-magnific-popup' );

            wp_enqueue_style( 'woocom-toolkit-flexslider' );
            wp_enqueue_script( 'woocom-toolkit-flexslider' );
        }

    }

    /**
     * 
     * Override the default product-image.php template file
     * 
     */
    public function wctk_get_template( $located, $template_name, $args, $template_path, $default_path ){

        $wctk_woocommerce_product_video = wctk_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );

        if ( 'single-product/product-image.php' == $template_name && $wctk_woocommerce_product_video == 'on') {
            remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

            $located = WOOCOM_TOOLKIT_PATH . '/templates/product-gallery.php';
        }

        return $located;
    }    

    /**
     * 
     * Show soundcloud player on the single product page
     * 
     */
    function wctk_show_soundcloud_player(){
        global $post;
        $wctk_sc_title   = get_post_meta( $post->ID, '_wctk_product_audio_title', true );
        $wctk_sc_url     = get_post_meta( $post->ID, '_wctk_product_audio_url', true );

        if( !empty( $wctk_sc_url ) ):
        ?>

        <iframe width="100%" height="166" scrolling="no" frameborder="no" class="wctk_soundcloud-player" src="https://w.soundcloud.com/player/?url= <?php echo urlencode( $wctk_sc_url ); ?>"></iframe>
            
        <div class="wctk_soundcloud-info"><a href="<?php echo $wctk_sc_url; ?>" title="<?php echo $wctk_sc_title; ?>" target="_blank" style="color: #cccccc; text-decoration: none;"><?php echo $wctk_sc_title; ?></a></div>

        <?php
        endif;
    }

}
