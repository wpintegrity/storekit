<?php
/**
 * New Customer Email ( plain text )
 *
 * An email sent to the admin when a new customer is registered.
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
echo "= " . esc_html( $email_heading ) . " =\n\n";
?>

<?php esc_html_e( 'Hello there,', 'wookit' ); echo " \n";?>
<?php printf( esc_html( 'A new customer has registered in your %s store', 'wookit' ), esc_html( $data['site_name'] ) ); echo " \n"; ?>

<?php echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n"; ?>

<?php esc_html_e( 'Customer: '. $data['customer_name'], 'wookit' ); echo " \n"; ?>

<?php esc_html_e( 'To edit vendor access and details visit : '.$data['customer_edit'], 'wookit' );  ?>
<?php
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
