<?php
namespace WpIntegrity\StoreKit\Emails;

use WpIntegrity\StoreKit\Options;
/**
 * Handles email sending
 */
class Manager {

	/**
	 * Constructor sets up actions
	 */
	public function __construct() {

        $new_customer_registration_email = Options::get_option( 'new_customer_registration_email', 'woocommerce', false );

        if( $new_customer_registration_email === true ){
	        add_filter( 'woocommerce_email_classes', [ $this, 'load_storekit_emails' ], 99 );
        }

        add_filter( 'woocommerce_template_directory', [ $this, 'set_email_template_directory' ], 15, 2 );
        add_filter( 'woocommerce_email_actions', [ $this, 'register_email_actions' ] );
		
	}
	
	/**
     * Get from name for email.
     *
     * @access public
     * @return string
     */
    public function get_from_name() {
        return wp_specialchars_decode( esc_html( get_option( 'woocommerce_email_from_name' ) ), ENT_QUOTES );
    }

    /**
     * Add StoreKit Email classes in WC Email
     */
    public function load_storekit_emails( $wc_emails ) {
        require_once STOREKIT_INCLUDES . '/Emails/NewCustomer.php';

        $wc_emails['StoreKit_New_Customer']   = new NewCustomer();

        return $wc_emails;
    }

    /**
     * Set template override directory for StoreKit Emails
     *
     * @since 1.0
     *
     * @param string $template_dir
     *
     * @param string $template
     *
     * @return string
     */
    public function set_email_template_directory( $template_dir, $template ) {
        $storekit_emails = [
            'new-customer-registration.php'
        ];

        $template_name = basename( $template );

        if ( in_array( $template_name, $storekit_emails, true ) ) {
            return 'storekit';
        }

        return $template_dir;
    }

    /**
     * Register StoreKit Email actions for WC
     *
     * @since 1.0
     *
     * @param array $actions
     *
     * @return $actions
     */
    public function register_email_actions( $actions ) {
        $dokan_email_actions = [
            'storekit_new_customer_registration'
        ];

        foreach ( $dokan_email_actions as $action ) {
            $actions[] = $action;
        }

        return $actions;
    }
	
}// end of class