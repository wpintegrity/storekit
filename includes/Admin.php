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

        $storekit_icon_base64 = 'PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2MDcuNTggNTM3LjE2Ij48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6IzQxNDA0Mjt9PC9zdHlsZT48L2RlZnM+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNTAxLjI2LDM2My4zOWE1My44OCw1My44OCwwLDAsMC02Mi4zNi0xMC4yM2wtNzguNTEtNzguNTJWMjI5bC05Mi44Mi03MC42NEwyMjAuODQsMjA0LjhsNzAuMSw5My41M2g0NS4zMWw3Ny45Miw3OC40NWE1NC45MSw1NC45MSwwLDAsMCwxMC4xNSw2Mi44NGw4NS40NCw4Ni4xOGEyNy4xNSwyNy4xNSwwLDAsMCwzOC41LDBMNTg2Ljc4LDQ4N2EyNy42NiwyNy42NiwwLDAsMCwwLTM4LjhaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNDcyLjcyLDMzNC4yM2E3Ni40Niw3Ni40NiwwLDAsMSw1NC40NSwyMy4yOWwxNC4xOCwxNC41NWMyOS4zMy0xMi43OSw1Mi4zOS0zOC43NSw2MC4yNS03MC41OGExMDguMDcsMTA4LjA3LDAsMCwwLC42Mi01MC4zMywxMC41OCwxMC41OCwwLDAsMC0xNy43My01bC01MC43OCw1MC4yNS01NS4yLS41MS41LTU1LDUwLjc4LTUwLjI0YzUuODMtNS43NywzLjI3LTE2LTQuNjYtMThhMTA1LjYxLDEwNS42MSwwLDAsMC00OS45NS0uMzFjLTQwLjQ3LDkuMzctNzIuMjUsNDMuNjYtNzkuMDYsODQuOTRhOTcuNzksOTcuNzksMCwwLDAtMS4zNywxOGw1OS44OSw2MS41M0E1NSw1NSwwLDAsMSw0NzIuNzIsMzM0LjIzWiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTM5NS4zLDM4Mi4yOSwzNTIuMDcsMzM4LjgsMjQzLjM0LDQ0OC4zOGE0OS45Miw0OS45MiwwLDAsMCwwLDcwLjI0LDQ5LDQ5LDAsMCwwLDY5LjY4LDBMNDAwLDQzMUMzOTMuNTUsNDE1LjYzLDM5MS40MywzOTguNzcsMzk1LjMsMzgyLjI5Wk0yNzUuNjEsNTAzLjcyYTE3LjY3LDE3LjY3LDAsMSwxLDE3LjUzLTE3LjY2QTE3LjYsMTcuNiwwLDAsMSwyNzUuNjEsNTAzLjcyWiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTIzMi40NSw1MjEuNjhhNTMuMTgsNTMuMTgsMCwwLDEsMC03NC44bDQ1LjQ0LTQ1Ljc5SDEyMC40OFYyNTkuMjlBMTM0LjczLDEzNC43MywwLDAsMSw4MS40MSwyNjVhMTQwLjY4LDE0MC42OCwwLDAsMS0xNy44NC0xLjE3aC0uMTNhMTA0LjE2LDEwNC4xNiwwLDAsMS0xMy4xMi0yLjQ0djIwNy43YzAsMzcuNTMsMzEuNDIsNjgsNzAuMTYsNjhIMjY2LjlsLS40Ni0uMTFBNTIuMTEsNTIuMTEsMCwwLDEsMjMyLjQ1LDUyMS42OFoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0yMTEuMjgsMjAzLjhsNTQtNTMuNjFMMzcyLjQsMjMxLjc0VjI0Mi41YzEuMzYuMDUsMi43NC4wOSw0LjEyLjA5cTUsMCw5Ljg2LS40OGMxNS41MS00MS42NSw1MS4wOC03NC41MSw5NC42OS04NWExMzcsMTM3LDAsMCwxLDY0Ljc4LS4yMWMxMC4zMSwyLjQ5LDEzLjc1LDE1LjY4LDYuMjYsMjMuMjRsLTU4LDU4LjQ5YTEwMC4yMywxMDAuMjMsMCwwLDAsMjgsNCwxMDkuMDYsMTA5LjA2LDAsMCwwLDEzLjUxLS44NmM2Mi04LDkxLjM3LTc4LjUyLDU4LjItMTI5LjMxbC02NC05OC4yQTMxLjgsMzEuOCwwLDAsMCw1MDMuMDcsMEgxMDQuNTRBMzEuODIsMzEuODIsMCwwLDAsNzcuOCwxNC4yMmwtNjQsOTguMmMtMzMsNTAuNjgtMy44MSwxMjEuMjksNTgsMTI5LjMxYTExMi40NiwxMTIuNDYsMCwwLDAsMTMuNTMuODYsOTkuMzIsOTkuMzIsMCwwLDAsNzIuODctMzEuNCw5OS4yNSw5OS4yNSwwLDAsMCw3Mi43MiwzMS40YzMuMSwwLDYuMTUtLjE1LDkuMTctLjQzWiIvPjwvc3ZnPg==';

        $storekit_icon_data_uri = 'data:image/svg+xml;base64,' . $storekit_icon_base64;

        $hook = add_menu_page( __( 'StoreKit', 'storekit' ), __( 'StoreKit', 'storekit' ), $capability, $slug, [ $this, 'plugin_page' ], $storekit_icon_data_uri );

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
        $sections = [];

        $sections['woocommerce'] = [
          'id'    => 'woocommerce',
          'title' => __( 'WooCommerce Settings', 'storekit' )
        ];

        if( class_exists('WeDevs_Dokan') ){

          $sections['dokan'] = [
            'id'    => 'dokan',
            'title' => __( 'Dokan Settings', 'storekit' )
          ];

        }

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
                    'type'      => 'number',
                    'default'   => ''
                ],
                [
                    'name'      => 'wc_product_sold_individually',
                    'label'     => __( 'Enable Product Individual Sale', 'storekit' ),
                    'desc'      => __( 'Prevent customers from purchasing one product multiple times at a time', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'off'
                ],
                [
                    'name'      => 'wc_hide_free_shipping',
                    'label'     => __( 'Enable Hide Shipping Methods', 'storekit' ),
                    'desc'      => __( 'Hide other shipping methods when Free Shipping is available on the cart', 'storekit' ),
                    'type'      => 'checkbox',
                    'default'   => 'off'
                ]
            ],
            'dokan' => [
                [
                    'name'    => 'dk_vendor_upload_size',
                    'size'    => 'small',
                    'label'   => __( 'Limit File Upload Size', 'storekit' ),
                    'desc'    => __( 'Limit vendor from uploading file size', 'storekit' ),
                    'type'    => 'number',
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
                    'type'      => 'number',
                    'default'   => ''
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
