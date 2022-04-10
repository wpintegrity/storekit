<?php
/*
Plugin Name: StoreKit
Plugin URI: https://wordpress.org/plugins/storekit
Description: A Helpful Toolkit WordPress plugin for WooCommerce
Version: 1.0.0
Author: Tanjir Al Mamun
Author URI: https://tanjirsdev.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: storekit
Domain Path: /languages
*/

/**
 * Copyright (c) 2022 Tanjir Al Mamun (email: contact.tanjir@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * StoreKit class
 *
 * @class StoreKit The class that holds the entire StoreKit plugin
 */
final class StoreKit {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the StoreKit class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        $this->define_constants();
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );

    }

    /**
     * Initializes the StoreKit() class
     *
     * Checks for an existing StoreKit() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new StoreKit();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
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
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'STOREKIT_VERSION', $this->version );
        define( 'STOREKIT_FILE', __FILE__ );
        define( 'STOREKIT_PATH', dirname( STOREKIT_FILE ) );
        define( 'STOREKIT_INCLUDES', STOREKIT_PATH . '/includes' );
        define( 'STOREKIT_URL', plugins_url( '', STOREKIT_FILE ) );
        define( 'STOREKIT_ASSETS', STOREKIT_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

        $installed = get_option( 'storekit_installed' );

        if ( ! $installed ) {
            update_option( 'storekit_installed', time() );
        }

        update_option( 'storekit_version', STOREKIT_VERSION );
    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once STOREKIT_INCLUDES . '/Assets.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once STOREKIT_INCLUDES . '/Admin.php';
            require_once STOREKIT_INCLUDES . '/class.settings-api.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once STOREKIT_INCLUDES . '/Frontend.php';
        }

        require_once STOREKIT_INCLUDES . '/functions.php';
        require_once STOREKIT_INCLUDES . '/Emails/Manager.php';

    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        add_action( 'init', array( $this, 'init_classes' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {

        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin']       = new StoreKit\Admin();
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend']    = new StoreKit\Frontend();
        }

        $this->container['assets']          = new StoreKit\Assets();
        $this->container['emails']          = new StoreKit\Emails\Manager();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'storekit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'frontend' :
                return ( ! is_admin() );
        }
    }

} // StoreKit

$storekit = StoreKit::init();
