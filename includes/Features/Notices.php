<?php

namespace WpIntegrity\StoreKit\Features;

/**
 * Notices functions Manager Class
 */
class Notices {

    /**
     * Constructor function
     */
    public function __construct() {
        add_action( 'admin_notices', [ $this, 'woocommerce_not_active_notice' ] );
    }
    
    /**
     * Display an admin notice if WooCommerce is not installed or activated
     *
     * @since 1.0
     */
    function woocommerce_not_active_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            $admin_notice_content = '';

            if ( !storekit()->has_woocommerce() ) {
                $install_url = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => 'woocommerce'), admin_url('update.php')), 'install-plugin_woocommerce');
                $admin_notice_content = sprintf(esc_html__('%1$sStoreKit is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for StoreKit to work. Please %5$s install WooCommerce &raquo;%6$s', 'storekit'), '<strong>', '</strong>', '<a href="https://wordpress.org/plugins/woocommerce/">', '</a>', '<a href="' . esc_url($install_url) . '">', '</a>');

                if (storekit()->is_woocommerce_installed()) {
                    $install_url = wp_nonce_url(add_query_arg(array('action' => 'activate', 'plugin' => urlencode('woocommerce/woocommerce.php')), admin_url('plugins.php')), 'activate-plugin_woocommerce/woocommerce.php');
                    $admin_notice_content = sprintf(esc_html__('%1$sStoreKit is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for StoreKit to work. Please %5$s activate WooCommerce &raquo;%6$s', 'storekit'), '<strong>', '</strong>', '<a href="https://wordpress.org/plugins/woocommerce/">', '</a>', '<a href="' . esc_url($install_url) . '">', '</a>');
                }
            }

            if ( $admin_notice_content ) {
                echo '<div class="error"><p>' . wp_kses_post( $admin_notice_content ) . '</p></div>';
            }
        }
    }
}