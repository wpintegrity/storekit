<?php
namespace WpIntegrity\StoreKit\Emails;

use WC_Email;

/**
 * New Customer Email.
 *
 * Handles the email notifications sent when a new customer registers in the store.
 */
class NewCustomer extends WC_Email {

    /**
     * Constructor.
     *
     * Initializes the email by setting up its properties and hooks.
     */
    public function __construct() {
        $this->id               = 'storekit_new_customer_registration';
        $this->title            = __( 'New Customer Registration', 'storekit' );
        $this->description      = __( 'These emails are sent to chosen recipient(s) when a new customer registers in the store.', 'storekit' );
        $this->template_html    = 'emails/new-customer-registration.php';
        $this->template_plain   = 'emails/plain/new-customer-registration.php';
        $this->template_base    = STOREKIT_PATH . '/templates/';

        // Triggers for this email.
        add_action( 'woocommerce_created_customer', [ $this, 'trigger' ], 20 );

        // Call parent constructor.
        parent::__construct();

        // Set the recipient.
        $this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
    }

    /**
     * Get email subject.
     *
     * @since 1.0.0
     * @return string Email subject.
     */
    public function get_default_subject() {
        return __( '[{site_name}] A New customer has registered', 'storekit' );
    }

    /**
     * Get email heading.
     *
     * @since 1.0.0
     * @return string Email heading.
     */
    public function get_default_heading() {
        return __( 'New Customer Registered - {customer_name}', 'storekit' );
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int $customer_id The ID of the newly registered customer.
     */
    public function trigger( $customer_id ) {
        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            return;
        }

        $customer                       = get_user_by( 'id', $customer_id );
        $this->object                   = $customer;
        $this->find['customer_name']    = '{customer_name}';
        $this->find['customer_edit']    = '{customer_edit}';
        $this->find['site_name']        = '{site_name}';
        $this->find['site_url']         = '{site_url}';

        $this->replace['customer_name'] = ucwords( $customer->display_name );
        $this->replace['customer_edit'] = admin_url( 'user-edit.php?user_id=' . $customer_id );
        $this->replace['site_name']     = $this->get_from_name();
        $this->replace['site_url']      = site_url();

        $this->setup_locale();
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        $this->restore_locale();
    }

    /**
     * Get the HTML content for the email.
     *
     * @access public
     * @return string HTML content.
     */
    public function get_content_html() {
        ob_start();
        wc_get_template(
            $this->template_html, array(
                'customer'      => $this->object,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => true,
                'plain_text'    => false,
                'email'         => $this,
                'data'          => $this->replace,
            ), 'storekit/', $this->template_base
        );
        return ob_get_clean();
    }

    /**
     * Get the plain text content for the email.
     *
     * @access public
     * @return string Plain text content.
     */
    public function get_content_plain() {
        ob_start();
        wc_get_template(
            $this->template_plain, array(
                'customer'      => $this->object,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => true,
                'plain_text'    => true,
                'email'         => $this,
                'data'          => $this->replace,
            ), 'storekit/', $this->template_base
        );
        return ob_get_clean();
    }

    /**
     * Initialize settings form fields.
     *
     * Defines the settings form fields for the email configuration.
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'       => __( 'Enable/Disable', 'storekit' ),
                'type'        => 'checkbox',
                'label'       => __( 'Enable this email notification', 'storekit' ),
                'default'     => 'yes',
            ),
            'recipient' => array(
                'title'       => __( 'Recipient(s)', 'storekit' ),
                'type'        => 'text',
                /* translators: %s: admin email */
                'description' => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'storekit' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
                'placeholder' => '',
                'default'     => '',
                'desc_tip'    => true,
            ),
            'subject' => array(
                'title'       => __( 'Subject', 'storekit' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'placeholder' => $this->get_default_subject(),
                'default'     => '',
            ),
            'heading' => array(
                'title'       => __( 'Email heading', 'storekit' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'placeholder' => $this->get_default_heading(),
                'default'     => '',
            ),
            'email_type' => array(
                'title'       => __( 'Email type', 'storekit' ),
                'type'        => 'select',
                'description' => __( 'Choose which format of email to send.', 'storekit' ),
                'default'     => 'html',
                'class'       => 'email_type wc-enhanced-select',
                'options'     => $this->get_email_type_options(),
                'desc_tip'    => true,
            ),
        );
    }
}
