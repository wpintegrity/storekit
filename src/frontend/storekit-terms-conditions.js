jQuery( document ).ready( function($) {
    $( '.vendor-customer-registration input[value="seller"]' ).on( 'change', function() {
        $( '.storekit_wc_tnc' ).hide();
    } )
    
    $( '.vendor-customer-registration input[value="customer"]' ).on( 'change', function() {
        $( '.storekit_wc_tnc' ).show();
    } )
} )