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
        }

        function loader_operations()
        {
            add_action('wp_enqueue_scripts', array($this, 'enqueue'));
            add_action('init', array($this, 'plugin_init'));
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_filter('manage_vat_calculations_posts_columns', 'vat_calculations_column');
            add_action('manage_vat_calculations_posts_custom_column', 'my_manage_vat_calculation_columns', 10, 2);
            add_action('wp_ajax_set_form', array($this, 'set_form'));    //execute when wp logged in
            add_action('wp_ajax_nopriv_set_form', array($this, 'set_form')); //execute when logged out
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

            wp_enqueue_style('mypluginstyle', plugins_url('/assets/mystyle.css', __FILE__));
            wp_enqueue_script('jquery', plugins_url('/js/jquery.min.js', __FILE__));
            wp_enqueue_script('mypluginscript', plugins_url('/js/myscript.js', __FILE__));
            wp_localize_script('mypluginscript', 'cpm_object', admin_url('admin-ajax.php'));
        }

        function activate()
        {
            require_once plugin_dir_path(__FILE__) . 'inc/vat-counter-activate.php';
            VatPluginActivate::activate();
        }



        function set_form()
        {

            if (


                // https://developer.wordpress.org/themes/theme-security/using-nonces/
                !isset($_POST['vat_calculations_nonce_field'])

                && !wp_verify_nonce($_POST['vat_calculations_nonce_field'], 'vat_calculations_nonce_action')
            ) {
                function getUserIpAddr()
                {
                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        //ip from share internet
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        //ip pass from proxy
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    }
                    return $ip;
                }


                $vat = isset($_POST['final_vat']) ? $_POST['final_vat'] : 'N/A';
                $title = isset($_POST['prod_title']) ? $_POST['prod_title'] : 'N/A';
                $finalAmound = isset($_POST['final_amound']) ? $_POST['final_amound'] : 'N/A';
                $netto_price = $_POST['numm'];
                $vat_rate =  isset($_POST['vat_rate']) ? $_POST['vat_rate'] : 'N/A';
                $netto_price = isset($_POST['net_price']) ? $_POST['net_price'] : 'N/A';



                $new_post = array(
                    'post_title' =>  $title,
                    'post_status'   => 'private',
                    'post_type' => 'vat_calculations',
                    'meta_input' => array(
                        'brutto_price' => $finalAmound,
                        'netto_price' => $netto_price,
                        'vat' => $vat,
                        'vat_proc' => $vat_rate,
                        'ip' => getUserIpAddr(),
                    )
                );
                wp_insert_post($new_post, true);
            }


            die();
        }
    }
    $vatCounter = new VatCounter();

    // activation
    register_activation_hook(__FILE__, array($vatCounter, 'activate'));

    // deactivation
    require_once plugin_dir_path(__FILE__) . 'inc/vat-counter-deactivate.php';
    register_deactivation_hook(__FILE__, array('VatPluginDeactivate', 'deactivate'));
}
