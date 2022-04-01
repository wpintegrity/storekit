<?php
/*
Plugin Name: WooCom Toolkit
Plugin URI: https://tanjirsdev.com
Description: A Helpful Toolkit WordPress plugin for WooCommerce
Version: 0.1
Author: Tanjir Al Mamun
Author URI: https://tanjirsdev.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: woocom-toolkit
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
 * WooCom_Toolkit class
 *
 * @class WooCom_Toolkit The class that holds the entire WooCom_Toolkit plugin
 */
final class WooCom_Toolkit {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.1.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the WooCom_Toolkit class
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
     * Initializes the WooCom_Toolkit() class
     *
     * Checks for an existing WooCom_Toolkit() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WooCom_Toolkit();
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
        define( 'WOOCOM_TOOLKIT_VERSION', $this->version );
        define( 'WOOCOM_TOOLKIT_FILE', __FILE__ );
        define( 'WOOCOM_TOOLKIT_PATH', dirname( WOOCOM_TOOLKIT_FILE ) );
        define( 'WOOCOM_TOOLKIT_INCLUDES', WOOCOM_TOOLKIT_PATH . '/includes' );
        define( 'WOOCOM_TOOLKIT_URL', plugins_url( '', WOOCOM_TOOLKIT_FILE ) );
        define( 'WOOCOM_TOOLKIT_ASSETS', WOOCOM_TOOLKIT_URL . '/assets' );
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

        $installed = get_option( 'woocom_toolkit_installed' );

        if ( ! $installed ) {
            update_option( 'woocom_toolkit_installed', time() );
        }

        update_option( 'woocom_toolkit_version', WOOCOM_TOOLKIT_VERSION );
    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once WOOCOM_TOOLKIT_INCLUDES . '/Assets.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once WOOCOM_TOOLKIT_INCLUDES . '/Admin.php';
            require_once WOOCOM_TOOLKIT_INCLUDES . '/class.settings-api.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once WOOCOM_TOOLKIT_INCLUDES . '/Frontend.php';
        }

        require_once WOOCOM_TOOLKIT_INCLUDES . '/functions.php';
        require_once WOOCOM_TOOLKIT_INCLUDES . '/Emails/Manager.php';

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
            $this->container['admin']       = new WooComToolkit\Admin();
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend']    = new WooComToolkit\Frontend();
        }

        $this->container['assets']          = new WooComToolkit\Assets();
        $this->container['emails']          = new WooComToolkit\Emails\Manager();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'woocom-toolkit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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

} // WooCom_Toolkit

$woocom_toolkit = WooCom_Toolkit::init();
