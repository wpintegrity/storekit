<?php
namespace WpIntegrity\StoreKit\Api;

/**
 * Base Settings API Class
 *
 * This abstract class provides methods to retrieve and update settings.
 * 
 * @since 2.0.0
 */
abstract class Settings extends Base {
    /**
     * Option key where settings are stored.
     *
     * @var string
     * @since 2.0.0
     */
    protected $options_key;

    /**
     * Retrieves settings from the database.
     *
     * @param array $default_settings Default settings to return if option is not set.
     * 
     * @since 2.0.0
     * @return array Current settings.
     */
    public function get_settings( $default_settings ) {
        return get_option( $this->options_key, $default_settings );
    }

    /**
     * Updates settings in the database.
     *
     * @param array $params Parameters to update.
     * @param array $default_settings Default settings to use as base.
     * 
     * @since 2.0.0
     * @return array Confirmation message of successful update.
     */
    public function update_settings( $params, $default_settings ) {
        // Retrieve current settings or use default if not set.
        $current_settings = get_option( $this->options_key, $default_settings );

        // Merge current settings with updated parameters, filtering out null values.
        $updated_settings = array_merge( $current_settings, array_filter( $params, function( $value ) {
            return ! is_null( $value );
        } ) );

        // Update settings in the database.
        update_option( $this->options_key, $updated_settings );

        // Return confirmation message.
        return [ 'message' => 'Settings updated successfully' ];
    }
}
