<?php

namespace WooKit\Emails;

use WC_Email;

/**
 * New Customer Email.
 *
 */
class NewCustomer extends WC_Email {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id               = 'wookit_new_customer_registration';
        $this->title            = __( 'New Customer Registration', 'wookit' );
        $this->description      = __( 'These emails are sent to chosen recipient(s) when a new customer registers in the store', 'wookit' );
        $this->template_html    = 'emails/new-customer-registration.php';
        $this->template_plain   = 'emails/plain/new-customer-registration.php';
        $this->template_base    = WOOKIT_PATH . '/templates/';

        // Triggers for this email
        add_action( 'woocommerce_created_customer', [ $this, 'trigger' ], 20 );

        // Call parent constructor
        parent::__construct();

        // Other settings
        $this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
    }

    /**
     * Get email subject.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_subject() {
            return __( '[{site_name}] A New customer has registered', 'wookit' );
    }

    /**
     * Get email heading.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_heading() {
            return __( 'New Customer Registered - {customer_name}', 'wookit' );
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int $product_id The product ID.
     * @param array $postdata.
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

            $this->replace['customer_name'] = ucwords( $customer->display_name ) ;
            $this->replace['customer_edit'] = admin_url( 'user-edit.php?user_id=' . $customer_id );
            $this->replace['site_name']     = $this->get_from_name();
            $this->replace['site_url']      = site_url();

            $this->setup_locale();
            $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
            $this->restore_locale();
    }

    /**
     * Get content html.
     *
     * @access public
     * @return string
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
                    ), 'wookit/', $this->template_base
                );
            return ob_get_clean();
    }

    /**
     * Get content plain.
     *
     * @access public
     * @return string
     */
    public function get_content_plain() {
            ob_start();
                wc_get_template(
                    $this->template_html, array(
						'customer'      => $this->object,
						'email_heading' => $this->get_heading(),
						'sent_to_admin' => true,
						'plain_text'    => true,
						'email'         => $this,
						'data'          => $this->replace,
                    ), 'wookit/', $this->template_base
                );
            return ob_get_clean();
    }

    /**
     * Initialise settings form fields.
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'         => __( 'Enable/Disable', 'wookit' ),
                'type'          => 'checkbox',
                'label'         => __( 'Enable this email notification', 'wookit' ),
                'default'       => 'yes',
            ),
            'recipient' => array(
                'title'         => __( 'Recipient(s)', 'wookit' ),
                'type'          => 'text',
                'description'   => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'wookit' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
                'placeholder'   => '',
                'default'       => '',
                'desc_tip'      => true,
            ),
            'subject' => array(
                'title'         => __( 'Subject', 'wookit' ),
                'type'          => 'text',
                'desc_tip'      => true,
                'placeholder'   => $this->get_default_subject(),
                'default'       => '',
            ),
            'heading' => array(
                'title'         => __( 'Email heading', 'wookit' ),
                'type'          => 'text',
                'desc_tip'      => true,
                'placeholder'   => $this->get_default_heading(),
                'default'       => '',
            ),
            'email_type' => array(
                'title'         => __( 'Email type', 'wookit' ),
                'type'          => 'select',
                'description'   => __( 'Choose which format of email to send.', 'wookit' ),
                'default'       => 'html',
                'class'         => 'email_type wc-enhanced-select',
                'options'       => $this->get_email_type_options(),
                'desc_tip'      => true,
            ),
        );
    }
}
