<?php
/*
Plugin Name: WP STRIPE CART
Plugin URI: https://wordpress.org/plugins/wp-stripe-cart/
Description: Simple cart for WordPress with STRIPE payment. Ideal for someone who wants to sell only few products. 
Version: 1.0.7
Author: Yosuke Inoue
Author URI: https://www.metrocode.co/
Text Domain: wp-stripe-cart
License: GPLv2
*/

define( 'WP_STRIPE_CART_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( WP_STRIPE_CART_PLUGIN_DIR . 'vendor/autoload.php' );

require_once( ABSPATH.'wp-admin/includes/class-wp-list-table.php' );
require_once( WP_STRIPE_CART_PLUGIN_DIR . 'class.wp-stripe-cart-list-table.php' );
require_once( WP_STRIPE_CART_PLUGIN_DIR . 'class.wp-stripe-cart-orders.php' );
require_once( WP_STRIPE_CART_PLUGIN_DIR . 'class.wp-stripe-cart-setting.php' );
require_once( WP_STRIPE_CART_PLUGIN_DIR . 'class.wp-stripe-product.php' );
require_once( WP_STRIPE_CART_PLUGIN_DIR . 'class.wp-stripe-cart.php' );

class WpStripeCart {

    const VERSION                    = '1.0.7';
    const PLUGIN_ID                  = 'wp-stripe-cart';
    const ORDERS_POST_TYPE            = 'wsc_orders';
    const CREDENTIAL_ACTION          = self::PLUGIN_ID . '-nonce-action';
    const CREDENTIAL_NAME            = self::PLUGIN_ID . '-nonce-key';
    const PLUGIN_DB_PREFIX           = self::PLUGIN_ID . '_';
    
    protected static $PRODUCT_TEMPLATE           = NULL;
    protected static $CART_ICON_TEMPLATE         = NULL;
    protected static $CART_TEMPLATE              = NULL;
    protected static $CHECKOUT_TEMPLATE          = NULL;
    protected static $STRIPE_STYLE_JSON          = NULL;
    protected static $WP_STRIPE_CART_CSS         = NULL;

    static function init() {
        return new WpStripeCart();
    }

    function __construct() {

        load_plugin_textdomain('wp-stripe-cart');

        if (session_status() == PHP_SESSION_NONE) {
			session_start();
        }
        
        if (is_admin()) {
            // Add menu
            add_action('admin_menu', [$this, 'set_plugin_menu']);
            // Add css for setting
            add_action('admin_enqueue_scripts', ['WpStripeCartSetting', 'wp_stripe_cart_admin']);
            //SAVE settings
            add_action('admin_init', ['WpStripeCartSetting', 'save_settings']);
            add_action('pre_get_posts', ['WpStripeCartOrders', 'alter_search_query']);
        }

        //Set Constants
        add_action('plugins_loaded', [$this,'set_constants']);

        //Add post type
        add_action('init', ['WpStripeCartOrders','create_order_posttype']);

        //Add shortcode for product.
        add_shortcode('wsc-get-product', ['WpStripeCartProduct', 'set_product_shortcode']);

        //Add cart html.
        add_action('wp_footer', ['WpStripeCartCart', 'display_icon']);
        add_action('wp_footer', ['WpStripeCartCart', 'display_cart']);
        add_action('wp_footer', ['WpStripeCartCart', 'display_checkout']);
        add_action('wp_footer', ['WpStripeCartCart', 'load_stripe_js']);

        //Add stylesheets.
        add_action('wp_enqueue_scripts',[$this,'load_front_styles']);
        
        //Add scriptts
        add_action('wp_footer',[$this, 'load_front_scripts']);

        //Add ajax
        add_action( 'wp_ajax_load_stripe_style', ['WpStripeCartCart', 'load_stripe_style'] );
        add_action( 'wp_ajax_nopriv_load_stripe_style', ['WpStripeCartCart', 'load_stripe_style'] );
        add_action( 'wp_ajax_load_form_label', ['WpStripeCartCart', 'load_form_label'] );
        add_action( 'wp_ajax_nopriv_load_form_label', ['WpStripeCartCart', 'load_form_label'] );
        add_action( 'wp_ajax_load_state', ['WpStripeCartCart', 'load_state'] );
        add_action( 'wp_ajax_nopriv_load_state', ['WpStripeCartCart', 'load_state'] );
        add_action( 'wp_ajax_load_country', ['WpStripeCartCart', 'load_country'] );
        add_action( 'wp_ajax_nopriv_load_country', ['WpStripeCartCart', 'load_country'] );
        add_action( 'wp_ajax_create_payment_intent', ['WpStripeCartCart', 'create_payment_intent'] );
        add_action( 'wp_ajax_nopriv_create_payment_intent', ['WpStripeCartCart', 'create_payment_intent'] );
        add_action( 'wp_ajax_save_order', ['WpStripeCartOrders', 'save_order'] );
        add_action( 'wp_ajax_nopriv_save_order', ['WpStripeCartOrders', 'save_order'] );
        add_action( 'wp_ajax_load_settings', ['WpStripeCartSetting', 'load_settings'] );
        add_action( 'wp_ajax_nopriv_load_settings', ['WpStripeCartSetting', 'load_settings'] );
        add_action( 'wp_ajax_calculate_cart_item', ['WpStripeCartCart', 'calculate_cart_item'] );
        add_action( 'wp_ajax_nopriv_calculate_cart_item', ['WpStripeCartCart', 'calculate_cart_item'] );
        add_action( 'wp_ajax_get_varied_shipping_fee', ['WpStripeCartCart', 'get_varied_shipping_fee'] );
        add_action( 'wp_ajax_nopriv_get_varied_shipping_fee', ['WpStripeCartCart', 'get_varied_shipping_fee'] );
        add_action( 'wp_ajax_restore_cart', ['WpStripeCartCart', 'restore_cart'] );
        add_action( 'wp_ajax_nopriv_restore_cart', ['WpStripeCartCart', 'restore_cart'] );
    }

    static function set_constants(){
        if(class_exists('WpStripeCartWhiteLabel')){
            static::$PRODUCT_TEMPLATE    = WpStripeCartWhiteLabel::$PRODUCT_TEMPLATE;
            static::$CART_ICON_TEMPLATE  = WpStripeCartWhiteLabel::$CART_ICON_TEMPLATE;
            static::$CART_TEMPLATE       = WpStripeCartWhiteLabel::$CART_TEMPLATE;
            static::$CHECKOUT_TEMPLATE   = WpStripeCartWhiteLabel::$CHECKOUT_TEMPLATE;
            static::$STRIPE_STYLE_JSON   = WpStripeCartWhiteLabel::$STRIPE_STYLE_JSON;
            static::$WP_STRIPE_CART_CSS  = WpStripeCartWhiteLabel::$WP_STRIPE_CART_CSS;
        }
    }

    static function set_plugin_menu() {

        add_menu_page(
            __( 'Orders - WP Stripe Cart' , self::PLUGIN_ID),
            __( 'WP Stripe Cart' , self::PLUGIN_ID),
            'edit_posts',
            self::PLUGIN_ID,
            ['WpStripeCartOrders','list_orders'],
            'dashicons-cart',
            99
        );
    
        add_submenu_page(
            self::PLUGIN_ID,
            __( 'Order - WP Stripe Cart' , self::PLUGIN_ID),
            __( 'Order' , self::PLUGIN_ID),
            'manage_options',
            self::PLUGIN_ID,
            function(){ return; },
            99
        );

        add_submenu_page(
            self::PLUGIN_ID,
            __( 'Setting - WP Stripe Cart' , self::PLUGIN_ID),
            __( 'Setting' , self::PLUGIN_ID),
            'manage_options',
            self::PLUGIN_ID.'-setting',
            ['WpStripeCartSetting', 'show_about_plugin'],
            99
        );
    }

    static function load_front_styles(){
        wp_register_style( self::PLUGIN_ID, ((static::$WP_STRIPE_CART_CSS && file_exists(static::$WP_STRIPE_CART_CSS)) ? static::$WP_STRIPE_CART_CSS : plugin_dir_url(__FILE__).'asset/css/wp-stripe-cart.css' ));
        wp_enqueue_style( self::PLUGIN_ID);
    }

    static function load_front_scripts(){
        if(!is_admin()) wp_enqueue_script( self::PLUGIN_ID.'-vendor', plugin_dir_url(__FILE__) . 'asset/js/wp-stripe-cart-vendor.bundle.js', [], false, true);
        if(!is_admin()) wp_enqueue_script( self::PLUGIN_ID, plugin_dir_url(__FILE__) . 'asset/js/wp-stripe-cart.bundle.js', [], false, true);
    }
}

new WpStripeCart();
?>
