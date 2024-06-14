<?php
namespace WpIntegrity\StoreKit;

/**
 * Freemius SDK Integration
 */
class Tracker {
    /**
     * Class constructor
     *
     * @return void
     * @since 2.0.0
     *
     */
    public function __construct() {
        if ( ! function_exists( 'storekit_fms' ) ) {
            $this->storekit_fms();

            // Signal that SDK was initiated.
            do_action( 'storekit_fms_loaded' );
        }
    }

    public function storekit_fms() {
        global $storekit_fms;

        if ( ! isset( $storekit_fms ) ) {
            // Include Freemius SDK.
            require_once STOREKIT_PATH . '/vendor/freemius/wordpress-sdk/start.php';

            $storekit_fms = fs_dynamic_init( array(
                'id'                  => '15797',
                'slug'                => 'storekit',
                'type'                => 'plugin',
                'public_key'          => 'pk_f8802259e004530efb325447b9a32',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'storekit',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $storekit_fms;
    }
}