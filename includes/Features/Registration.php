<?php

namespace WpIntegrity\StoreKit\Features;

use WpIntegrity\StoreKit\Options;
use WP_Error;

/**
 * Registration functions Manager Class
 */
class Registration {

    /**
     * Constructor function
     */
    public function __construct() {
        add_action( 'woocommerce_register_form', [ $this, 'terms_conditions' ], 9 );
        add_action( 'woocommerce_process_registration_errors', [ $this, 'terms_and_conditions_validation' ], 10, 3 );
    }
    
    /**
     * Add a checkbox to the registration form that the user must check to register
     * 
     * @since 1.1.2
     */
    public function terms_conditions() {
        $terms_conditions      = Options::get_option( 'terms_conditions', 'woocommerce', false );
        $terms_conditions_page = Options::get_option( 'terms_conditions_page_id', 'woocommerce' );

        if ( $terms_conditions ): ?>
            <div class="storekit_wc_tnc">
                <input type="checkbox" id="storekit_terms_conditions" name="storekit_terms_conditions" required> 
                <label for="storekit_terms_conditions">
                    <?php 
                        /* translators: %s terms & condition permalink url */
                        echo wp_kses_post( sprintf( __( 'I have read and agree to the <a target="_blank" href="%s">Terms &amp; Conditions</a>.', 'storekit' ), get_permalink( $terms_conditions_page ) ) );
                    ?>
                </label>
            </div>
        <?php endif;
    }

    /**
     * Validate the terms and conditions checkbox during registration
     * 
     * @since 1.1.2
     * @param WP_Error $errors
     * @return WP_Error
     */
    public function terms_and_conditions_validation( $errors ) {
        $nonce_value = isset($_POST['_wpnonce']) ? wp_unslash($_POST['_wpnonce']) : '';
        $nonce_value = isset($_POST['woocommerce-register-nonce']) ? wp_unslash($_POST['woocommerce-register-nonce']) : $nonce_value;

        if ( ! isset( $_POST['role'] ) || 'customer' !== $_POST['role']  || ! wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
            return $errors;
        }

        if ( !wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
            error_log('Nonce verification failed.');
            return $errors;
        }

        if ( Options::get_option('terms_conditions', 'woocommerce') ) {
            if ( empty( $_POST['storekit_terms_conditions'] ) ) {
                error_log('Terms and conditions not accepted.');
                $errors->add('terms_error', __('Please read and accept the terms and conditions before registration', 'storekit'));
            } else {
                error_log('Terms and conditions accepted.');
            }
        }

        return $errors;
    }

}