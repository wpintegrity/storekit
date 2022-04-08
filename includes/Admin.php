<?php
namespace WooKit;

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
        $slug       = 'wookit';

        $hook = add_menu_page( __( 'WooCommerce Kit', 'wookit' ), __( 'WooCommerce Kit', 'wookit' ), $capability, $slug, [ $this, 'plugin_page' ], 'dashicons-editor-paste-word' );

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
        wp_enqueue_style( 'wookit-admin' );
        wp_enqueue_script( 'wookit-admin' );
    }

    public function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'woocommerce',
                'title' => __( 'WooCommerce Settings', 'wookit' )
            ),
            array(
                'id'    => 'dokan',
                'title' => __( 'Dokan Settings', 'wookit' )
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
                    'label'     => __( 'Enable Product Video', 'wookit' ),
                    'desc'      => __( 'This option enables video adding capability in product edit form', 'wookit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_product_audio_checkbox',
                    'label'     => __( 'Enable Product Audio', 'wookit' ),
                    'desc'      => __( 'This option enables audio adding capability in product edit form', 'wookit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_new_customer_reg_email',
                    'label'     => __( 'Enable New Customer Registration Email', 'wookit' ),
                    'desc'      => __( 'It will enables the New Customer Registration Email functionality', 'wookit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_clear_cart',
                    'label'     => __( 'Enable Clear Cart button', 'wookit' ),
                    'desc'      => __( 'Add a clear cart button on the cart page to clear cart by one click', 'wookit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
            ],
            'dokan' => [
                [
                    'name'    => 'dk_product_video_checkbox',
                    'label'   => __( 'Disable Product Video', 'wookit' ),
                    'desc'    => __( 'Disallow vendors from using the product video feature', 'wookit' ),
                    'type'    => 'checkbox',
                    'default' => ''
                ],
                [
                    'name'    => 'dk_product_audio_checkbox',
                    'label'   => __( 'Disable Product Audio', 'wookit' ),
                    'desc'    => __( 'Disallow vendors from using the product audio feature', 'wookit' ),
                    'type'    => 'checkbox',
                    'default' => ''
                ],
                [
                    'name'    => 'dk_vendor_upload_size',
                    'size'    => 'small',
                    'label'   => __( 'Limit File Upload Size', 'wookit' ),
                    'desc'    => __( 'Limit vendor from uploading file size', 'wookit' ),
                    'type'    => 'text',
                    'default' => '1'
                ],
                [
                    'name'    => 'dk_sort_product_by_vendor',
                    'label'   => __( 'Sort Product by Vendor', 'wookit' ),
                    'desc'    => __( 'Sort products by vendor name on the cart', 'wookit' ),
                    'type'    => 'select',
                    'options' => [
                        'none'  => __( 'None', 'wookit' ),
                        'asc'   => __( 'ASC', 'wookit' ),
                        'desc'  => __( 'DESC', 'wookit' ),
                    ],
                    'default' => 'asc'
                ],
                [
                    'name'    => 'dk_sold_by_label',
                    'label'   => __( 'Sold by label', 'wookit' ),
                    'desc'    => __( 'Display sold by label on the shop page', 'wookit' ),
                    'type'    => 'select',
                    'options' => [
                        'none'          => __( 'None', 'wookit' ),
                        'product-title' => __( 'After Product Title', 'wookit' ),
                        'product-price' => __( 'Before Add to Cart Button', 'wookit' ),
                        'add-to-cart'   => __( 'After Add to Cart Button', 'wookit' ),
                    ],
                    'default' => 'add-to-cart'
                ],
                [
                    'name'    => 'dk_vendor_dashboard_widgets',
                    'label'   => __( 'Hide Vendor Dashboard Widgets', 'wookit' ),
                    'desc'    => __( 'Hide Vendor Dashboard - Dashboard menu screen widgets', 'wookit' ),
                    'type'    => 'multicheck',
                    'options' => [
                        'big-counter'   => __( 'Big Counter Widget', 'wookit' ),
                        'orders'        => __( 'Orders Widget', 'wookit' ),
                        'products'      => __( 'Products Widget', 'wookit' ),
                        'reviews'       => __( 'Reviews Widget', 'wookit' ),
                        'sales-chart'   => __( 'Sales Report Chart Widget', 'wookit' ),
                        'announcement'  => __( 'Announcement Widget', 'wookit' )
                    ]
                ],
                [
                    'name'    => 'dk_vendor_dashboard_product_form',
                    'label'   => __( 'Hide Product Form Sections', 'wookit' ),
                    'desc'    => __( 'Hide Vendor Dashboard - Product Form sections', 'wookit' ),
                    'type'    => 'multicheck',
                    'options' => [
                        'download-virtual'  => __( 'Download/Virtual Checkboxes', 'wookit' ),
                        'inventory'         => __( 'Inventory', 'wookit' ),
                        'downloadable'      => __( 'Downloadable', 'wookit' ),
                        'other-options'     => __( 'Other Options', 'wookit' ),
                        'shipping-tax'      => __( 'Shipping & Tax', 'wookit' ),
                        'linked-products'   => __( 'Linked Products', 'wookit' ),
                        'attributes'        => __( 'Attributes & Variations', 'wookit' ),
                        'discount-options'  => __( 'Discount Options', 'wookit' ),
                        'yoast-seo'         => __( 'Products SEO (Yoast SEO)', 'wookit' ),
                        'rankmath-seo'      => __( 'Products SEO (Rank Math SEO)', 'wookit' ),
                        'geolocation'       => __( 'Geolocation', 'wookit' ),
                        'rma'               => __( 'RMA Options', 'wookit' ),
                        'product-addon'     => __( 'Add-ons', 'wookit' ),
                        'wholesale'         => __( 'Wholesale', 'wookit' ),
                        'order-min-max'     => __( 'Min/Max Options', 'wookit' ),
                        'advertise'         => __( 'Advertise Product', 'wookit' ),
                    ]
                ]
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

        <h1 class="wp-heading-inline"><?php esc_html_e( 'WooCommerce Kit: A Helpfull Toolkit for WooCommerce', 'wookit' ) ?></h1>        

        <?php

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }
}
