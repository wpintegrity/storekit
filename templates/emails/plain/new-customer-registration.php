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

<?php esc_html_e( 'Hello there,', 'storekit' ); echo " \n";?>
<?php printf( esc_html( 'A new customer has registered in your %s store', 'storekit' ), esc_html( $data['site_name'] ) ); echo " \n"; ?>

<?php echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n"; ?>

<?php esc_html_e( 'Customer: '. $data['customer_name'], 'storekit' ); echo " \n"; ?>

<?php esc_html_e( 'To edit vendor access and details visit : '.$data['customer_edit'], 'storekit' );  ?>
<?php
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
