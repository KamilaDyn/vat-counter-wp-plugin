<?php

/**
 * Plugin Name:       Vat Counter
 * Description:      Vat Counter for products. Count price brutto and price vat. Send data to DB, name of product, netto price, brutto price, vat, vat rate, ip user and date of submit form. 
 * Version:           1.0.0
 * Author:         Kamila Dynysiuk
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       vat-counter
 * 
 */
// Exit if accessed directly

if (!defined('ABSPATH')) {
    exit;
}
// define variable for path to this plugin file
if (!class_exists('VatCounter')) {
    class VatCounter
    {
        function __construct()
        {
            // add_action('init', array($this, 'custom_post_type'));
            $this->plugin_includes();
            $this->loader_operations();
        }

        function plugin_includes()
        {
            include_once('vat-order.php');
            include_once('vat_calculation_form.php');
            include_once('vat-router.php');
        }

        function loader_operations()
        {
            add_action('wp_enqueue_scripts', array($this, 'enqueue'));
            add_action('init', array($this, 'plugin_init'));
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_filter('manage_vat_calculations_posts_columns', 'vat_calculations_column');
            add_action('manage_vat_calculations_posts_custom_column', 'my_manage_vat_calculation_columns', 10, 2);
            add_action('wp_ajax_vat_counter_data', array($this, 'vat_counter_data'));    //execute when wp logged in
            add_action('wp_ajax_nopriv_vat_counter_data', array($this, 'vat_counter_data')); //execute when logged out
            add_action('rest_api_init', 'vat_counter');
            add_shortcode('vat_counter', 'vat_counter_button_handler');
        }

        function plugin_init()
        {
            //register orders
            vat_calculations_order_page();
        }

        function add_meta_boxes()
        {
            //   add_meta_box('vat-counter-order-box', __('Edit Vat Counter', 'vat-counter'), 'vat_calculations_order_meta_box', 'vat_calculations', 'normal', 'high');
        }

        function enqueue()
        {
            // enqueue all our scripts
            wp_enqueue_style('my-plugin-style', plugins_url('/assets/my-style.css', __FILE__));
            wp_enqueue_script('my-plugin-script', plugins_url('/js/myscript.js', __FILE__), '', '1.0', true);
            wp_localize_script('my-plugin-script', 'vatData',  array(
                'root_url' => get_site_url(),
                'nonce' => wp_create_nonce('wp_rest')
            ));
        }

        function activate()
        {
            require_once plugin_dir_path(__FILE__) . 'inc/vat-counter-activate.php';
            VatPluginActivate::activate();
        }
    }
    $vat_counter = new VatCounter();

    // activation
    register_activation_hook(__FILE__, array($vat_counter, 'activate'));
    // deactivation
    require_once plugin_dir_path(__FILE__) . 'inc/vat-counter-deactivate.php';
    register_deactivation_hook(__FILE__, array('VatPluginDeactivate', 'deactivate'));
}
