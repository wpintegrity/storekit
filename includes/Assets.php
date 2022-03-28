<?php
namespace WooComToolkit;

/**
 * Scripts and Styles Class
 */
class Assets {

    function __construct() {

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
        } else {
            add_action( 'wp_enqueue_scripts', [ $this, 'register' ], 5 );
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : WOOCOM_TOOLKIT_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, WOOCOM_TOOLKIT_VERSION );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'woocom-toolkit-magnific-popup' => [
                'src'       => WOOCOM_TOOLKIT_ASSETS . '/js/jquery.magnific-popup.min.js',
                'deps'      => [ 'jquery' ],
                'version'   => '1.1.0',
                'in_footer' => true
            ],
            'woocom-toolkit-flexslider' => [
                'src'       => WOOCOM_TOOLKIT_ASSETS . '/js/jquery.flexslider-min.js',
                'deps'      => [ 'jquery' ],
                'version'   => '2.7.2',
                'in_footer' => true
            ],
            'woocom-toolkit-frontend' => [
                'src'       => WOOCOM_TOOLKIT_ASSETS . '/js/frontend.js',
                'deps'      => [ 'jquery' ],
                'version'   => time(),
                'in_footer' => true
            ]
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'woocom-toolkit-flexslider' => [
                'src' =>  WOOCOM_TOOLKIT_ASSETS . '/css/flexslider.css'
            ],
            'woocom-toolkit-magnific-popup' => [
                'src' =>  WOOCOM_TOOLKIT_ASSETS . '/css/magnific-popup.css'
            ],
            'woocom-toolkit-style' => [
                'src' =>  WOOCOM_TOOLKIT_ASSETS . '/css/style.css'
            ],
            'woocom-toolkit-frontend' => [
                'src' =>  WOOCOM_TOOLKIT_ASSETS . '/css/frontend.css'
            ]
        ];

        return $styles;
    }

}
