<?php
namespace WpIntegrity\StoreKit\Emails;

use WpIntegrity\StoreKit\Options;

/**
 * Handles email sending for StoreKit.
 * 
 * @since 1.0.0
 */
class Manager {

    /**
     * Constructor sets up actions.
     * 
     * @since 1.0.0
     */
    public function __construct() {
        $new_customer_registration_email = Options::get_option('new_customer_registration_email', 'woocommerce', false);

        if ($new_customer_registration_email === true) {
            add_filter('woocommerce_email_classes', [ $this, 'load_storekit_emails' ], 99);
        }

        add_filter('woocommerce_template_directory', [ $this, 'set_email_template_directory' ], 15, 2);
        add_filter('woocommerce_email_actions', [ $this, 'register_email_actions' ]);
    }

    /**
     * Get the "from" name for the email.
     * 
     * @since 1.0.0
     *
     * @return string The name used in the "from" field of the email.
     */
    public function get_from_name() {
        return wp_specialchars_decode(esc_html(get_option('woocommerce_email_from_name')), ENT_QUOTES);
    }

    /**
     * Add StoreKit email classes to WooCommerce email classes.
     * 
     * @since 1.0.0
     *
     * @param array $wc_emails Existing WooCommerce email classes.
     * @return array Modified WooCommerce email classes.
     */
    public function load_storekit_emails($wc_emails) {
        require_once STOREKIT_INCLUDES . '/Emails/NewCustomer.php';

        $wc_emails['StoreKit_New_Customer'] = new NewCustomer();

        return $wc_emails;
    }

    /**
     * Set the template override directory for StoreKit emails.
     * 
     * @since 1.0.0
     *
     * @param string $template_dir The existing template directory.
     * @param string $template The template name.
     * @return string The modified template directory.
     */
    public function set_email_template_directory($template_dir, $template) {
        $storekit_emails = [
            'new-customer-registration.php'
        ];

        $template_name = basename($template);

        if (in_array($template_name, $storekit_emails, true)) {
            return 'storekit';
        }

        return $template_dir;
    }

    /**
     * Register StoreKit email actions for WooCommerce.
     * 
     * @since 1.0.0
     *
     * @param array $actions Existing WooCommerce email actions.
     * @return array Modified WooCommerce email actions.
     */
    public function register_email_actions($actions) {
        $storekit_email_actions = [
            'storekit_new_customer_registration'
        ];

        foreach ($storekit_email_actions as $action) {
            $actions[] = $action;
        }

        return $actions;
    }
}