<?php
namespace WpIntegrity\StoreKit\Features;

use WP_Comment;

/**
 * Profile Avatar Manager Class.
 *
 * Manages custom profile avatars for WooCommerce user accounts.
 *
 * @since 2.0.0
 */
class ProfileAvatar {
    
    /**
     * Class constructor.
     *
     * Initializes actions and filters for managing profile avatars.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'storekit_enqueue_scripts' ] );
        add_action( 'woocommerce_edit_account_form_fields', [ $this, 'storekit_profile_avatar_fields' ] );
        add_action( 'woocommerce_save_account_details', [ $this, 'storekit_save_profile_avatar_fields' ] );
        add_action( 'wp_ajax_storekit_delete_custom_avatar', [ $this, 'handle_storekit_delete_custom_avatar' ] );
        add_filter( 'get_avatar', [ $this, 'storekit_get_user_avatar' ], 10, 5 );
    }

    /**
     * Enqueue scripts to the frontend.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function storekit_enqueue_scripts() {
        if ( is_account_page() ) {
            wp_enqueue_style( 'storekit-frontend' );
            wp_enqueue_script( 'storekit-frontend' );

            wp_localize_script( 'storekit-frontend', 'storekit_params', [
                'ajax_url'           => admin_url( 'admin-ajax.php' ),
                'nonce'              => wp_create_nonce( 'storekit_delete_custom_avatar_nonce' ),
                'mystery_avatar_url' => get_avatar_url( 0, ['default' => 'mystery'] ),
            ] );
        }
    }

    /**
     * Display profile avatar fields on the account edit page.
     *
     * @since 2.0.0
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
                        <img src="<?php echo STOREKIT_ASSETS .'/images/pen-icon.svg'; ?>" alt="Edit">
                    </span>
                    <span id="delete-icon">
                        <img src="<?php echo STOREKIT_ASSETS .'/images/delete-icon.svg'; ?>" alt="Delete">
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
     * Save the custom avatar image.
     *
     * @since 2.0.0
     *
     * @param int $user_id User ID.
     * @return void
     */
    public function storekit_save_profile_avatar_fields( $user_id ) {
        if ( isset( $_POST['storekit_avatar_options'] ) ) {
            update_user_meta( $user_id, 'storekit_avatar_option', sanitize_text_field( $_POST['storekit_avatar_options'] ) );

            if ( $_POST['storekit_avatar_options'] === 'custom' && !empty( $_POST['storekit_custom_avatar'] ) ) {
                $this->save_custom_avatar( $user_id, $_POST['storekit_custom_avatar'] );
            }
        }
    }

    /**
     * Save the custom avatar image.
     *
     * @since 2.0.0
     *
     * @param int $user_id User ID.
     * @param string $base64_image Base64 encoded image string.
     * @return void
     */
    private function save_custom_avatar( $user_id, $base64_image ) {
        if ( strpos( $base64_image, 'data:image/' ) === 0 ) {
            list( $type, $data ) = explode( ';', $base64_image );
            list( , $data ) = explode( ',', $data );
            $decoded = base64_decode( $data );

            if ( isset( $_POST['storekit_custom_avatar_filename'] ) && !empty( $_POST['storekit_custom_avatar_filename'] ) ) {
                $original_filename = sanitize_file_name( $_POST['storekit_custom_avatar_filename'] );
                $upload_dir = wp_upload_dir();
                $filename = wp_unique_filename( $upload_dir['path'], $original_filename );
                $file_path = $upload_dir['path'] . '/' . $filename;

                file_put_contents( $file_path, $decoded );

                $filetype = wp_check_filetype( basename( $file_path ), null );
                $attachment = [
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => sanitize_file_name( basename( $file_path ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
                ];

                $attachment_id = wp_insert_attachment( $attachment, $file_path );
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $attach_data = wp_generate_attachment_metadata( $attachment_id, $file_path );
                wp_update_attachment_metadata( $attachment_id, $attach_data );

                $image_url = wp_get_attachment_url( $attachment_id );
                update_user_meta( $user_id, 'storekit_custom_avatar', $image_url );
            }
        }
    }

    /**
     * Handle AJAX request to delete custom avatar.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public static function handle_storekit_delete_custom_avatar() {
        check_ajax_referer( 'storekit_delete_custom_avatar_nonce', 'security' );

        if ( ! current_user_can( 'edit_user', get_current_user_id() ) ) {
            wp_send_json_error( [ 'message' => 'Permission denied.' ] );
        }

        $avatar_url    = esc_url_raw( $_POST['avatar_url'] );
        $attachment_id = attachment_url_to_postid( $avatar_url );

        if ( $attachment_id ) {
            wp_delete_attachment( $attachment_id, true );
            delete_user_meta( get_current_user_id(), 'storekit_custom_avatar' );
            wp_send_json_success();
        } else {
            wp_send_json_error( [ 'message' => 'Failed to delete the image.' ] );
        }
    }

    /**
     * Filter the avatar output.
     *
     * @since 2.0.0
     *
     * @param string $avatar HTML for the user's avatar.
     * @param mixed $id_or_email User ID, email, or object.
     * @param int $size Size of the avatar.
     * @param string $default URL of the default avatar.
     * @param string $alt Alternative text for the avatar.
     * @return string Modified avatar HTML.
     */
    public function storekit_get_user_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
        $user_id = 0;
    
        if ( is_numeric( $id_or_email ) ) {
            $user_id = (int) $id_or_email;
        } elseif ( is_object( $id_or_email ) ) {
            if ( $id_or_email instanceof WP_Comment ) {
                $user_id = (int) $id_or_email->user_id;
                if ( !$user_id ) {
                    $user = get_user_by( 'email', $id_or_email->comment_author_email );
                    $user_id = $user ? $user->ID : 0;
                }
            } elseif ( ! empty( $id_or_email->user_id ) ) {
                $user_id = (int) $id_or_email->user_id;
            }
        } else {
            $user = get_user_by( 'email', $id_or_email );
            $user_id = $user ? $user->ID : 0;
        }
    
        if ( $user_id ) {
            $avatar_option = get_user_meta( $user_id, 'storekit_avatar_option', true );
            if ( $avatar_option === 'custom' ) {
                $custom_avatar = get_user_meta( $user_id, 'storekit_custom_avatar', true );
                if ( $custom_avatar ) {
                    return '<img src="' . esc_url( $custom_avatar ) . '" width="' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" alt="' . esc_attr( $alt ) . '" />';
                }
            }
        }
    
        return $avatar;
    }
    
}
