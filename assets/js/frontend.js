var jq = jQuery.noConflict();

jq( document ).ready( function(){
    jq( '.vendor-customer-registration input[value="seller"]' ).on( 'change', function(){
        jq( '.storekit_wc_tnc' ).hide();
    } )
    
    jq( '.vendor-customer-registration input[value="customer"]' ).on( 'change', function(){
        jq( '.storekit_wc_tnc' ).show();
    } )
} )