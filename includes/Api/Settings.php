<?php
namespace WpIntegrity\StoreKit\Api;

/**
 * Base Settings API Class
 */
abstract class Settings extends Base {
    protected $options_key;

    /**
     * Retrieves settings
     */
    public function get_settings( $default_settings ) {
        return get_option( $this->options_key, $default_settings );
    }

    /**
     * Updates settings
     */
    public function update_settings( $params, $default_settings ) {
        $current_settings = get_option( $this->options_key, $default_settings );

        $updated_settings = array_merge( $current_settings, array_filter( $params, function( $value ) {
            return !is_null( $value );
        } ) );

        update_option( $this->options_key, $updated_settings );

        return [ 'message' => 'Settings updated successfully' ];
    }
}