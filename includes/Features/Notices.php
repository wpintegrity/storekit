<?php
namespace WpIntegrity\StoreKit\Features;

/**
 * Notices Functions Manager Class.
 *
 * Handles admin notices related to plugin dependencies.
 */
class Notices {

    /**
     * Constructor function.
     *
     * Initializes actions to display notices.
     */
    public function __construct() {
        add_action( 'admin_notices', [ $this, 'woocommerce_not_active_notice' ] );
    }
    
    /**
     * Display an admin notice if WooCommerce is not installed or activated.
     *
     * @since 1.0.0
     */
    public function woocommerce_not_active_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            $admin_notice_content = '';

            // Check if WooCommerce is not installed or activated.
            if ( ! storekit()->has_woocommerce() ) {
                $install_url = wp_nonce_url(
                    add_query_arg( [ 'action' => 'install-plugin', 'plugin' => 'woocommerce' ], admin_url( 'update.php' ) ),
                    'install-plugin_woocommerce'
                );
                $admin_notice_content = sprintf(
                    // Translators: 1$s, 2$s are HTML tags, 3$s, 4$s are the link to WooCommerce plugin, 5$s, 6$s are the link to install WooCommerce.
                    esc_html__(
                        '%1$sStoreKit is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for StoreKit to work. Please %5$s install WooCommerce &raquo;%6$s',
                        'storekit'
                    ),
                    '<strong>',
                    '</strong>',
                    '<a href="https://wordpress.org/plugins/woocommerce/">',
                    '</a>',
                    '<a href="' . esc_url( $install_url ) . '">',
                    '</a>'
                );

                // If WooCommerce is installed, provide activation link.
                if ( storekit()->is_woocommerce_installed() ) {
                    $activate_url = wp_nonce_url(
                        add_query_arg( [ 'action' => 'activate', 'plugin' => urlencode( 'woocommerce/woocommerce.php' ) ], admin_url( 'plugins.php' ) ),
                        'activate-plugin_woocommerce/woocommerce.php'
                    );
                    $admin_notice_content = sprintf(
                        // Translators: 1$s and 2$s are opening and closing strong HTML tags, 3$s and 4$s are opening and closing anchor HTML tags to the WooCommerce plugin page, 5$s and 6$s are opening and closing anchor HTML tags to the activate WooCommerce URL.
                        esc_html__(
                            '%1$sStoreKit is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for StoreKit to work. Please %5$s activate WooCommerce &raquo;%6$s',
                            'storekit'
                        ),
                        '<strong>',
                        '</strong>',
                        '<a href="https://wordpress.org/plugins/woocommerce/">',
                        '</a>',
                        '<a href="' . esc_url( $activate_url ) . '">',
                        '</a>'
                    );
                }
            }

            // Display the admin notice if content is set.
            if ( $admin_notice_content ) {
                echo '<div class="error"><p>' . wp_kses_post( $admin_notice_content ) . '</p></div>';
            }
        }
    }
}