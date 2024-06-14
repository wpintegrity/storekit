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
            'big-counter'  => [ 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_big_counter_widgets', 10 ],
            'orders'       => [ 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_orders_widgets', 15 ],
            'products'     => [ 'dokan_dashboard_left_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_products_widgets', 20 ],
            'reviews'      => [ 'dokan_dashboard_left_widgets', 'WeDevs\DokanPro\Dashboard\Dashboard', 'get_review_widget', 16 ],
            'sales-chart'  => [ 'dokan_dashboard_right_widgets', 'WeDevs\Dokan\Dashboard\Templates\Dashboard', 'get_sales_report_chart_widget', 10] ,
            'announcement' => [ 'dokan_dashboard_right_widgets', 'WeDevs\DokanPro\Dashboard\Dashboard', 'get_announcement_widget', 12 ]
        ];

        foreach ($hooks as $key => $hook) {
            if ( isset($options[$key]) && $options[$key] === true ) {
                dokan_remove_hook_for_anonymous_class(...$hook);
            }
        }
    }

    /**
     * Remove fields from the edit product form based on options
     */
    public static function remove_edit_product_form_fields() {
        if( ! storekit()->has_dokan() ){
            return;
        }

        $sections = Options::get_option( 'hide_product_form_sections', 'dokan', '' );

        $hooks = [
            'download-virtual' => ['dokan_product_edit_after_title', ['WeDevs\Dokan\Dashboard\Templates\Products', 'load_download_virtual_template'], 10, 2],
            'inventory' => ['dokan_product_edit_after_main', ['WeDevs\Dokan\Dashboard\Templates\Products', 'load_inventory_template'], 5, 2],
            'downloadable' => ['dokan_product_edit_after_main', ['WeDevs\Dokan\Dashboard\Templates\Products', 'load_downloadable_template'], 10, 2],
            'other-options' => ['dokan_product_edit_after_inventory_variants', ['WeDevs\Dokan\Dashboard\Templates\Products', 'load_others_template'], 85, 2],
            'shipping-tax' => ['dokan_product_edit_after_inventory_variants', [dokan_pro()->products, 'load_shipping_tax_content'], 10, 2],
            'linked-products' => ['dokan_product_edit_after_inventory_variants', [dokan_pro()->products, 'load_linked_product_content'], 15, 2],
            'attributes' => ['dokan_product_edit_after_inventory_variants', [dokan_pro()->products, 'load_variations_content'], 20, 2],
            'discount-options' => ['dokan_product_edit_after_inventory_variants', [dokan_pro()->products, 'load_lot_discount_content'], 25, 2],
            'yoast-seo' => ['dokan_product_edit_after_inventory_variants', [dokan_pro()->product_seo, 'load_product_seo_content'], 5, 2],
            'rankmath-seo' => ['dokan_product_edit_after_inventory_variants', [dokan_pro()->module->rank_math, 'load_product_seo_content'], 6, 2],
            'geolocation' => ['dokan_product_edit_after_main', 'Dokan_Geolocation_Vendor_Dashboard', 'add_product_editor_options', 10],
            'rma' => ['dokan_product_edit_after_inventory_variants', 'Dokan_RMA_Product', 'load_rma_content', 30, 2],
            'product-addon' => ['dokan_product_edit_after_main', 'Dokan_Product_Addon_Vendor_Product', 'add_addons_section', 15, 2],
            'wholesale' => ['dokan_product_edit_after_inventory_variants', 'Dokan_Wholesale_Vendor', 'load_wholesale_content', 30, 2],
            'order-min-max' => ['dokan_product_edit_after_inventory_variants', 'WeDevs\DokanPro\Modules\OrderMinMax\Vendor', 'load_min_max_meta_box', 31, 2],
            'advertise' => ['dokan_product_edit_after_options', 'WeDevs\DokanPro\Modules\ProductAdvertisement\Frontend\Product', 'render_advertise_product_section', 99, 1],
        ];

        foreach ( $hooks as $key => $hook ) {
            if ( isset($sections[$key]) && $sections[$key] === true ) {
                remove_action(...$hook);
            }
        }
    }
}
