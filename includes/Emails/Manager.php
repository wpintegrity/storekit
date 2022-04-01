<?php
namespace WooComToolkit\Emails;
/**
 * Handles email sending
 */
class Manager {

	/**
	 * Constructor sets up actions
	 */
	public function __construct() {

        $wctk_new_cus_reg_option = wctk_get_option( 'wc_new_customer_reg_email', 'woocommerce', 'on' );

        if( $wctk_new_cus_reg_option == 'on' ){
	        add_filter( 'woocommerce_email_classes', array( $this, 'load_wctk_emails' ), 99 );
        }

        add_filter( 'woocommerce_template_directory', array( $this, 'set_email_template_directory' ), 15, 2 );
        add_filter( 'woocommerce_email_actions', array( $this, 'register_email_actions' ) );
		
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
     * Add WooCom Toolkit Email classes in WC Email
     */
    public function load_wctk_emails( $wc_emails ) {
        require_once WOOCOM_TOOLKIT_INCLUDES . '/Emails/NewCustomer.php';

        $wc_emails['WCTK_New_Customer']   = new NewCustomer();

        return $wc_emails;
    }

    /**
     * Set template override directory for WooComToolkit Emails
     *
     * @since 0.1
     *
     * @param string $template_dir
     *
     * @param string $template
     *
     * @return string
     */
    public function set_email_template_directory( $template_dir, $template ) {
        $wctk_emails = [
            'new-customer-registration.php'
        ];

        $template_name = basename( $template );

        if ( in_array( $template_name, $wctk_emails, true ) ) {
            return 'woocom-toolkit';
        }

        return $template_dir;
    }

    /**
     * Register Dokan Email actions for WC
     *
     * @since 0.1
     *
     * @param array $actions
     *
     * @return $actions
     */
    public function register_email_actions( $actions ) {
        $dokan_email_actions = [
            'wctk_new_customer_registration'
        ];

        foreach ( $dokan_email_actions as $action ) {
            $actions[] = $action;
        }

        return $actions;
    }
	
}// end of class