<?php
namespace WpIntegrity\StoreKit\Api;

use WP_REST_Server;
use WP_REST_Response;
use WP_REST_Request;

/**
 * WooOptions Class
 *
 * Handles the WooCommerce settings via REST API.
 * 
 * @since 2.0.0
 */
class WooOptions extends Settings {
    /**
     * Class constructor.
     * 
     * @since 2.0.0
     */
    public function __construct() {
        $this->namespace = 'storekit/v1';
        $this->rest_base = 'woocommerce-settings';
        $this->options_key = 'storekit_woocommerce_settings';
    }

    /**
     * Register WooCommerce REST API routes.
     *
     * @since 2.0.0
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'admin_permission_check' ],
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_items' ],
                    'permission_callback' => [ $this, 'admin_permission_check' ],
                ],
                'schema' => [ $this, 'get_item_schema' ]
            ]
        );
    }

    /**
     * Get WooCommerce settings.
     *
     * @param WP_REST_Request $request Full data about the request.
     * 
     * @since 2.0.0
     * @return WP_REST_Response Settings data.
     * 
     */
    public function get_items( $request ) {
        $default_settings = [
            'new_customer_registration_email' => '',
            'clear_cart_button'               => '',
            'default_product_stock'           => '',
            'product_individual_sale'         => 'no',
            'hide_shipping_methods'           => '',
            'terms_conditions'                => '',
            'terms_conditions_page_id'        => '',
            'external_product_new_tab'        => '',
            'manage_profile_avatar'           => '',
            'my_account_admin_menu'           => true,
        ];

        $settings = $this->get_settings( $default_settings );
        $data = [];

        foreach ( $settings as $key => $value ) {
            $data[] = $this->prepare_item_for_response( [ $key => $value ], $request );
        }

        return new WP_REST_Response( $this->prepare_response_for_collection( $data ), 200 );
    }

    /**
     * Update WooCommerce settings.
     *
     * @param WP_REST_Request $request Full data about the request.
     * 
     * @since 2.0.0
     * @return WP_REST_Response Confirmation message.
     * 
     */
    public function update_items( $request ) {
        $params = $request->get_json_params();
        $default_settings = [
            'new_customer_registration_email' => '',
            'clear_cart_button'               => '',
            'default_product_stock'           => '',
            'product_individual_sale'         => 'no',
            'hide_shipping_methods'           => '',
            'terms_conditions'                => '',
            'terms_conditions_page_id'        => '',
            'external_product_new_tab'        => '',
            'manage_profile_avatar'           => '',
            'my_account_admin_menu'           => true,
        ];

        $response = $this->update_settings( $params, $default_settings );
        return new WP_REST_Response( $response, 200 );
    }

    /**
     * Prepare a single item for response.
     *
     * @param array           $item    Raw item data.
     * @param WP_REST_Request $request Full data about the request.
     * 
     * @since 2.0.0
     * @return array Prepared item data.
     * 
     */
    public function prepare_item_for_response( $item, $request ) {
        return [
            'key'   => key( $item ),
            'value' => current( $item ),
        ];
    }

    /**
     * Prepare the response for a collection of items.
     *
     * @param array $response Response data.
     * 
     * @since 2.0.0
     * @return array Prepared response data.
     * 
     */
    public function prepare_response_for_collection( $response ) {
        return [
            'woocommerce_settings' => $response,
            'count'                => count( $response ),
        ];
    }

    /**
     * Get the schema for the settings.
     *
     * @since 2.0.0
     * @return array Schema data.
     * 
     */
    public function get_item_schema() {
        if ( $this->schema ) {
            return $this->schema;
        }

        $this->schema = [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'woocommerce-settings',
            'type'       => 'object',
            'properties' => [
                'new_customer_registration_email' => [
                    'description' => esc_html__( 'New customer registration email setting.', 'storekit' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ],
                'clear_cart_button' => [
                    'description' => esc_html__( 'Clear cart button setting.', 'storekit' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ],
                'default_product_stock' => [
                    'description' => esc_html__( 'Default product stock setting.', 'storekit' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                ],
                'product_individual_sale' => [
                    'description' => esc_html__( 'Product individual sale setting.', 'storekit' ),
                    'type'        => 'string',
                    'context'     => [ 'view', 'edit' ],
                ],
                'hide_shipping_methods' => [
                    'description' => esc_html__( 'Hide shipping methods setting.', 'storekit' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ],
                'terms_conditions' => [
                    'description' => esc_html__( 'Terms and conditions setting.', 'storekit' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ],
                'terms_conditions_page_id' => [
                    'description' => esc_html__( 'Terms and conditions page ID setting.', 'storekit' ),
                    'type'        => 'integer',
                    'context'     => [ 'view', 'edit' ],
                ],
                'external_product_new_tab' => [
                    'description' => esc_html__( 'External Product New Tab setting.', 'storekit' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ],
                'manage_profile_avatar' => [
                    'description' => esc_html__( 'Profile Picture setting.', 'storekit' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ],
                'my_account_admin_menu' => [
                    'description' => esc_html__( 'My Account Menu setting.', 'storekit' ),
                    'type'        => 'boolean',
                    'context'     => [ 'view', 'edit' ],
                ],
            ],
        ];

        return $this->schema;
    }
}
