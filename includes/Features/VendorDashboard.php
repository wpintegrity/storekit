<?php

namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;

/**
 * Dokan Vendor Dashboard Manager Class
 */
class VendorDashboard {

    /**
     * Constructor function
     */
    public function __construct() {
        add_action( 'wp_head', [ $this, 'remove_vendor_dashboard_widgets' ] );
        add_action( 'wp_head', [ $this, 'remove_edit_product_form_fields' ] );
    }

    /**
     * Remove vendor dashboard widgets based on options
     */
    public function remove_vendor_dashboard_widgets() {
        if( ! storekit()->has_dokan() ){
            return;
        }

        $options = Options::get_option( 'hide_vendor_dashboard_widgets', 'dokan', '' );
        
        $hooks = [
            'big_counter'        => [ 'dokan_dashboard_left_widgets', dokan()->dashboard->templates->dashboard, 'get_big_counter_widgets', 10 ],
            'orders'             => [ 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_orders_widgets', 15 ],
            'products'           => [ 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_products_widgets', 20 ],
            'sales_report_chart' => [ 'dokan_dashboard_right_widgets', dokan()->dashboard->templates->dashboard, 'get_sales_report_chart_widget', 10 ]
        ];

        if ( is_plugin_active('dokan-pro/dokan-pro.php') ) {
            $pro_hooks = [
                'reviews'      => [ 'dokan_dashboard_left_widgets', 'WeDevs\DokanPro\Dashboard\Dashboard', 'get_review_widget', 16 ],
                'announcement' => [ 'dokan_dashboard_right_widgets', 'WeDevs\DokanPro\Dashboard\Dashboard', 'get_announcement_widget', 12 ]
            ];

            $hooks = array_merge( $hooks, $pro_hooks );
        }

        $use_remove_action = [ 'big_counter', 'sales_report_chart' ];

        foreach ($hooks as $key => $hook) {
            if ( isset($options[$key]) && $options[$key] === true ) {
                if ( in_array($key, $use_remove_action) ) {
                    remove_action($hook[0], [ $hook[1], $hook[2] ], $hook[3]);
                } else {
                    dokan_remove_hook_for_anonymous_class(...$hook);
                }
            }
        }
    }

    /**
     * Remove fields from the edit product form based on options
     */
    public function remove_edit_product_form_fields() {
        if( ! storekit()->has_dokan() ){
            return;
        }

        $sections = Options::get_option( 'hide_product_form_sections', 'dokan', '' );

        $hooks = [
            'download_virtual' => ['dokan_product_edit_after_title','WeDevs\Dokan\Dashboard\Templates\Products', 'load_download_virtual_template', 10, 2],
            'inventory'        => ['dokan_product_edit_after_main', 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_inventory_template', 5, 2],
            'downloadable'     => ['dokan_product_edit_after_main', 'WeDevs\Dokan\Dashboard\Templates\Products', 'load_downloadable_template', 10, 2]
        ];

        $use_remove_action = [ 'download_virtual', 'inventory', 'downloadable' ];

        // Hooks available only in Dokan Pro and Merge the Pro hooks if Dokan Pro is active
        if ( is_plugin_active('dokan-pro/dokan-pro.php') ) {
            $pro_hooks = [
                'shipping_tax'          => ['dokan_product_edit_after_inventory_variants', dokan_pro()->products, 'load_shipping_tax_content', 10, 2],
                'linked_products'       => ['dokan_product_edit_after_inventory_variants', dokan_pro()->products, 'load_linked_product_content', 15, 2],
                'attributes'            => ['dokan_product_edit_after_inventory_variants', dokan_pro()->products, 'load_variations_content', 20, 2],
                'discount_options'      => ['dokan_product_edit_after_inventory_variants', dokan_pro()->vendor_discount->frontend_dashboard, 'load_discount_content', 25, 2],
                'products_seo_yoast'    => ['dokan_product_edit_after_inventory_variants', dokan_pro()->product_seo, 'load_product_seo_content', 5, 2],
                'products_seo_rankmath' => ['dokan_product_edit_after_inventory_variants', dokan_pro()->module->rank_math, 'load_product_seo_content', 6, 2],
                'geolocation'           => ['dokan_product_edit_after_main', 'Dokan_Geolocation_Vendor_Dashboard', 'add_product_editor_options', 10],
                'rma_options'           => ['dokan_product_edit_after_inventory_variants', 'Dokan_RMA_Product', 'load_rma_content', 30, 2],
                'product_addons'        => ['dokan_product_edit_after_main', 'Dokan_Product_Addon_Vendor_Product', 'add_addons_section', 15, 2],
                'wholesale'             => ['dokan_product_edit_after_inventory_variants', 'Dokan_Wholesale_Vendor', 'load_wholesale_content', 30, 2],
                'order_minmax'          => ['dokan_product_edit_after_inventory_variants', 'WeDevs\DokanPro\Modules\OrderMinMax\Vendor', 'load_min_max_meta_box', 31, 2],
                'advertise'             => ['dokan_product_edit_after_options', 'WeDevs\DokanPro\Modules\ProductAdvertisement\Frontend\Product', 'render_advertise_product_section', 99, 1],
            ];

            $pro_key = [ 'shipping_tax', 'linked_products', 'attributes', 'discount_options', 'products_seo_yoast', 'products_seo_rankmath' ];

            $use_remove_action = array_merge( $use_remove_action, $pro_key );
            $hooks = array_merge($hooks, $pro_hooks);
        }

        foreach ($hooks as $key => $hook) {
            if ( isset($sections[$key]) && $sections[$key] === true ) {
                if ( in_array($key, $use_remove_action) ) {
                    remove_action($hook[0], [ $hook[1], $hook[2] ], $hook[3], $hook[4]);
                } else {
                    dokan_remove_hook_for_anonymous_class(...$hook);
                }
            }
        }
    }
}
