jQuery( document ).ready( function($) {
    $( '.vendor-customer-registration input[value="seller"]' ).on( 'change', function() {
        $( '.storekit_wc_tnc' ).hide();
    } )
    
    $( '.vendor-customer-registration input[value="customer"]' ).on( 'change', function() {
        $( '.storekit_wc_tnc' ).show();
    } )

    var storekit_tnc_check = $( '#storekit_terms_conditions' );

    if( storekit_tnc_check.length > 0 ) {
        var submitButton = $('.woocommerce-form-register__submit');
        submitButton.prop('disabled', true);

        storekit_tnc_check.on('change', function() {
            if ($(this).is(':checked')) {
                submitButton.prop('disabled', false);
            } else {
                submitButton.prop('disabled', true);
            }
        });
    }
} )