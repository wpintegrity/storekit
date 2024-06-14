<?php
namespace WpIntegrity\StoreKit\Features;

/**
 * Profile Avatar Manager Class
 */
class ProfileAvatar {
    /**
     * Class constructor
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'storekit_enqueue_scripts' ] );

        add_action( 'woocommerce_edit_account_form_fields', [ $this, 'storekit_profile_avatar_fields' ] );
        add_action( 'woocommerce_save_account_details', [ $this, 'storekit_save_profile_avatar_fields'] );

        // Register AJAX handlers
        add_action('wp_ajax_storekit_delete_custom_avatar', [ $this, 'handle_storekit_delete_custom_avatar' ]);
        add_action('wp_ajax_nopriv_storekit_delete_custom_avatar', [ $this, 'handle_storekit_delete_custom_avatar' ]);

        add_filter('get_avatar', [ $this, 'storekit_get_user_avatar' ], 10, 5);
    }

    /**
     * Enqueue scripts to frontend
     *
     * @return void
     */
    public function storekit_enqueue_scripts() {

        if( is_account_page() ) {
            wp_enqueue_style( 'storekit-frontend' );
            wp_enqueue_script( 'storekit-frontend' );

            wp_localize_script( 'storekit-frontend', 'storekit_params', [
                'ajax_url'           => admin_url( 'admin-ajax.php' ),
                'nonce'              => wp_create_nonce( 'storekit_delete_custom_avatar_nonce' ), // Create nonce
                'mystery_avatar_url' => get_avatar_url( 0, ['default' => 'mystery'] ) // Pass default avatar URL
            ] );
        }
    }

    /**
     * Display profile avatar fields
     *
     * @return void
     */
    /**
     * Display profile avatar fields
     *
     * @return void
     */
    public function storekit_profile_avatar_fields() {
        $user_id        = get_current_user_id();
        $default_avatar = get_avatar_url($user_id, ['size' => 96]);
        $custom_avatar  = get_user_meta($user_id, 'storekit_custom_avatar', true) ?: get_avatar_url( 0, ['default' => 'mystery'] );
        $avatar_option  = get_user_meta($user_id, 'storekit_avatar_option', true) ?: 'gravatar';
        ?>
        <fieldset class="storekit-profile-picture">
            <legend><?php _e('Profile Picture', 'storekit'); ?></legend>
            <label for="storekit-gravatar">
                <input type="radio" id="storekit-gravatar" name="storekit_avatar_options" value="gravatar" <?php checked($avatar_option, 'gravatar'); ?>>
                <?php _e('Gravatar', 'storekit'); ?>
                <div class="storekit-avatar-image">
                    <img src="<?php echo esc_url($default_avatar); ?>" alt="">
                </div>
            </label>
            <label for="storekit-custom">
                <input type="radio" id="storekit-custom" name="storekit_avatar_options" value="custom" <?php checked($avatar_option, 'custom'); ?>>
                <?php _e('Custom', 'storekit'); ?>
                <div class="storekit-avatar-image">
                    <img src="<?php echo esc_url($custom_avatar); ?>" alt="" id="storekit-custom-avatar-preview">
                    <span id="pen-icon">
                        <img src="<?php echo STOREKIT_ASSETS .'/images/pen-icon.svg' ?>" alt="Edit">
                    </span>
                    <span id="delete-icon">
                        <img src="<?php echo STOREKIT_ASSETS .'/images/delete-icon.svg' ?>" alt="Delete">
                    </span>
                </div>
            </label>
            <input type="hidden" name="storekit_custom_avatar" id="storekit_custom_avatar" value="<?php echo esc_url($custom_avatar); ?>">
            <input type="hidden" name="storekit_custom_avatar_filename" id="storekit_custom_avatar_filename" value="">

            <div id="avatar-upload-modal" style="display: none;">
                <div id="avatar-upload-content">
                    <span id="close-modal">&times;</span>
                    <h2><?php _e('Upload and Crop Your Avatar', 'storekit'); ?></h2>
                    <input type="file" id="avatar-upload-input">
                    <img id="avatar-upload-preview" src="" alt="" style="max-width: 100%; height: auto;">
                    <button type="button" id="crop-avatar" style="display: none;"><?php _e('Crop and Upload', 'storekit'); ?></button>
                </div>
            </div>
        </fieldset>
        <?php
    }

    /**
     * Save the custom avatar image
     *
     * @param int $user_id
     * @return void
     */
    public function storekit_save_profile_avatar_fields($user_id) {
        if (isset($_POST['storekit_avatar_options'])) {
            update_user_meta($user_id, 'storekit_avatar_option', sanitize_text_field($_POST['storekit_avatar_options']));
    
            if ($_POST['storekit_avatar_options'] == 'custom' && !empty($_POST['storekit_custom_avatar'])) {
                $this->save_custom_avatar($user_id, $_POST['storekit_custom_avatar']);
            }
        }
    }

    /**
     * Handle avatar upload via AJAX
     *
     * @return void
     */
    private function save_custom_avatar($user_id, $base64_image) {
        // Decode base64 string
        if (strpos($base64_image, 'data:image/') === 0) {
            list($type, $data) = explode(';', $base64_image);
            list(, $data) = explode(',', $data);
            $decoded = base64_decode($data);

            // Get the original file name from the hidden input
            if (isset($_POST['storekit_custom_avatar_filename']) && !empty($_POST['storekit_custom_avatar_filename'])) {
                $original_filename = sanitize_file_name($_POST['storekit_custom_avatar_filename']);
                $upload_dir = wp_upload_dir();
                $filename = wp_unique_filename($upload_dir['path'], $original_filename);
                $file_path = $upload_dir['path'] . '/' . $filename;

                // Save the file
                file_put_contents($file_path, $decoded);

                // Insert the file into the WordPress media library
                $filetype = wp_check_filetype(basename($file_path), null);
                $attachment = [
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => sanitize_file_name(basename($file_path)),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                ];

                $attachment_id = wp_insert_attachment($attachment, $file_path);
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attachment_id, $file_path);
                wp_update_attachment_metadata($attachment_id, $attach_data);

                // Get the URL of the uploaded image and update user meta
                $image_url = wp_get_attachment_url($attachment_id);
                update_user_meta($user_id, 'storekit_custom_avatar', $image_url);
            }
        }
    }   
    
    /**
     * Handle AJAX request to delete custom avatar
     *
     * @return void
     */
    public static function handle_storekit_delete_custom_avatar() {
        // Log the incoming request data
        error_log('AJAX Request: ' . print_r($_POST, true));
    
        check_ajax_referer('storekit_delete_custom_avatar_nonce', 'security'); // Verify nonce
    
        if (!current_user_can('edit_user', get_current_user_id())) {
            error_log('Permission denied');
            wp_send_json_error(['message' => 'Permission denied.']);
        }
    
        $avatar_url    = esc_url_raw($_POST['avatar_url']);
        $attachment_id = attachment_url_to_postid($avatar_url);
    
        if ( $attachment_id ) {
            wp_delete_attachment($attachment_id, true);
            delete_user_meta( get_current_user_id(), 'storekit_custom_avatar' );
            wp_send_json_success();
        } else {
            error_log('Failed to delete the image');
            wp_send_json_error(['message' => 'Failed to delete the image.']);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $avatar
     * @param [type] $id_or_email
     * @param [type] $size
     * @param [type] $default
     * @param [type] $alt
     * @return void
     */
    public function storekit_get_user_avatar($avatar, $id_or_email, $size, $default, $alt) {
        $user_id = 0;
    
        if (is_numeric($id_or_email)) {
            $user_id = (int) $id_or_email;
        } elseif (is_object($id_or_email)) {
            if (!empty($id_or_email->user_id)) {
                $user_id = (int) $id_or_email->user_id;
            }
        } else {
            $user = get_user_by('email', $id_or_email);
            $user_id = $user ? $user->ID : 0;
        }
    
        if ($user_id) {
            $avatar_option = get_user_meta($user_id, 'storekit_avatar_option', true);
            if ($avatar_option === 'custom') {
                $custom_avatar = get_user_meta($user_id, 'storekit_custom_avatar', true);
                if ($custom_avatar) {
                    return '<img src="' . esc_url($custom_avatar) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" alt="' . esc_attr($alt) . '" />';
                }
            }
        }
    
        return $avatar;
    }

}