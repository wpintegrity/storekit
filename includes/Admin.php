<?php
namespace WooComToolkit;

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
        $slug       = 'woocom-toolkit';

        $hook = add_menu_page( __( 'WooCom Toolkit', 'textdomain' ), __( 'WooCom Toolkit', 'textdomain' ), $capability, $slug, [ $this, 'plugin_page' ], 'dashicons-text' );

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
        wp_enqueue_style( 'woocom-toolkit-admin' );
        wp_enqueue_script( 'woocom-toolkit-admin' );
    }

    public function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'woocommerce',
                'title' => __( 'WooCommerce Settings', 'woocom-toolkit' )
            ),
            array(
                'id'    => 'dokan',
                'title' => __( 'Dokan Settings', 'woocom-toolkit' )
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
                    'label'     => __( 'Enable Product Video', 'woocom-toolkit' ),
                    'desc'      => __( 'This option enables video adding capability in product edit form', 'woocom-toolkit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_product_audio_checkbox',
                    'label'     => __( 'Enable Product Audio', 'woocom-toolkit' ),
                    'desc'      => __( 'This option enables audio adding capability in product edit form', 'woocom-toolkit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
                [
                    'name'      => 'wc_new_customer_reg_email',
                    'label'     => __( 'Enable New Customer Registration Email', 'woocom-toolkit' ),
                    'desc'      => __( 'It will enables the New Customer Registration Email functionality', 'woocom-toolkit' ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ],
            ],
            'dokan' => [
                [
                    'name'    => 'dk_product_video_checkbox',
                    'label'   => __( 'Disable Product Video', 'woocom-toolkit' ),
                    'desc'    => __( 'Disallow vendors from using the product video feature', 'woocom-toolkit' ),
                    'type'    => 'checkbox',
                    'default' => ''
                ],
                [
                    'name'    => 'dk_product_audio_checkbox',
                    'label'   => __( 'Disable Product Audio', 'woocom-toolkit' ),
                    'desc'    => __( 'Disallow vendors from using the product audio feature', 'woocom-toolkit' ),
                    'type'    => 'checkbox',
                    'default' => ''
                ],
                [
                    'name'    => 'dk_vendor_upload_size',
                    'size'    => 'small',
                    'label'   => __( 'Limit File Upload Size', 'woocom-toolkit' ),
                    'desc'    => __( 'Limit vendor from uploading file size', 'woocom-toolkit' ),
                    'type'    => 'text',
                    'default' => '1'
                ],
                [
                    'name'    => 'dk_vendor_dashboard_widgets',
                    'label'   => __( 'Hide Vendor Dashboard Widgets', 'woocom-toolkit' ),
                    'desc'    => __( 'Hide Vendor Dashboard - Dashboard menu screen widgets', 'woocom-toolkit' ),
                    'type'    => 'multicheck',
                    'options' => [
                        'big-counter'   => __( 'Big Counter Widget', 'woocom-toolkit' ),
                        'orders'        => __( 'Orders Widget', 'woocom-toolkit' ),
                        'products'      => __( 'Products Widget', 'woocom-toolkit' ),
                        'reviews'        => __( 'Reviews Widget', 'woocom-toolkit' ),
                        'sales-chart'   => __( 'Sales Report Chart Widget', 'woocom-toolkit' ),
                        'announcement'  => __( 'Announcement Widget', 'woocom-toolkit' )
                    ]
                ],
                [
                    'name'    => 'dk_vendor_dashboard_product_form',
                    'label'   => __( 'Hide Product Form Sections', 'woocom-toolkit' ),
                    'desc'    => __( 'Hide Vendor Dashboard - Product Form sections', 'woocom-toolkit' ),
                    'type'    => 'multicheck',
                    'options' => [
                        'download-virtual'  => __( 'Download/Virtual Checkboxes', 'woocom-toolkit' ),
                        'inventory'         => __( 'Inventory', 'woocom-toolkit' ),
                        'downloadable'      => __( 'Downloadable', 'woocom-toolkit' ),
                        'other-options'     => __( 'Other Options', 'woocom-toolkit' ),
                        'shipping-tax'      => __( 'Shipping & Tax', 'woocom-toolkit' ),
                        'linked-products'   => __( 'Linked Products', 'woocom-toolkit' ),
                        'attributes'        => __( 'Attributes & Variations', 'woocom-toolkit' ),
                        'discount-options'  => __( 'Discount Options', 'woocom-toolkit' ),
                        'yoast-seo'         => __( 'Products SEO (Yoast SEO)', 'woocom-toolkit' ),
                        'rankmath-seo'      => __( 'Products SEO (Rank Math SEO)', 'woocom-toolkit' ),
                        'geolocation'       => __( 'Geolocation', 'woocom-toolkit' ),
                        'rma'               => __( 'RMA Options', 'woocom-toolkit' ),
                        'product-addon'     => __( 'Add-ons', 'woocom-toolkit' ),
                        'wholesale'         => __( 'Wholesale', 'woocom-toolkit' ),
                        'order-min-max'     => __( 'Min/Max Options', 'woocom-toolkit' ),
                        'advertise'         => __( 'Advertise Product', 'woocom-toolkit' ),
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

        <h1 class="wp-heading-inline"><?php esc_html_e( 'WooCom Toolkit: A Helpfull WooCommerce Toolkit', 'woocom-toolkit' ) ?></h1>        

        <?php

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }
}
