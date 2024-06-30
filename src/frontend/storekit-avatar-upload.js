/**
 * Handles avatar upload and interaction in the admin settings page.
 * Dependencies: jQuery, Cropper.js, cropper.min.css, storekit_params
 *
 * @since 2.0.0
 */

import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.min.css';

jQuery(document).ready(function($) {
    var cropper;
    var originalFilename;
    var defaultAvatar = storekit_params.mystery_avatar_url;

    // Show 'Change Image' button if custom option is selected on page load
    if ($('#storekit-custom').is(':checked')) {
        $('#storekit-change-avatar').show();
    }

    var avatarRadios = $('input[name="storekit_avatar_options"]');
    var avatarLabels = $('.storekit-profile-picture label');

    // Function to update selected avatar option classes
    function updateAvatarClasses() {
        avatarLabels.removeClass('selected');
        avatarRadios.each(function() {
            if ($(this).is(':checked')) {
                $(this).parent().addClass('selected');
            }
        });
    }

    avatarRadios.on('change', updateAvatarClasses);

    // Initial check for selected avatar option
    updateAvatarClasses();

    // Set initial state based on whether a custom image is uploaded
    function updateIconState() {
        var customAvatar = $('#storekit_custom_avatar').val();
        var avatarOption = $('input[name="storekit_avatar_options"]:checked').val();
        
        if (avatarOption === 'custom') {
            if (customAvatar && customAvatar !== defaultAvatar) {
                $('#pen-icon').hide();
                $('#delete-icon').show();
            } else {
                $('#pen-icon').show();
                $('#delete-icon').hide();
            }
        } else {
            $('#pen-icon').hide();
            $('#delete-icon').hide();
        }
    }

    updateIconState();

    // Handle radio button change event
    $('input[name="storekit_avatar_options"]').on('change', function() {
        updateIconState();
    });

    // Handle file input change event
    $('#avatar-upload-input').on('change', function(event) {
        var file = event.target.files[0];
        originalFilename = file.name; // Store the original filename
        
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#avatar-upload-preview').attr('src', e.target.result);
            
            // Destroy existing Cropper instance if exists
            if (cropper) {
                cropper.destroy();
            }
            
            // Initialize Cropper for the uploaded image
            cropper = new Cropper(document.getElementById('avatar-upload-preview'), {
                aspectRatio: 1,
                viewMode: 1,
                responsive: true,
                autoCropArea: 1,
                cropBoxResizable: false,
                dragMode: 'move',
                ready: function () {
                    // Adjust Cropper container size for responsiveness
                    var cropperContainer = document.querySelector('.cropper-container');
                    if (cropperContainer) {
                        cropperContainer.style.width = '100%';
                        cropperContainer.style.height = '420px';
                    }
                    
                    // Set initial crop box data
                    cropper.setCropBoxData({
                        width: 150,
                        height: 150,
                        left: (cropper.getCanvasData().width - 150) / 2,
                        top: (cropper.getCanvasData().height - 150) / 2,
                    });
                }
            });
            
            // Show 'Crop and Upload' button after image preview
            $('#crop-avatar').show();
        };
        
        // Read the uploaded file as Data URL
        reader.readAsDataURL(file);
    });

    // Handle 'Crop and Upload' button click event
    $('#crop-avatar').on('click', function() {
        // Get cropped canvas from Cropper
        var canvas = cropper.getCroppedCanvas({
            width: 600, // Use higher resolution for cropping
            height: 600,
            minWidth: 600,
            minHeight: 600,
            maxWidth: 600,
            maxHeight: 600
        });

        // Create downscaled canvas for thumbnail preview
        var downscaleCanvas = document.createElement('canvas');
        var downscaleCtx = downscaleCanvas.getContext('2d');
        downscaleCanvas.width = 150;
        downscaleCanvas.height = 150;

        // Draw cropped image on downscaled canvas
        downscaleCtx.drawImage(canvas, 0, 0, 150, 150);

        // Convert downscaled canvas to Blob
        downscaleCanvas.toBlob(function(blob) {
            var reader = new FileReader();
            reader.onloadend = function() {
                // Update custom avatar preview and hidden input values
                $('#storekit-custom-avatar-preview').attr('src', reader.result);
                $('#storekit_custom_avatar').val(reader.result);
                $('#storekit_custom_avatar_filename').val(originalFilename);
                
                // Hide avatar upload modal and update icon state
                $('#avatar-upload-modal').hide();
                updateIconState();
            };
            
            // Read Blob as Data URL
            reader.readAsDataURL(blob);
        });
    });

    // Handle 'Remove' button click event
    $('#delete-icon').on('click', function() {
        if (confirm('Are you sure you want to delete this image?')) {
            var customAvatar = $('#storekit_custom_avatar').val();
            
            if (customAvatar) {
                // Send AJAX request to delete custom avatar
                $.ajax({
                    url: storekit_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'storekit_delete_custom_avatar',
                        avatar_url: customAvatar,
                        security: storekit_params.nonce // Ensure the nonce is included
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reset avatar preview and input values
                            $('#storekit-custom-avatar-preview').attr('src', defaultAvatar);
                            $('#storekit_custom_avatar').val('');
                            $('#storekit_custom_avatar_filename').val('');
                            
                            // Destroy Cropper instance if exists and hide 'Crop and Upload' button
                            if (cropper) {
                                cropper.destroy();
                            }
                            $('#crop-avatar').hide();
                            
                            // Select default avatar option and update icon state
                            $('#storekit-gravatar').prop('checked', true);
                            updateIconState();
                            
                            // Trigger form submission to save settings
                            $('form.woocommerce-EditAccountForm').submit();
                        } else {
                            alert('Failed to delete the image. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to delete the image. Please try again.');
                    }
                });
            }
        }
    });

    // Handle 'Change Image' button click to open avatar upload modal
    $('#pen-icon').on('click', function() {
        $('#avatar-upload-modal').show();
    });

    // Handle modal close button click event
    $('#close-modal').on('click', function() {
        $('#avatar-upload-modal').hide();
    });
});
