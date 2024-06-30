/**
 * Handles registration form interactions on the vendor/customer registration page.
 * Dependencies: jQuery
 *
 * @since 1.1.0
 */

jQuery(document).ready(function($) {
    // Hide terms and conditions checkbox initially for seller registration
    $('.vendor-customer-registration input[value="seller"]').on('change', function() {
        $('.storekit_wc_tnc').hide();
    });

    // Show terms and conditions checkbox for customer registration
    $('.vendor-customer-registration input[value="customer"]').on('change', function() {
        $('.storekit_wc_tnc').show();
    });

    // Enable/disable submit button based on terms and conditions checkbox
    var storekit_tnc_check = $('#storekit_terms_conditions');
    
    if (storekit_tnc_check.length > 0) {
        var submitButton = $('.woocommerce-form-register__submit');
        
        // Disable submit button by default
        submitButton.prop('disabled', true);

        // Toggle submit button based on terms and conditions checkbox change
        storekit_tnc_check.on('change', function() {
            if ($(this).is(':checked')) {
                submitButton.prop('disabled', false); // Enable submit button when checked
            } else {
                submitButton.prop('disabled', true); // Disable submit button when unchecked
            }
        });
    }
});