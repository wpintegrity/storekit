<?php

/**
 * Get the value of a settings field
 *
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 *
 * @return mixed
 */
function wookit_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[ $option ] ) ) {
        return $options[ $option ];
    }

    return $default;
}

/**
 * 
 * Create WooCom Toolkit Custom Product Tab
 * 
 */

function wookit_product_settings_tabs( $tabs ){
 
	$tabs[ 'wookit_product_tab' ] = array(
		'label'    => __( 'WooKit' ),
		'target'   => 'wookit_product_data',
		'priority' => 99,
	);
	return $tabs;
 
}
add_filter( 'woocommerce_product_data_tabs', 'wookit_product_settings_tabs' );

/** 
 *
 * Add input fields to the WooCom Toolkit Product Data Tab
 *  
 */
function wookit_product_data() {
    global $woocommerce, $post;

    echo '<div id="wookit_product_data" class="panel woocommerce_options_panel hidden">';

    $wookit_woocommerce_product_video = wookit_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );
    $wookit_woocommerce_product_audio = wookit_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );

    if( $wookit_woocommerce_product_video == 'on' ){

        echo '<div class="options_group wookit_product_video_url_field">';
    
        woocommerce_wp_text_input(
            array(
            'id'          => '_wookit_product_video_url',
            'label'       => __( 'Product Video URL', 'woocom-toolkit' ),
            'placeholder' => 'https://www.youtube.com/watch?v=tt2k8PGm-TI',
            'description' => __( 'Insert your YouTube Video URL', 'woocom-toolkit' ),
            'desc_tip'    => 'true'
            )
        );

        echo '</div>';

    }

    if( $wookit_woocommerce_product_audio == 'on' ){

        echo '<div class="options_group wookit_product_audio_url_field">';
        
            woocommerce_wp_text_input(
                array(
                'id'          => '_wookit_product_audio_title',
                'label'       => __( 'Product Audio Title', 'wookit' ),
                'placeholder' => __( 'OneRepublic - Counting Stars', 'wookitt' ),
                'description' => __( 'Insert your Soundcloud audio/music title', 'wookit' ),
                'desc_tip'    => 'true'
                )
            );

            woocommerce_wp_text_input(
                array(
                'id'          => '_wookit_product_audio_url',
                'label'       => __( 'Product Audio URL', 'wookit' ),
                'placeholder' => 'https://soundcloud.com/interscope/onerepublic-counting-stars',
                'description' => __( 'Insert your Soundcloud audio/music URL', 'wookit' ),
                'desc_tip'    => 'true'
                )
            );

        echo '</div>';

    }

    echo '</div>';

}
add_action( 'woocommerce_product_data_panels', 'wookit_product_data' );

/**
 * 
 * Save WooCom Toolkit Product Data Tab's fields' values
 * 
 */
function wookit_product_data_save( $post_id ) {
    $wookit_product_video_url_field = $_POST['_wookit_product_video_url'];
    $wookit_product_audio_title_field = $_POST['_wookit_product_audio_title'];
    $wookit_product_audio_url_field = $_POST['_wookit_product_audio_url'];

    if ( isset( $wookit_product_video_url_field ) ){
        update_post_meta( $post_id, '_wookit_product_video_url', wp_kses_post( $wookit_product_video_url_field ) );
    }

    if ( isset( $wookit_product_audio_title_field ) ){
        update_post_meta( $post_id, '_wookit_product_audio_title', esc_attr( $wookit_product_audio_title_field ) );
    }

    if ( isset( $wookit_product_audio_url_field ) ){
        update_post_meta( $post_id, '_wookit_product_audio_url', wp_kses_post( $wookit_product_audio_url_field ) );
    }

}
add_action( 'woocommerce_process_product_meta', 'wookit_product_data_save' );


/**
 * 
 * Add input fields to the Dokan Vendor Dashboard - edit product form
 * 
 */
function wookit_dk_product_video_url_field( $post, $post_id ){
    $wookit_woocommerce_product_video = wookit_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );
    $wookit_dokan_product_video = wookit_get_option( 'dk_product_video_checkbox', 'dokan', 'off' );

    $wookit_woocommerce_product_video = wookit_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );
    $wookit_dokan_product_video = wookit_get_option( 'dk_product_audio_checkbox', 'dokan', 'off' );

    if( $wookit_woocommerce_product_video == 'on' && $wookit_dokan_product_video == 'off' ):
    ?>

    <div class="dokan-form-group">
    <label for="_wookit_product_video_url" class="form-label"><?php esc_html_e( 'Product Video URL', 'dokan-lite' ) ?></label>    

    <?php
        dokan_post_input_box( 
            $post_id,
            '_wookit_product_video_url', 
            [
                'placeholder'   => 'https://www.youtube.com/watch?v=tt2k8PGm-TI'
            ]
        );
    ?>

    </div>
    <?php endif;

    if( $wookit_woocommerce_product_video == 'on' && $wookit_dokan_product_video == 'off' ):
    ?>

    <div class="dokan-form-group">
    <label for="_wookit_product_audio_title" class="form-label"><?php esc_html_e( 'Product Audio Title', 'dokan-lite' ) ?></label>    
    <?php
        dokan_post_input_box( 
            $post_id,
            '_wookit_product_audio_title', 
            [
                'placeholder'   =>  __( 'OneRepublic - Counting Stars', 'woocom-toolkit' )
            ]
        );
    ?>

    <label for="_wookit_product_audio_url" class="form-label"><?php esc_html_e( 'Product Audio URL', 'dokan-lite' ) ?></label>    
    <?php
        dokan_post_input_box( 
            $post_id,
            '_wookit_product_audio_url', 
            [
                'placeholder'   => 'https://soundcloud.com/interscope/onerepublic-counting-stars'
            ]
        );
    ?>

    </div>
    <?php endif;
}
add_action( 'dokan_product_edit_after_product_tags', 'wookit_dk_product_video_url_field', 10, 2 );

/**
 * 
 * Save Dokan Vendor Dashboard - edit product form's fields' values
 * 
 */
function wookit_dk_product_video_url_field_save( $post_id, $data ){
    $wookit_dk_product_video_url_field = $data['_wookit_product_video_url'];
    $wookit_dk_product_audio_title_field = $data['_wookit_product_audio_title'];
    $wookit_dk_product_audio_url_field = $data['_wookit_product_audio_url'];

    if( isset( $wookit_dk_product_video_url_field ) ){
        update_post_meta( $post_id, '_wookit_product_video_url', wp_kses_post( $wookit_dk_product_video_url_field ) );
    }

    if( isset( $wookit_dk_product_audio_title_field ) ){
        update_post_meta( $post_id, '_wookit_product_audio_title', wp_kses_post( $wookit_dk_product_audio_title_field ) );
    }

    if( isset( $wookit_dk_product_audio_url_field ) ){
        update_post_meta( $post_id, '_wookit_product_audio_url', wp_kses_post( $wookit_dk_product_audio_url_field ) );
    }
}
add_action( 'dokan_product_updated', 'wookit_dk_product_video_url_field_save', 10, 2 );

/**
 * 
 * Vendor dashboard's Dashboard menu widget remove function
 * 
 */
function remove_vendor_dashboard_vendormenu_widgets(){ 
    $wookit_dk_vendor_dashboard_widget_options = wookit_get_option( 'dk_vendor_dashboard_widgets', 'dokan', '' );
    
    if( isset($wookit_dk_vendor_dashboard_widget_options['big-counter']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_big_counter_widgets', 10 );
    }

    if( isset($wookit_dk_vendor_dashboard_widget_options['orders']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_orders_widgets', 15 );
    }

    if( isset($wookit_dk_vendor_dashboard_widget_options['products']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_products_widgets', 20 );
    }

    if( isset($wookit_dk_vendor_dashboard_widget_options['reviews']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\DokanPro\Dashboard', 'get_review_widget', 16 );
    }

    if( isset($wookit_dk_vendor_dashboard_widget_options['sales-chart']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_right_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_sales_report_chart_widget', 10 );
    }

    if( isset($wookit_dk_vendor_dashboard_widget_options['announcement']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_right_widgets', 'WeDevs\DokanPro\Dashboard', 'get_announcement_widget', 12 );
    }

}
add_action( 'wp_head', 'remove_vendor_dashboard_vendormenu_widgets' );

/**
 * 
 * Vendor dashboard's Edit product form's section remove function
 * 
 */
function remove_edit_product_form_fields(){
    $wookit_dk_product_form_sections = wookit_get_option( 'dk_vendor_dashboard_product_form', 'dokan', '' );

    if( isset( $wookit_dk_product_form_sections['download-virtual'] ) ){
        remove_action( 'dokan_product_edit_after_title', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_download_virtual_template' ], 10, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['inventory'] ) ){
        remove_action( 'dokan_product_edit_after_main', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_inventory_template' ], 5, 2 );
    }
    
    if( isset( $wookit_dk_product_form_sections['downloadable'] ) ){
        remove_action( 'dokan_product_edit_after_main', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_downloadable_template' ], 10, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['other-options'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_others_template' ], 85, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['shipping-tax'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_shipping_tax_content' ], 10, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['linked-products'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_linked_product_content' ], 15, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['attributes'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_variations_content' ], 20, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['discount-options'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_lot_discount_content' ], 25, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['yoast-seo'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->product_seo, 'load_product_seo_content' ], 5, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['rankmath-seo'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->module->rank_math, 'load_product_seo_content' ], 6, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['geolocation'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_main', 'Dokan_Geolocation_Vendor_Dashboard', 'add_product_editor_options', 10 );
    }

    if( isset( $wookit_dk_product_form_sections['rma'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_inventory_variants', 'Dokan_RMA_Product', 'load_rma_content', 30, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['product-addon'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_main', 'Dokan_Product_Addon_Vendor_Product', 'add_addons_section', 15, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['wholesale'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_inventory_variants', 'Dokan_Wholesale_Vendor', 'load_wholesale_content', 30, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['order-min-max'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_inventory_variants', 'WeDevs\DokanPro\Modules\OrderMinMax\Vendor', 'load_min_max_meta_box', 31, 2 );
    }

    if( isset( $wookit_dk_product_form_sections['advertise'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_options', 'WeDevs\DokanPro\Modules\ProductAdvertisement\Frontend\Product', 'render_advertise_product_section', 99, 1 );
    }    

}
add_action( 'wp_head', 'remove_edit_product_form_fields' );


/**
 * 
 * Limit vendor file upload size
 * 
 */
function wookit_vendor_file_upload_size( $size ){
    $wookit_dk_inputf_size = wookit_get_option( 'dk_vendor_upload_size', 'dokan', '1' );;

    if( is_user_logged_in() ){
        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;

        if( in_array( 'seller', $roles ) == true && isset( $wookit_dk_inputf_size ) ){
            $size = 1048576 * $wookit_dk_inputf_size;
        }
    }

    return $size;
    
}
add_action( 'upload_size_limit', 'wookit_vendor_file_upload_size' );

/**
 * 
 * Sort products by vendor on the cart
 * 
 */

function wookit_sort_cart_by_vendor_store_name( $cart ) {

    $wookit_dk_sort_product = wookit_get_option( 'dk_sort_product_by_vendor', 'dokan', 'none' );

    if( $wookit_dk_sort_product != 'none' ){

        $products_in_cart = array();
    
        foreach ( $cart->get_cart() as $key => $item ) {
            $vendor = dokan_get_vendor_by_product( $item['data']->get_id() );
            $products_in_cart[ $key ] = $vendor->get_shop_name();
        }
        
        if( $wookit_dk_sort_product == 'asc' ){
            asort( $products_in_cart );
        } elseif( $wookit_dk_sort_product == 'desc' ){
            arsort( $products_in_cart );
        }
    
        $cart_contents = array();

        foreach ( $products_in_cart as $cart_key => $vendor_store_name ) {
            $cart_contents[ $cart_key ] = $cart->cart_contents[ $cart_key ];
        }
    
        $cart->set_cart_contents( $cart_contents );
        $cart->set_session();
    }
 
};

add_action( 'woocommerce_cart_loaded_from_session', 'wookit_sort_cart_by_vendor_store_name', 100 );

/**
 *   
 * Clear cart button to clear/empty cart 
 *
 */

function wookit_clear_cart_button(){
    $wookit_wc_clear_cart = wookit_get_option( 'wc_clear_cart', 'woocommerce', 'on' );

    if( $wookit_wc_clear_cart == 'on' ):
    ?>

    <button type="submit" class="button" name="clear_cart" value="<?php esc_attr_e( 'Clear cart', 'woocom-toolkit' ); ?>"><?php esc_html_e( 'Clear cart', 'woocom-toolkit' ); ?></button>

    <?php
    endif;
}
add_action( 'woocommerce_cart_actions', 'wookit_clear_cart_button' );


/**
 *   
 * Clear cart session
 *
 */
function wookit_clear_cart_session(){
    global $woocommerce;

    if( isset( $_REQUEST['clear_cart'] ) ){
        $woocommerce->cart->empty_cart(); 
    }
}
add_action( 'init', 'wookit_clear_cart_session' );

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
            <span class="wookit_sold_by_label">Store: </span>
            <img src="<?php echo esc_url( $vendor->get_avatar() ); ?>" alt="<?php echo esc_attr( $vendor->get_shop_name() ); ?>" style="width: 25px; border-radius: 50%;">
            <a href="<?php echo esc_attr( $vendor->get_shop_url() ); ?>"><?php echo esc_html( $vendor->get_shop_name() ); ?></a>
        </div>
        <div class="wookit_store_rating">
            <?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
        </div>
    </div>

    <?php

}
add_action('woocommerce_after_shop_loop_item', 'wookit_sold_by_product', 50 );