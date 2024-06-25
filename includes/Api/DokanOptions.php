<?php
namespace WpIntegrity\StoreKit\Api;

use WP_REST_Server;
use WP_REST_Response;
use WP_REST_Request;

class DokanOptions extends Settings {
    /**
     * Class constructor
     */
    public function __construct() {
        $this->namespace = 'storekit/v1';
        $this->rest_base = 'dokan-settings';
        $this->options_key = 'storekit_dokan_settings';
    }

    /**
     * Register Dokan REST API route
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'               => WP_REST_Server::READABLE,
                    'callback'              => [ $this, 'get_items' ],
                    'permission_callback'   => [ $this, 'admin_permission_check' ],
                ],
                [
                    'methods'               => WP_REST_Server::EDITABLE,
                    'callback'              => [ $this, 'update_items' ],
                    'permission_callback'   => [ $this, 'admin_permission_check' ],
                ],
                'schema'    => [ $this, 'get_item_schema' ]
            ]
        );
    }
    
    public function get_items( $request ) {
        $default_settings = [
            'limit_file_upload_size'        => '',
            'sort_product_by_vendor'        => '',
            'sold_by_label'                 => '',
            'hide_vendor_dashboard_widgets' => [
                'big_counter'        => false,
                'orders'             => false,
                'products'           => false,
                'reviews'            => false,
                'sales_report_chart' => false,
                'announcement'       => false,
            ],
            'hide_product_form_sections'    => [
                'download_virtual'      => false,
                'inventory'             => false,
                'downloadable'          => false,
                'other'                 => false,
                'shipping_tax'          => false,
                'linked_products'       => false,
                'attributes'            => false,
                'discount'              => false,
                'products_seo_yoast'    => false,
                'products_seo_rankmath' => false,
                'geolocation'           => false,
                'rma_options'           => false,
                'addons'                => false,
                'wholesale'             => false,
                'mixmax'                => false,
                'advertise'             => false,
            ],
            'default_product_stock'         => '',
            'product_individual_sale'       => false
        ];

        $settings = $this->get_settings( $default_settings );
        $data = [];

        foreach ( $settings as $key => $value ) {
            $data[] = $this->prepare_item_for_response( [ $key => $value ], $request );
        }

        return new WP_REST_Response( $this->prepare_response_for_collection( $data ), 200 );
    }

    public function update_items( $request ) {
        $params = $request->get_json_params();
        $default_settings = [
            'limit_file_upload_size'        => '',
            'sort_product_by_vendor'        => '',
            'sold_by_label'                 => '',
            'hide_vendor_dashboard_widgets' => [
                'big_counter'        => false,
                'orders'             => false,
                'products'           => false,
                'reviews'            => false,
                'sales_report_chart' => false,
                'announcement'       => false,
            ],
            'hide_product_form_sections'    => [
                'download_virtual'      => false,
                'inventory'             => false,
                'downloadable'          => false,
                'other'                 => false,
                'shipping_tax'          => false,
                'linked_products'       => false,
                'attributes'            => false,
                'discount'              => false,
                'products_seo_yoast'    => false,
                'products_seo_rankmath' => false,
                'geolocation'           => false,
                'rma_options'           => false,
                'addons'                => false,
                'wholesale'             => false,
                'mixmax'                => false,
                'advertise'             => false,
            ],
            'default_product_stock'         => '',
            'product_individual_sale'       => false
        ];

        $response = $this->update_settings( $params, $default_settings );
        return new WP_REST_Response( $response, 200 );
    }

    /**
     * Prepare a single item for response
     *
     * @param array            $item    Raw item.
     * @param WP_REST_Request  $request Request object.
     *
     * @return array
     */
    public function prepare_item_for_response( $item, $request ) {
        return [
            'key'   => key($item),
            'value' => current($item)
        ];
    }

    /**
     * Prepare the response for a collection of items
     *
     * @param array $response Response data.
     *
     * @return array
     */
    public function prepare_response_for_collection( $response ) {
        return [
            'dokan_settings' => $response,
            'count'        => count( $response )
        ];
    }

    /**
     * Get the schema for the settings
     * 
     * @return array
     */
    public function get_item_schema() {
        if( $this->schema ) {
            return $this->schema;
        }

        $this->schema = [
            '$schema'       => 'http://json-schema.org/draft-04/schema#',
            'title'         => 'dokan-settings',
            'type'          => 'object',
            'properties'    => [
                'limit_file_upload_size' => [
                    'description' => esc_html__( 'Limit File Upload Size.' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                ],
                'sort_product_by_vendor' => [
                    'description' => esc_html__( 'Sort Product by Vendor.' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                ],
                'sold_by_label' => [
                    'description' => esc_html__( 'Sold by label.' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                ],
                'hide_vendor_dashboard_widgets' => [
                    'description' => esc_html__( 'Hide Vendor Dashboard Widgets.' ),
                    'type'        => 'object',
                    'context'     => [ 'view', 'edit' ],
                    'properties'  => [
                        'big_counter' => [
                            'description' => esc_html__( 'Big Counter.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'orders' => [
                            'description' => esc_html__( 'Orders.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'products' => [
                            'description' => esc_html__( 'Products.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'reviews' => [
                            'description' => esc_html__( 'Reviews.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'sales_report_chart' => [
                            'description' => esc_html__( 'Sales Report Chart.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'announcement' => [
                            'description' => esc_html__( 'Announcement.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ]
                    ]
                ],
                'hide_product_form_sections' => [
                    'description' => esc_html__( 'Hide Product Form Sections.' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                    'properties'  => [
                        'download_virtual' => [
                            'description' => esc_html__( 'Downloads & Virtual Checkboxes.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'inventory' => [
                            'description' => esc_html__( 'Inventory.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'downloadable' => [
                            'description' => esc_html__( 'Downloadable.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'other' => [
                            'description' => esc_html__( 'Other Options.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'shipping_tax' => [
                            'description' => esc_html__( 'Shipping & Tax.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'linked_products' => [
                            'description' => esc_html__( 'Linked Products.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'attributes' => [
                            'description' => esc_html__( 'Attributes & Variations' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'discount' => [
                            'description' => esc_html__( 'Discount Options.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'products_seo_yoast' => [
                            'description' => esc_html__( 'Product SEO (Yoast).' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'products_seo_rankmath' => [
                            'description' => esc_html__( 'Product SEO (RankMath).' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'geolocation' => [
                            'description' => esc_html__( 'Geolocation.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'rma_options' => [
                            'description' => esc_html__( 'RMA Options.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'addons' => [
                            'description' => esc_html__( 'Add-ons.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'wholesale' => [
                            'description' => esc_html__( 'Wholesale.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'min_max' => [
                            'description' => esc_html__( 'Min/Max Options.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ],
                        'advertise' => [
                            'description' => esc_html__( 'Advertise Product.' ),
                            'type'        => 'boolean',
                            'context'     => [ 'view', 'edit' ],
                        ]
                    ]
                ],
                'default_product_stock' => [
                    'description' => esc_html__( 'Default product stock.' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                ],
                'product_individual_sale' => [
                    'description' => esc_html__( 'Product Individual Sale.' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ]
            ]
        ];

        return $this->schema;
    }
}