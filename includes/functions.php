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

    <button type="submit" class="button" name="clear_cart" value="<?php esc_attr_e( 'Clear cart', 'storekit' ); ?>"><?php esc_html_e( 'Clear cart', 'storekit' ); ?></button>

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
add_action( 'wp_head', 'storekit_clear_cart_session' );

/**
 * 
 * Default Product Stock
 * 
 */
function default_product_stock( $post_id ){
    $product_stock = storekit_get_option( 'wc_default_product_stock', 'woocommerce', '' );

    if( $product_stock > 0 ){
        update_post_meta( $post_id, '_manage_stock', 'yes' );
        update_post_meta( $post_id, '_stock', $product_stock );
    }

}
add_action( 'save_post_product', 'default_product_stock' );

/**
 * 
 * Default Product Stock for Dokan Vendors
 * 
 */
function default_product_stock_for_vendors( $post_id ){
    $dk_product_stock = storekit_get_option( 'dk_default_product_stock', 'dokan', '' );

    if( $dk_product_stock > 0 ){
        update_post_meta( $post_id, '_manage_stock', 'yes' );
        update_post_meta( $post_id, '_stock', $dk_product_stock );
    }

}
add_action( 'dokan_new_product_added', 'default_product_stock_for_vendors' );

/**
 * 
 * Product Sold Individually
 * 
 */
function storekit_product_sold_individually( $individually, $product ){
    $wc_sold_individually = storekit_get_option( 'wc_product_sold_individually', 'woocommerce', 'off' );
    $dk_sold_individually = storekit_get_option( 'dk_product_sold_individually', 'dokan', 'off' );

    $vendor = dokan_get_vendor_by_product( $product->get_id() );
    $user = get_userdata( $vendor->id );
    $user_roles = $user->roles;
    
    if( in_array( 'seller', $user_roles ) && $dk_sold_individually == 'on' ){    
        $individually = true;
    } elseif( in_array( 'administrator', $user_roles ) && $wc_sold_individually == 'on' ){
        $individually = true;
    }
    
    return $individually;
}
add_filter( 'woocommerce_is_sold_individually', 'storekit_product_sold_individually', 10, 2 );  

/**
 * 
 * Hide shipping methods when free shipping is available
 * 
 */
function hide_shipping_when_free_is_available( $rates ) {

    $wc_hide_shipping = storekit_get_option( 'wc_hide_free_shipping', 'woocommerce', 'off' );

    if( $wc_hide_shipping == 'on' ){
        $free = array();

        foreach ( $rates as $rate_id => $rate ) {
            if ( 'free_shipping' === $rate->method_id || strpos( $rate->id, 'free_shipping' ) !== false ) {
                $free[ $rate_id ] = $rate;
                break;
            }
        }
    }
    
	return ! empty( $free ) ? $free : $rates;
    
}
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 100 );

/**
 * Handle when WooCommerce is not installed or activated
 *
 * @since 1.0.0
 */
function woocommerce_not_active_notice(){
    if ( current_user_can( 'activate_plugins' ) ) {
        $has_woocommerce = class_exists( 'WooCommerce' );
        $woocommerce_installed = in_array( 'woocommerce/woocommerce.php', array_keys( get_plugins() ), true );

        $admin_notice_content = '';

        if ( ! $has_woocommerce ){
            $install_url  = wp_nonce_url( add_query_arg( array( 'action' => 'install-plugin', 'plugin' => 'woocommerce' ), admin_url( 'update.php' ) ), 'install-plugin_woocommerce' );
            // translators: 1$-2$: opening and closing <strong> tags, 3$-4$: link tags, takes to woocommerce plugin on wp.org, 5$-6$: opening and closing link tags, leads to plugins.php in admin
            $admin_notice_content         = sprintf( esc_html__( '%1$sStoreKit is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for StoreKit to work. Please %5$s install WooCommerce &raquo;%6$s',  'storekit' ), '<strong>', '</strong>', '<a href="https://wordpress.org/plugins/woocommerce/">', '</a>', '<a href="' .  esc_url( $install_url ) . '">', '</a>' );

                if ( $woocommerce_installed ) {
                    $install_url = wp_nonce_url( add_query_arg( array( 'action' => 'activate', 'plugin' => urlencode( 'woocommerce/woocommerce.php' ) ), admin_url( 'plugins.php' ) ), 'activate-plugin_woocommerce/woocommerce.php' );
                    // translators: 1$-2$: opening and closing <strong> tags, 3$-4$: link tags, takes to woocommerce plugin on wp.org, 5$-6$: opening and closing link tags, leads to plugins.php in admin
                    $admin_notice_content        = sprintf( esc_html__( '%1$sStoreKit is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for StoreKit to work. Please %5$s activate WooCommerce &raquo;%6$s',  'storekit' ), '<strong>', '</strong>', '<a href="https://wordpress.org/plugins/woocommerce/">', '</a>', '<a href="' .  esc_url( $install_url ) . '">', '</a>' );
                }
        }

        if ( $admin_notice_content ) {
            echo '<div class="error">';
            echo '<p>' . wp_kses_post( $admin_notice_content ) . '</p>';
            echo '</div>';
        }
    }
}
add_action( 'admin_notices', 'woocommerce_not_active_notice' );