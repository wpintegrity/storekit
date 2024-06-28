<?php
namespace WpIntegrity\StoreKit;

class Options {
    
    /**
     * Get the value of a settings field
     *
     * @param string $option settings field name
     * @param string $section the section name this field belongs to
     * @param mixed $default default value if the option is not found
     *
     * @return mixed
     */
    public static function get_option( $option, $section, $default = '' ) {
        // Define the option names
        $options_map = [
            'woocommerce' => 'storekit_woocommerce_settings',
            'dokan'       => 'storekit_dokan_settings',
        ];

        // Get the serialized option value
        $serialized_options = get_option( $options_map[ $section ] );

        // Unserialize the option value
        $options = maybe_unserialize( $serialized_options );

        // Return the specific option value or default if not set
        return $options[$option] ?? $default;
    }

    /**
     * Get the template file from the templates directory.
     *
     * @param string $template_name The name of the template file.
     * @param array  $args          Optional. An array of arguments to pass to the template file.
     * @param string $template_path Optional. The path to the templates directory.
     * @param string $default_path  Optional. The default path to the templates directory.
     */
    public static function get_templates( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args );
        }

        $template_path  = $template_path ? $template_path : 'storekit/templates/';
        $default_path   = $default_path ? $default_path : STOREKIT_PATH . '/templates/';

        // Look within the passed path within the theme - this allows overriding.
        $template = locate_template( array( trailingslashit( $template_path ) . $template_name ) );

        // Get default template.
        if ( ! $template ) {
            $template = $default_path . $template_name;
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters( 'storekit_get_templates', $template, $template_name, $args, $template_path, $default_path );

        if ( file_exists( $template ) ) {
            include $template;
        } else {
            _doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'storekit' ), '<code>' . $template . '</code>' ), '1.0' );
        }
    }
}
