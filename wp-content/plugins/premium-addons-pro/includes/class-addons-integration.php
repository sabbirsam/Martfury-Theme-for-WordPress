<?php

namespace PremiumAddonsPro;

use PremiumAddonsPro\License\Admin;
use PremiumAddonsPro\Admin\Settings\Premium_Pro_Admin_Settings;
use PremiumAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit();

class Addons_Integration {
    
    //Instance of the class
    private static $instance = null;
    
    //Modules Keys
    private static $modules = null;
    
    public function __construct() {
        
        self::$modules = Premium_Pro_Admin_Settings::get_enabled_keys();
       
        //Load plugin icons font
        add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'enqueue_icon_font' ) );
        
        // Load widgets files
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_register' ) );
        
        // Enqueue Editor assets
        add_action( 'elementor/editor/before_enqueue_scripts', array( $this,'enqueue_editor_scripts') );
        
        // Enqueue Preview CSS files
        add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );
        
        // Register Frontend CSS files
        add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_frontend_styles' ) );
        
        // Enqueue Frontend CSS files
        add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_styles' ) );
        
        // Registers Frontend JS files
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ) );
        
        // Registers AJAX Hooks
        add_action( 'wp_ajax_get_page_token', array( $this, 'get_page_token' ) );
        // add_action( 'wp_ajax_set_twitter_cached_response', array( $this, 'set_twitter_cached_response' ) );
        // add_action( 'wp_ajax_get_twitter_cached_response', array( $this, 'get_twitter_cached_response' ) );
        
    }
    
    /**
     * Loads widgets font CSS file
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return void
     */
    public function enqueue_icon_font() {
        
        wp_enqueue_style(
            'premium-pro-elements',
            PREMIUM_PRO_ADDONS_URL . 'assets/editor/css/style.css'
        );
        
    }
    
    /**
     * Get Facebook page token for Facebook Reviews
     * 
     * @since 1.5.9
     * @access public
     * 
     * @return void
     */
    public function get_page_token() {
        
        check_ajax_referer( 'papro-templates', 'security' );
        
        $api_url = 'https://appfb.premiumaddons.com/wp-json/fbapp/v2/pages';

        $response = wp_remote_get( $api_url, array(
			'timeout'   => 60,
			'sslverify' => false
		) );
        
        $body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

        wp_send_json_success( $body );
        
        
    }

    /**
     * Get Twitter Response
     * 
     * Used to get transient for twitter response
     * 
     * @since 1.9.0
     * @access public
     * 
     * @return void
     */
    public function get_twitter_cached_response() {

        if( ! isset( $_GET['users'] ) ) {
            wp_send_json_error();
        }

        $users = $_GET['users'];

        $transient_name = 'papro_twitter_feed_' . $users[0];

        //Search for cached data
        $response = get_transient( $transient_name );

        $data = array(
            'users'     => $users,
            'feed'      => $response
        );
        
        wp_send_json_success( $data );

    }

    /**
     * Set Twitter Response
     * 
     * Used to set transient for twitter response
     * 
     * @since 1.9.0
     * @access public
     * 
     * @return void
     */
    public function set_twitter_cached_response() {

        if( ! isset( $_POST['users'] ) ) {
            wp_send_json_error();
        }

        $users = $_POST['users'];

        if( ! isset( $_POST['feed'] ) ) {
            wp_send_json_error();
        }

        $feed = $_POST['feed'];

        $transient_name = 'papro_twitter_feed_' . $users[0];

        $expire_time = MINUTE_IN_SECONDS;
        
        set_transient( $transient_name, $feed, $expire_time );

        wp_send_json_success();

    }
    
    /**
     * Enqueue Editor assets
     * 
     * @since 1.4.5
     * @access public
     * 
     * @return void
     */
    public function enqueue_editor_scripts() {
        
        $fb_reviews = self::$modules['premium-facebook-reviews'];
        
        wp_enqueue_script(
            'papro-editor',
            PREMIUM_PRO_ADDONS_URL . 'assets/editor/js/editor.js', 
            array(),
            PREMIUM_PRO_ADDONS_VERSION,
            'all'
        ); 
       
        if( $fb_reviews ) {
            
            wp_register_script(
                'papro-fb-helper',
                PREMIUM_PRO_ADDONS_URL . 'assets/editor/js/fb-helper-min.js',
                array(),
                PREMIUM_PRO_ADDONS_VERSION,
                false
            );

            wp_register_script(
                'papro-fb-connect',
                PREMIUM_PRO_ADDONS_URL . 'assets/editor/js/fb-connect.js',
                array('papro-fb-helper'),
                PREMIUM_PRO_ADDONS_VERSION,
                false
            );
            
            $license_key    = Admin::get_license_key();
            
            $data = array(
                'ajaxurl'   => esc_url( admin_url( 'admin-ajax.php' ) ),
                'nonce' 	=> wp_create_nonce( 'papro-templates' ),
                'key'       => $license_key
            );
            
            
            
            wp_localize_script( 'papro-fb-connect', 'settings', $data );
            
            wp_enqueue_script('papro-fb-helper');
            wp_enqueue_script('papro-fb-connect');
            
        }
        
    }
    
    /** 
     * Register Front CSS files
     * 
     * @since 1.2.8
     * @access public
     * 
     */
    public function register_frontend_styles() {
        
        $dir = Helper_Functions::get_styles_dir();
		$suffix = Helper_Functions::get_assets_suffix();
        
        wp_register_style(
            'tooltipster',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/tooltipster' . $suffix . '.css',
            array(),
            PREMIUM_PRO_ADDONS_VERSION,
            'all'
        );
        
    }
    
    /** 
     * Enqueue Preview CSS files
     * 
     * @since 1.2.8
     * @access public
     * 
     */
    public function enqueue_preview_styles() {
        
        wp_enqueue_style('tooltipster');
        
    }
    
    /** Load widgets require function
    * @since 1.0.0
    * @access public
    */
    public function widgets_register() {
        $this->premium_pro_widgets_area();
    }
    
    /** 
     * Enqueue required CSS files
     * 
     * @since 1.2.7
     * @access public
     * 
     */
    public function enqueue_frontend_styles() {

        $dir = Helper_Functions::get_styles_dir();
		$suffix = Helper_Functions::get_assets_suffix();
        
        $is_rtl = is_rtl() ? '-rtl' : '';
        
        wp_enqueue_style(
            'premium-pro',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-addons' . $is_rtl . $suffix . '.css',
            array(),
            PREMIUM_PRO_ADDONS_VERSION,
            'all'
        );
        
    }
    
    /** 
     * Requires widgets files
     * 
     * @since 1.0.0
     * @access private
     * 
     */
    private function premium_pro_widgets_area() {

        $check_component_active = self::$modules;
        
        foreach ( glob( PREMIUM_PRO_ADDONS_PATH . 'widgets/' . '*.php' ) as $file ) {
            $slug = basename( $file, '.php' );
            
            $enabled = isset( $check_component_active[ $slug ] ) ? $check_component_active[ $slug ] : '';
            
            if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $check_component_active ) {
                $this->register_addon( $file );
            }
        }

    }
    
    /**
     * Registers required JS files
     * 
     * @since 1.0.0
     * @access public
     * 
     */
    public function register_frontend_scripts() {
        
        $dir = Helper_Functions::get_scripts_dir();
		$suffix = Helper_Functions::get_assets_suffix();
        
        $magic_section = self::$modules['premium-magic-section'];
        
        wp_register_script(
            'premium-pro-js',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-addons' . $suffix . '.js',
            array(
                'jquery',
                'jquery-ui-draggable',
                'jquery-ui-sortable',
                'jquery-ui-resizable'
            ),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        $data = array(
            'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php' ) ),
            'magicSection'  => $magic_section ? true : false
        );
        
		wp_localize_script( 'premium-pro-js', 'PremiumProSettings', $data );

        wp_register_script(
            'codebird-js',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/codebird' . $suffix . '.js',
            array('jquery'),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'dot-js',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/doT' . $suffix . '.js',
            array('jquery'),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
            
        wp_register_script(
            'jquery-socialfeed-js',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/socialfeed' . $suffix . '.js',
            array('jquery'),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );

        wp_register_script(
            'instafeed-js', 
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/instafeed' . $suffix . '.js',
            array(), 
            PREMIUM_PRO_ADDONS_VERSION, 
            true
        );
        
        wp_register_script(
            'chart-js', 
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/charts' . $suffix . '.js',
            array(), 
            PREMIUM_PRO_ADDONS_VERSION, 
            false
        );
        
        wp_register_script(
            'event-move',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/event-move' . $suffix . '.js',
            array('jquery'), 
            PREMIUM_PRO_ADDONS_VERSION, 
            true
        );
            
        wp_register_script(
            'pa-imgcompare', 
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/imgcompare' . $suffix . '.js',
            array('jquery'), 
            PREMIUM_PRO_ADDONS_VERSION, 
            true
        );
        
        wp_register_script(
            'premium-behance-js', 
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/embed-behance' . $suffix . '.js',
            array('jquery'), 
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'anime-js', 
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/anime' . $suffix . '.js',
            array( 'jquery' ), 
            PREMIUM_PRO_ADDONS_VERSION, 
            true
        );
        
        wp_register_script(
            'tweenmax-js', 
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/TweenMax' . $suffix . '.js',
            array( 'jquery' ), 
            PREMIUM_PRO_ADDONS_VERSION, 
            true
        );
        
        wp_register_script(
            'tilt-js', 
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/universal-tilt' . $suffix . '.js',
            array( 'jquery' ), 
            PREMIUM_PRO_ADDONS_VERSION, 
            true
        );
        
        wp_register_script(
            'table-sorter',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/tablesorter' . $suffix . '.js',
            array('jquery'),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'tooltipster-bundle-js',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/tooltipster' . $suffix . '.js',
            array(),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        wp_register_script( 
            'multi-scroll',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/multiscroll' . $suffix . '.js',
            array('jquery'),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'gsap',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/gsap' . $suffix . '.js',
            array( 'jquery' ),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        wp_register_script( 'papro-hscroll',
            PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-hscroll' . $suffix . '.js',
            array( 'jquery' ),
            PREMIUM_PRO_ADDONS_VERSION,
            true
        );
        
        //Localize jQuery with required data for Section Add-ons
        wp_localize_script(
			'jquery',
			'papro_addons',
			array(
				'url'           => admin_url( 'admin-ajax.php' ),
				'particles_url' => PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/particles' . $suffix . '.js',
                'kenburns_url'  => PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/cycle' . $suffix . '.js',
                'gradient_url'  => PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-gradient' . $suffix . '.js',
                'parallax_url'  => PREMIUM_PRO_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-parallax' . $suffix . '.js',
			)
		);

    }
    
    /**
    * Register addon by file name
    *
    * @param  string $file            File name.
    * @param  object $widgets_manager Widgets manager instance.
    * @return void
    */
    public function register_addon( $file ) {

        $widget_manager = \Elementor\Plugin::instance()->widgets_manager;
        
        $base  = basename( str_replace( '.php', '', $file ) );
        $class = ucwords( str_replace( '-', ' ', $base ) );
        $class = str_replace( ' ', '_', $class );
        $class = sprintf( 'PremiumAddonsPro\Widgets\%s', $class );
        
        require $file;
        
        if ( 'PremiumAddonsPro\Widgets\Premium_Facebook_Reviews'== $class || 'PremiumAddonsPro\Widgets\Premium_Google_Reviews' == $class ) {
            require_once ( PREMIUM_PRO_ADDONS_PATH . 'includes/deps/urlopen.php' );

            require_once ( PREMIUM_PRO_ADDONS_PATH . 'includes/deps/reviews.php' ); 
        }

        if ( class_exists( $class ) ) {
            $widget_manager->register_widget_type( new $class );
        }
    }
    
    /**
    * Creates and returns an instance of the class
    * @since 1.0.0
    * @access public
    * return object
    */
   public static function get_instance() {
       if( self::$instance == null ) {
           self::$instance = new self;
       }
       return self::$instance;
   }
}
    

if ( ! function_exists( 'premium_addons_integration' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  1.0.0
	 * @return object
	 */
	function premium_addons_integration() {
		return Addons_Integration::get_instance();
	}
}
premium_addons_integration();
