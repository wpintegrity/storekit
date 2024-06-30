<?php
namespace WpIntegrity\StoreKit;

/**
 * Freemius SDK Integration for StoreKit plugin.
 */
class Tracker {

    /**
     * Class constructor.
     *
     * Initializes the Freemius SDK if not already initialized.
     *
     * @since 2.0.0
     */
    public function __construct() {
        // Check if the storekit_fms function exists to avoid conflicts.
        if ( ! function_exists( 'storekit_fms' ) ) {
            $this->storekit_fms();

            // Signal that Freemius SDK was initiated.
            do_action( 'storekit_fms_loaded' );
        }
    }

    /**
     * Initialize Freemius SDK.
     *
     * Sets up the Freemius SDK instance for StoreKit plugin.
     *
     * @return object|null Freemius SDK instance if initialized successfully, otherwise null.
     */
    public function storekit_fms() {
        global $storekit_fms;

        // Check if Freemius SDK instance is not already set.
        if ( ! isset( $storekit_fms ) ) {
            // Include Freemius SDK file.
            require_once STOREKIT_PATH . '/vendor/freemius/wordpress-sdk/start.php';

            // Initialize Freemius SDK with plugin details.
            $storekit_fms = fs_dynamic_init( [
                'id'                  => '15797',
                'slug'                => 'storekit',
                'type'                => 'plugin',
                'public_key'          => 'pk_f8802259e004530efb325447b9a32',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => [
                    'slug'    => 'storekit',
                    'account' => false,
                    'contact' => false,
                    'support' => false,
                ],
            ] );
        }

        return $storekit_fms;
    }
}
