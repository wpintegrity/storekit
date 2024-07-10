<?php
/**
 * Plugin Name: StoreKit
 * Plugin URI: https://wordpress.org/plugins/storekit
 * Description: A Powerful Toolkit WordPress plugin for WooCommerce
 * Version: 2.0.1
 * Author: WPIntegrity
 * Author URI: https://wpintegrity.com/
 * Text Domain: storekit
 * Requires Plugins: woocommerce
 * WC requires at least: 8.0.0
 * WC tested up to: 9.0.0
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * StoreKit class
 *
 * @class StoreKit
 * @version 2.0.0
 */
final class StoreKit {

    /**
     * Plugin version
     *
     * @var string
     */
    const VERSION = '2.0.1';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor for the StoreKit class
     *
     * Sets up all the appropriate hooks and actions within our plugin.
     */
    public function __construct() {
        require_once __DIR__ . '/vendor/autoload.php';

        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        // Hooks for initializing the plugin
        add_action( 'before_woocommerce_init', [ $this, 'declare_woocommerce_hpos_compatibility' ] );
        add_action( 'woocommerce_loaded', [ $this, 'init_plugin' ] );

        // Initialize Freemius tracker
        $this->init_freemius_tracker();

        // Hook to handle scenarios when WooCommerce is not loaded
        add_action( 'plugins_loaded', [ $this, 'woocommerce_not_loaded' ], 11 );
    }

    /**
     * Initializes the StoreKit() class
     *
     * Checks for an existing StoreKit() instance and if it doesn't find one, creates it.
     *
     * @return StoreKit
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param string $prop Property name.
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param string $prop Property name.
     * @return bool
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants used by the plugin.
     *
     * @return void
     */
    private function define_constants() {
        define( 'STOREKIT_VERSION', self::VERSION );
        define( 'STOREKIT_FILE', __FILE__ );
        define( 'STOREKIT_PATH', __DIR__ );
        define( 'STOREKIT_INCLUDES', STOREKIT_PATH . '/includes' );
        define( 'STOREKIT_URL', plugins_url( '', STOREKIT_FILE ) );
        define( 'STOREKIT_ASSETS', STOREKIT_URL . '/assets' );
    }

    /**
     * Initializes the plugin after all plugins are loaded.
     *
     * @return void
     */
    public function init_plugin() {
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function.
     *
     * Performs tasks on plugin activation.
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'storekit_installed' );

        if ( ! $installed ) {
            update_option( 'storekit_installed', time() );
        }

        update_option( 'storekit_version', STOREKIT_VERSION );
    }

    /**
     * Initialize the hooks for the plugin.
     *
     * @return void
     */
    private function init_hooks() {
        add_action( 'init', [ $this, 'init_classes' ] );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );

        // Add action links on the plugin screen
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'storekit_action_links' ] );
    }

    /**
     * Show action links on the plugin screen.
     *
     * @param array $links Plugin action links.
     * @return array
     */
    public function storekit_action_links( $links ) {
        $action_links = [
            'settings' => '<a href="' . admin_url( 'admin.php?page=storekit' ) . '" aria-label="' . esc_attr__( 'View StoreKit settings', 'storekit' ) . '">' . esc_html__( 'Settings', 'storekit' ) . '</a>',
        ];

        return array_merge( $action_links, $links );
    }

    /**
     * Initialize the Freemius tracker for the plugin.
     *
     * @return void
     */
    public function init_freemius_tracker() {
        $this->container['tracker'] = new \WpIntegrity\StoreKit\Tracker();
    }

    /**
     * Instantiate the required classes for the plugin.
     *
     * @return void
     */
    public function init_classes() {
        $this->container['assets']    = new WpIntegrity\StoreKit\Assets();
        $this->container['api']       = new WpIntegrity\StoreKit\Api\Manager();

        if ( is_admin() ) {
            $this->container['admin'] = new WpIntegrity\StoreKit\Admin\Manager();
        }
        
        $this->container['features'] = new WpIntegrity\StoreKit\Features\Manager();
        $this->container['emails']   = new WpIntegrity\StoreKit\Emails\Manager();
    }

    /**
     * Initialize plugin for localization.
     *
     * @return void
     */
    public function localization_setup() {
        $locale = determine_locale();

        /**
		 * Filter to adjust the StoreKit locale to use for translations.
		 */
		$locale = apply_filters( 'plugin_locale', $locale, 'storekit' ); // phpcs:ignore StoreKit.Commenting.CommentHooks.MissingSinceComment

        unload_textdomain( 'storekit' );
		load_textdomain( 'storekit', WP_LANG_DIR . '/storekit/storekit-' . $locale . '.mo' );
        load_plugin_textdomain( 'storekit', false, dirname( plugin_basename( STOREKIT_FILE ) ) . '/languages/' );
    }

    /**
     * Add High Performance Order Storage Support for WooCommerce.
     *
     * @since 2.0.0
     * @return void
     */
    public function declare_woocommerce_hpos_compatibility() {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }

    /**
     * Check whether WooCommerce is installed and active.
     *
     * @since 1.0.1
     * @return bool
     */
    public function has_woocommerce() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Check whether Dokan is installed and active.
     *
     * @since 1.0.1
     * @return bool
     */
    public function has_dokan() {
        return class_exists( 'WeDevs_Dokan' );
    }

    /**
     * Check whether WooCommerce is installed.
     *
     * @since 1.0.1
     * @return bool
     */
    public function is_woocommerce_installed() {
        return in_array( 'woocommerce/woocommerce.php', array_keys( get_plugins() ), true );
    }

    /**
     * Handles scenarios when WooCommerce is not active.
     *
     * @since 1.0.0
     * @return void
     */
    public function woocommerce_not_loaded() {
        if ( did_action( 'woocommerce_loaded' ) || ! is_admin() ) {
            return;
        }
    }
}

/**
 * Load StoreKit Plugin when all plugins are loaded.
 *
 * @return StoreKit
 */
function storekit() {
    return StoreKit::init();
}

// Initialize StoreKit plugin
storekit();
