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
function storekit_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[ $option ] ) ) {
        return $options[ $option ];
    }

    return $default;
}

/**
 * 
 * Create StoreKit Custom Product Tab
 * 
 */

function storekit_product_settings_tabs( $tabs ){
 
	$tabs[ 'storekit_product_tab' ] = array(
		'label'    => __( 'storekit' ),
		'target'   => 'storekit_product_data',
		'priority' => 99,
	);
	return $tabs;
 
}
add_filter( 'woocommerce_product_data_tabs', 'storekit_product_settings_tabs' );

/** 
 *
 * Add input fields to the StoreKit Product Data Tab
 *  
 */
function storekit_product_data() {
    global $woocommerce, $post;

    echo '<div id="storekit_product_data" class="panel woocommerce_options_panel hidden">';

    $storekit_woocommerce_product_video = storekit_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );
    $storekit_woocommerce_product_audio = storekit_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );

    if( $storekit_woocommerce_product_video == 'on' ){

        echo '<div class="options_group storekit_product_video_url_field">';
    
        woocommerce_wp_text_input(
            array(
            'id'          => '_storekit_product_video_url',
            'label'       => __( 'Product Video URL', 'woocom-toolkit' ),
            'placeholder' => 'https://www.youtube.com/watch?v=tt2k8PGm-TI',
            'description' => __( 'Insert your YouTube Video URL', 'woocom-toolkit' ),
            'desc_tip'    => 'true'
            )
        );

        echo '</div>';

    }

    if( $storekit_woocommerce_product_audio == 'on' ){

        echo '<div class="options_group storekit_product_audio_url_field">';
        
            woocommerce_wp_text_input(
                array(
                'id'          => '_storekit_product_audio_title',
                'label'       => __( 'Product Audio Title', 'storekit' ),
                'placeholder' => __( 'OneRepublic - Counting Stars', 'storekit' ),
                'description' => __( 'Insert your Soundcloud audio/music title', 'storekit' ),
                'desc_tip'    => 'true'
                )
            );

            woocommerce_wp_text_input(
                array(
                'id'          => '_storekit_product_audio_url',
                'label'       => __( 'Product Audio URL', 'storekit' ),
                'placeholder' => 'https://soundcloud.com/interscope/onerepublic-counting-stars',
                'description' => __( 'Insert your Soundcloud audio/music URL', 'storekit' ),
                'desc_tip'    => 'true'
                )
            );

        echo '</div>';

    }

    echo '</div>';

}
add_action( 'woocommerce_product_data_panels', 'storekit_product_data' );

/**
 * 
 * Save StoreKit Product Data Tab's fields' values
 * 
 */
function storekit_product_data_save( $post_id ) {
    $storekit_product_video_url_field = $_POST['_storekit_product_video_url'];
    $storekit_product_audio_title_field = $_POST['_storekit_product_audio_title'];
    $storekit_product_audio_url_field = $_POST['_storekit_product_audio_url'];

    if ( isset( $storekit_product_video_url_field ) ){
        update_post_meta( $post_id, '_storekit_product_video_url', wp_kses_post( $storekit_product_video_url_field ) );
    }

    if ( isset( $storekit_product_audio_title_field ) ){
        update_post_meta( $post_id, '_storekit_product_audio_title', esc_attr( $storekit_product_audio_title_field ) );
    }

    if ( isset( $storekit_product_audio_url_field ) ){
        update_post_meta( $post_id, '_storekit_product_audio_url', wp_kses_post( $storekit_product_audio_url_field ) );
    }

}
add_action( 'woocommerce_process_product_meta', 'storekit_product_data_save' );


/**
 * 
 * Add input fields to the Dokan Vendor Dashboard - edit product form
 * 
 */
function storekit_dk_product_video_url_field( $post, $post_id ){
    $storekit_woocommerce_product_video = storekit_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );
    $storekit_dokan_product_video = storekit_get_option( 'dk_product_video_checkbox', 'dokan', 'off' );

    $storekit_woocommerce_product_video = storekit_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );
    $storekit_dokan_product_video = storekit_get_option( 'dk_product_audio_checkbox', 'dokan', 'off' );

    if( $storekit_woocommerce_product_video == 'on' && $storekit_dokan_product_video == 'off' ):
    ?>

    <div class="dokan-form-group">
    <label for="_storekit_product_video_url" class="form-label"><?php esc_html_e( 'Product Video URL', 'dokan-lite' ) ?></label>    

    <?php
        dokan_post_input_box( 
            $post_id,
            '_storekit_product_video_url', 
            [
                'placeholder'   => 'https://www.youtube.com/watch?v=tt2k8PGm-TI'
            ]
        );
    ?>

    </div>
    <?php endif;

    if( $storekit_woocommerce_product_video == 'on' && $storekit_dokan_product_video == 'off' ):
    ?>

    <div class="dokan-form-group">
    <label for="_storekit_product_audio_title" class="form-label"><?php esc_html_e( 'Product Audio Title', 'dokan-lite' ) ?></label>    
    <?php
        dokan_post_input_box( 
            $post_id,
            '_storekit_product_audio_title', 
            [
                'placeholder'   =>  __( 'OneRepublic - Counting Stars', 'woocom-toolkit' )
            ]
        );
    ?>

    <label for="_storekit_product_audio_url" class="form-label"><?php esc_html_e( 'Product Audio URL', 'dokan-lite' ) ?></label>    
    <?php
        dokan_post_input_box( 
            $post_id,
            '_storekit_product_audio_url', 
            [
                'placeholder'   => 'https://soundcloud.com/interscope/onerepublic-counting-stars'
            ]
        );
    ?>

    </div>
    <?php endif;
}
add_action( 'dokan_product_edit_after_product_tags', 'storekit_dk_product_video_url_field', 10, 2 );

/**
 * 
 * Save Dokan Vendor Dashboard - edit product form's fields' values
 * 
 */
function storekit_dk_product_video_url_field_save( $post_id, $data ){
    $storekit_dk_product_video_url_field = $data['_storekit_product_video_url'];
    $storekit_dk_product_audio_title_field = $data['_storekit_product_audio_title'];
    $storekit_dk_product_audio_url_field = $data['_storekit_product_audio_url'];

    if( isset( $storekit_dk_product_video_url_field ) ){
        update_post_meta( $post_id, '_storekit_product_video_url', wp_kses_post( $storekit_dk_product_video_url_field ) );
    }

    if( isset( $storekit_dk_product_audio_title_field ) ){
        update_post_meta( $post_id, '_storekit_product_audio_title', wp_kses_post( $storekit_dk_product_audio_title_field ) );
    }

    if( isset( $storekit_dk_product_audio_url_field ) ){
        update_post_meta( $post_id, '_storekit_product_audio_url', wp_kses_post( $storekit_dk_product_audio_url_field ) );
    }
}
add_action( 'dokan_product_updated', 'storekit_dk_product_video_url_field_save', 10, 2 );

/**
 * 
 * Vendor dashboard's Dashboard menu widget remove function
 * 
 */
function remove_vendor_dashboard_vendormenu_widgets(){ 
    $storekit_dk_vendor_dashboard_widget_options = storekit_get_option( 'dk_vendor_dashboard_widgets', 'dokan', '' );
    
    if( isset($storekit_dk_vendor_dashboard_widget_options['big-counter']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_big_counter_widgets', 10 );
    }

    if( isset($storekit_dk_vendor_dashboard_widget_options['orders']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_orders_widgets', 15 );
    }

    if( isset($storekit_dk_vendor_dashboard_widget_options['products']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_products_widgets', 20 );
    }

    if( isset($storekit_dk_vendor_dashboard_widget_options['reviews']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\DokanPro\Dashboard', 'get_review_widget', 16 );
    }

    if( isset($storekit_dk_vendor_dashboard_widget_options['sales-chart']) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_right_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_sales_report_chart_widget', 10 );
    }

    if( isset($storekit_dk_vendor_dashboard_widget_options['announcement']) ){
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
    $storekit_dk_product_form_sections = storekit_get_option( 'dk_vendor_dashboard_product_form', 'dokan', '' );

    if( isset( $storekit_dk_product_form_sections['download-virtual'] ) ){
        remove_action( 'dokan_product_edit_after_title', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_download_virtual_template' ], 10, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['inventory'] ) ){
        remove_action( 'dokan_product_edit_after_main', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_inventory_template' ], 5, 2 );
    }
    
    if( isset( $storekit_dk_product_form_sections['downloadable'] ) ){
        remove_action( 'dokan_product_edit_after_main', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_downloadable_template' ], 10, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['other-options'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_others_template' ], 85, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['shipping-tax'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_shipping_tax_content' ], 10, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['linked-products'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_linked_product_content' ], 15, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['attributes'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_variations_content' ], 20, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['discount-options'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->products, 'load_lot_discount_content' ], 25, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['yoast-seo'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->product_seo, 'load_product_seo_content' ], 5, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['rankmath-seo'] ) ){
        remove_action( 'dokan_product_edit_after_inventory_variants', [ dokan_pro()->module->rank_math, 'load_product_seo_content' ], 6, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['geolocation'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_main', 'Dokan_Geolocation_Vendor_Dashboard', 'add_product_editor_options', 10 );
    }

    if( isset( $storekit_dk_product_form_sections['rma'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_inventory_variants', 'Dokan_RMA_Product', 'load_rma_content', 30, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['product-addon'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_main', 'Dokan_Product_Addon_Vendor_Product', 'add_addons_section', 15, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['wholesale'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_inventory_variants', 'Dokan_Wholesale_Vendor', 'load_wholesale_content', 30, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['order-min-max'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_inventory_variants', 'WeDevs\DokanPro\Modules\OrderMinMax\Vendor', 'load_min_max_meta_box', 31, 2 );
    }

    if( isset( $storekit_dk_product_form_sections['advertise'] ) ){
        dokan_remove_hook_for_anonymous_class( 'dokan_product_edit_after_options', 'WeDevs\DokanPro\Modules\ProductAdvertisement\Frontend\Product', 'render_advertise_product_section', 99, 1 );
    }    

}
add_action( 'wp_head', 'remove_edit_product_form_fields' );


/**
 * 
 * Limit vendor file upload size
 * 
 */
function storekit_vendor_file_upload_size( $size ){
    $storekit_dk_inputf_size = storekit_get_option( 'dk_vendor_upload_size', 'dokan', '1' );;

    if( is_user_logged_in() ){
        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;

        if( in_array( 'seller', $roles ) == true && isset( $storekit_dk_inputf_size ) ){
            $size = 1048576 * $storekit_dk_inputf_size;
        }
    }

    return $size;
    
}
add_action( 'upload_size_limit', 'storekit_vendor_file_upload_size' );

/**
 * 
 * Sort products by vendor on the cart
 * 
 */

function storekit_sort_cart_by_vendor_store_name( $cart ) {

    $storekit_dk_sort_product = storekit_get_option( 'dk_sort_product_by_vendor', 'dokan', 'none' );

    if( $storekit_dk_sort_product != 'none' ){

        $products_in_cart = array();
    
        foreach ( $cart->get_cart() as $key => $item ) {
            $vendor = dokan_get_vendor_by_product( $item['data']->get_id() );
            $products_in_cart[ $key ] = $vendor->get_shop_name();
        }
        
        if( $storekit_dk_sort_product == 'asc' ){
            asort( $products_in_cart );
        } elseif( $storekit_dk_sort_product == 'desc' ){
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

add_action( 'woocommerce_cart_loaded_from_session', 'storekit_sort_cart_by_vendor_store_name', 100 );

/**
 *   
 * Clear cart button to clear/empty cart 
 *
 */

function storekit_clear_cart_button(){
    $storekit_wc_clear_cart = storekit_get_option( 'wc_clear_cart', 'woocommerce', 'on' );

    if( $storekit_wc_clear_cart == 'on' ):
    ?>

    <button type="submit" class="button" name="clear_cart" value="<?php esc_attr_e( 'Clear cart', 'woocom-toolkit' ); ?>"><?php esc_html_e( 'Clear cart', 'woocom-toolkit' ); ?></button>

    <?php
    endif;
}
add_action( 'woocommerce_cart_actions', 'storekit_clear_cart_button' );


/**
 *   
 * Clear cart session
 *
 */
function storekit_clear_cart_session(){
    global $woocommerce;

    if( isset( $_REQUEST['clear_cart'] ) ){
        $woocommerce->cart->empty_cart(); 
    }
}
add_action( 'init', 'storekit_clear_cart_session' );