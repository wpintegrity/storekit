<?php
namespace StoreKit;

/**
 * Admin Pages Handler
 */
class Admin {

    private $settings_api;

    public function __construct() {

        $this->settings_api = new WDTH_Settings_API();

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_init', [ $this, 'admin_init' ] );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';
        $slug       = 'storekit';

        $hook = add_menu_page( __( 'StoreKit', 'storekit' ), __( 'StoreKit', 'storekit' ), $capability, $slug, [ $this, 'plugin_page' ], 'dashicons-editor-paste-word' );

        add_action( 'load-' . $hook, [ $this, 'init_hooks'] );
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'storekit-admin' );
        wp_enqueue_script( 'storekit-admin' );
    }

    public function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'woocommerce',
                'title' => __( 'WooCommerce Settings', 'storekit' )
            ),
            array(
                'id'    => 'dokan',
                'title' => __( 'Dokan Settings', 'storekit' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    public function get_settings_fields() {
        $settings_fields = [
            'woocommerce' => [
                [
                    'name'      => 'wc_product_video_checkbox',
                    'label'     => __( 'Enable Product Video', 'storekit' ),
                    'desc'      => __( 'Allow customers to see product featured video from the single product page', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_product_audio_checkbox',
                    'label'     => __( 'Enable Product Audio', 'storekit' ),
                    'desc'      => __( 'Allow customers to listen to sample audio from the single product page', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_new_customer_reg_email',
                    'label'     => __( 'Enable New Customer Registration Email', 'storekit' ),
                    'desc'      => __( 'Get new customers registration email to the admin email', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_clear_cart',
                    'label'     => __( 'Enable Clear Cart button', 'storekit' ),
                    'desc'      => __( 'Add a clear cart button on the cart page to empty the entire cart with one click', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_default_product_stock',
                    'size'      => 'small',
                    'label'     => __( 'Default product stock', 'storekit' ),
                    'desc'      => __( 'Insert default product stock amount', 'storekit' ),
                    'type'      => 'text',
                    'default'   => '0'
                ],
                [
                    'name'      => 'wc_product_sold_individually',
                    'label'     => __( 'Enable Product Individual Sale', 'storekit' ),
                    'desc'      => __( 'Prevent customers from purchasing one product multiple times at a time', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'off'
                ],
            ],
            'dokan' => [
                [
                    'name'    => 'dk_product_video_checkbox',
                    'label'   => __( 'Disable Product Video', 'storekit' ),
                    'desc'    => __( 'Disallow vendors from using the product video feature', 'storekit' ),
                    'type'    => 'checkbox',
                    'default' => 'off'
                ],
                [
                    'name'    => 'dk_product_audio_checkbox',
                    'label'   => __( 'Disable Product Audio', 'storekit' ),
                    'desc'    => __( 'Disallow vendors from using the product audio feature', 'storekit' ),
                    'type'    => 'checkbox',
                    'default' => 'off'
                ],
                [
                    'name'    => 'dk_vendor_upload_size',
                    'size'    => 'small',
                    'label'   => __( 'Limit File Upload Size', 'storekit' ),
                    'desc'    => __( 'Limit vendor from uploading file size', 'storekit' ),
                    'type'    => 'text',
                    'default' => '1'
                ],
                [
                    'name'    => 'dk_sort_product_by_vendor',
                    'label'   => __( 'Sort Product by Vendor', 'storekit' ),
                    'desc'    => __( 'Sort products by vendor name on the cart', 'storekit' ),
                    'type'    => 'select',
                    'options' => [
                        'none'  => __( 'None', 'storekit' ),
                        'asc'   => __( 'ASC', 'storekit' ),
                        'desc'  => __( 'DESC', 'storekit' ),
                    ],
                    'default' => 'asc'
                ],
                [
                    'name'    => 'dk_sold_by_label',
                    'label'   => __( 'Sold by label', 'storekit' ),
                    'desc'    => __( 'Display sold by label on the shop page', 'storekit' ),
                    'type'    => 'select',
                    'options' => [
                        'none'          => __( 'None', 'storekit' ),
                        'product-title' => __( 'After Product Title', 'storekit' ),
                        'product-price' => __( 'Before Add to Cart Button', 'storekit' ),
                        'add-to-cart'   => __( 'After Add to Cart Button', 'storekit' ),
                    ],
                    'default' => 'add-to-cart'
                ],
                [
                    'name'    => 'dk_vendor_dashboard_widgets',
                    'label'   => __( 'Hide Vendor Dashboard Widgets', 'storekit' ),
                    'desc'    => __( 'Hide Vendor Dashboard - Dashboard menu screen widgets', 'storekit' ),
                    'type'    => 'multicheck',
                    'options' => [
                        'big-counter'   => __( 'Big Counter Widget', 'storekit' ),
                        'orders'        => __( 'Orders Widget', 'storekit' ),
                        'products'      => __( 'Products Widget', 'storekit' ),
                        'reviews'       => __( 'Reviews Widget', 'storekit' ),
                        'sales-chart'   => __( 'Sales Report Chart Widget', 'storekit' ),
                        'announcement'  => __( 'Announcement Widget', 'storekit' )
                    ]
                ],
                [
                    'name'    => 'dk_vendor_dashboard_product_form',
                    'label'   => __( 'Hide Product Form Sections', 'storekit' ),
                    'desc'    => __( 'Hide Vendor Dashboard - Product Form sections', 'storekit' ),
                    'type'    => 'multicheck',
                    'options' => [
                        'download-virtual'  => __( 'Download/Virtual Checkboxes', 'storekit' ),
                        'inventory'         => __( 'Inventory', 'storekit' ),
                        'downloadable'      => __( 'Downloadable', 'storekit' ),
                        'other-options'     => __( 'Other Options', 'storekit' ),
                        'shipping-tax'      => __( 'Shipping & Tax', 'storekit' ),
                        'linked-products'   => __( 'Linked Products', 'storekit' ),
                        'attributes'        => __( 'Attributes & Variations', 'storekit' ),
                        'discount-options'  => __( 'Discount Options', 'storekit' ),
                        'yoast-seo'         => __( 'Products SEO (Yoast SEO)', 'storekit' ),
                        'rankmath-seo'      => __( 'Products SEO (Rank Math SEO)', 'storekit' ),
                        'geolocation'       => __( 'Geolocation', 'storekit' ),
                        'rma'               => __( 'RMA Options', 'storekit' ),
                        'product-addon'     => __( 'Add-ons', 'storekit' ),
                        'wholesale'         => __( 'Wholesale', 'storekit' ),
                        'order-min-max'     => __( 'Min/Max Options', 'storekit' ),
                        'advertise'         => __( 'Advertise Product', 'storekit' ),
                    ]
                ],
                [
                    'name'      => 'dk_default_product_stock',
                    'size'      => 'small',
                    'label'     => __( 'Default product stock', 'storekit' ),
                    'desc'      => __( 'Insert default product stock amount', 'storekit' ),
                    'type'      => 'text',
                    'default'   => '0'
                ],
                [
                    'name'      => 'dk_product_sold_individually',
                    'label'     => __( 'Enable Product Individual Sale', 'storekit' ),
                    'desc'      => __( 'Prevent customers from purchasing one product multiple times at a time', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'off'
                ],
            ]
        ];

        return $settings_fields;
    }


    /**
     * Render our admin page
     *
     * @return void
     */
    public function plugin_page() {

        echo '<div class="wrap">';

        ?>

        <h1 class="wp-heading-inline"><?php esc_html_e( 'StoreKit: A Helpfull Toolkit for WooCommerce', 'storekit' ) ?></h1>        

        <?php

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }
}
