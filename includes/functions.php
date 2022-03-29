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
function wctk_get_option( $option, $section, $default = '' ) {

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

function wctk_product_settings_tabs( $tabs ){
 
	$tabs['misha'] = array(
		'label'    => __( 'WooCom Toolkit' ),
		'target'   => 'wctk_product_data',
		'priority' => 21,
	);
	return $tabs;
 
}
add_filter( 'woocommerce_product_data_tabs', 'wctk_product_settings_tabs' );

/** 
 *
 * Add input fields to the WooCom Toolkit Product Data Tab
 *  
 */
function wctk_product_data() {
    global $woocommerce, $post;

    echo '<div id="wctk_product_data" class="panel woocommerce_options_panel hidden">';

    $wctk_woocommerce_product_video = wctk_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );
    $wctk_woocommerce_product_audio = wctk_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );

    if( $wctk_woocommerce_product_video == 'on' ){

        echo '<div class="options_group wctk_product_video_url_field">';
    
        woocommerce_wp_text_input(
            array(
            'id'          => '_wctk_product_video_url',
            'label'       => __( 'Product Video URL', 'woocom-toolkit' ),
            'placeholder' => 'https://www.youtube.com/watch?v=tt2k8PGm-TI',
            'description' => __( 'Insert your YouTube Video URL', 'woocom-toolkit' ),
            'desc_tip'    => 'true'
            )
        );

        echo '</div>';

    }

    if( $wctk_woocommerce_product_audio == 'on' ){

        echo '<div class="options_group wctk_product_audio_url_field">';
        
            woocommerce_wp_text_input(
                array(
                'id'          => '_wctk_product_audio_title',
                'label'       => __( 'Product Audio Title', 'woocom-toolkit' ),
                'placeholder' => __( 'OneRepublic - Counting Stars', 'woocom-toolkit' ),
                'description' => __( 'Insert your Soundcloud audio/music title', 'woocom-toolkit' ),
                'desc_tip'    => 'true'
                )
            );

            woocommerce_wp_text_input(
                array(
                'id'          => '_wctk_product_audio_url',
                'label'       => __( 'Product Audio URL', 'woocom-toolkit' ),
                'placeholder' => 'https://soundcloud.com/interscope/onerepublic-counting-stars',
                'description' => __( 'Insert your Soundcloud audio/music URL', 'woocom-toolkit' ),
                'desc_tip'    => 'true'
                )
            );

        echo '</div>';

    }

    echo '</div>';

}
add_action( 'woocommerce_product_data_panels', 'wctk_product_data' );

/**
 * 
 * Save WooCom Toolkit Product Data Tab's fields' values
 * 
 */
function wctk_product_data_save( $post_id ) {
    $wctk_product_video_url_field = $_POST['_wctk_product_video_url'];
    $wctk_product_audio_title_field = $_POST['_wctk_product_audio_title'];
    $wctk_product_audio_url_field = $_POST['_wctk_product_audio_url'];

    if ( isset( $wctk_product_video_url_field ) ){
        update_post_meta( $post_id, '_wctk_product_video_url', wp_kses_post( $wctk_product_video_url_field ) );
    }

    if ( isset( $wctk_product_audio_title_field ) ){
        update_post_meta( $post_id, '_wctk_product_audio_title', esc_attr( $wctk_product_audio_title_field ) );
    }

    if ( isset( $wctk_product_audio_url_field ) ){
        update_post_meta( $post_id, '_wctk_product_audio_url', wp_kses_post( $wctk_product_audio_url_field ) );
    }

}
add_action( 'woocommerce_process_product_meta', 'wctk_product_data_save' );


/**
 * 
 * Add input fields to the Dokan Vendor Dashboard - edit product form
 * 
 */
function wctk_dk_product_video_url_field( $post, $post_id ){
    $wctk_woocommerce_product_video = wctk_get_option( 'wc_product_video_checkbox', 'woocommerce', 'on' );
    $wctk_dokan_product_video = wctk_get_option( 'dk_product_video_checkbox', 'dokan', 'off' );

    $wctk_woocommerce_product_video = wctk_get_option( 'wc_product_audio_checkbox', 'woocommerce', 'on' );
    $wctk_dokan_product_video = wctk_get_option( 'dk_product_audio_checkbox', 'dokan', 'off' );

    if( $wctk_woocommerce_product_video == 'on' && $wctk_dokan_product_video == 'off' ):
    ?>

    <div class="dokan-form-group">
    <label for="_wctk_product_video_url" class="form-label"><?php esc_html_e( 'Product Video URL', 'dokan-lite' ) ?></label>    

    <?php
        dokan_post_input_box( 
            $post_id,
            '_wctk_product_video_url', 
            [
                'placeholder'   => 'https://www.youtube.com/watch?v=tt2k8PGm-TI'
            ]
        );
    ?>

    </div>
    <?php endif;

    if( $wctk_woocommerce_product_video == 'on' && $wctk_dokan_product_video == 'off' ):
    ?>

    <div class="dokan-form-group">
    <label for="_wctk_product_audio_title" class="form-label"><?php esc_html_e( 'Product Audio Title', 'dokan-lite' ) ?></label>    
    <?php
        dokan_post_input_box( 
            $post_id,
            '_wctk_product_audio_title', 
            [
                'placeholder'   =>  __( 'OneRepublic - Counting Stars', 'woocom-toolkit' )
            ]
        );
    ?>

    <label for="_wctk_product_audio_url" class="form-label"><?php esc_html_e( 'Product Audio URL', 'dokan-lite' ) ?></label>    
    <?php
        dokan_post_input_box( 
            $post_id,
            '_wctk_product_audio_url', 
            [
                'placeholder'   => 'https://soundcloud.com/interscope/onerepublic-counting-stars'
            ]
        );
    ?>

    </div>
    <?php endif;
}
add_action( 'dokan_product_edit_after_product_tags', 'wctk_dk_product_video_url_field', 10, 2 );

/**
 * 
 * Save Dokan Vendor Dashboard - edit product form's fields' values
 * 
 */
function wctk_dk_product_video_url_field_save( $post_id, $data ){
    $wctk_dk_product_video_url_field = $data['_wctk_product_video_url'];
    $wctk_dk_product_audio_title_field = $data['_wctk_product_audio_title'];
    $wctk_dk_product_audio_url_field = $data['_wctk_product_audio_url'];

    if( isset( $wctk_dk_product_video_url_field ) ){
        update_post_meta( $post_id, '_wctk_product_video_url', wp_kses_post( $wctk_dk_product_video_url_field ) );
    }

    if( isset( $wctk_dk_product_audio_title_field ) ){
        update_post_meta( $post_id, '_wctk_product_audio_title', wp_kses_post( $wctk_dk_product_audio_title_field ) );
    }

    if( isset( $wctk_dk_product_audio_url_field ) ){
        update_post_meta( $post_id, '_wctk_product_audio_url', wp_kses_post( $wctk_dk_product_audio_url_field ) );
    }
}
add_action( 'dokan_product_updated', 'wctk_dk_product_video_url_field_save', 10, 2 );

function remove_vendor_dashboard_vendormenu_widgets(){ 
    $wctk_dk_remove_vendor_dashboard_widget_options = wctk_get_option( 'dk_vendor_dashboard_widgets', 'dokan', '' );
    
    if( $wctk_dk_remove_vendor_dashboard_widget_options['big-counter'] ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_big_counter_widgets', 10 );
    }

    if( $wctk_dk_remove_vendor_dashboard_widget_options['orders'] ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_orders_widgets', 15 );
    }

    if( $wctk_dk_remove_vendor_dashboard_widget_options['products'] ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_products_widgets', 20 );
    }

    if( $wctk_dk_remove_vendor_dashboard_widget_options['reviews'] ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_left_widgets', 'WeDevs\DokanPro\Dashboard', 'get_review_widget', 16 );
    }

    if( $wctk_dk_remove_vendor_dashboard_widget_options['sales-chart'] ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_right_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_sales_report_chart_widget', 10 );
    }

    if( $wctk_dk_remove_vendor_dashboard_widget_options['announcement'] ){
        dokan_remove_hook_for_anonymous_class( 'dokan_dashboard_right_widgets', 'WeDevs\DokanPro\Dashboard', 'get_announcement_widget', 12 );
    }

}
add_action( 'wp_head', 'remove_vendor_dashboard_vendormenu_widgets' );