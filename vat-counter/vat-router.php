<?php

/* file vat-router
@package vat-counter

*/

function vat_counter()
{
    register_rest_route('vat-counter/v1', 'manageVat', array(
        'methods' => 'POST',
        'callback' => 'vat_counter_data',
    ));
}

function vat_counter_data($data)
{

    if (
        // https://developer.wordpress.org/themes/theme-security/using-nonces/
        !isset($_POST['vat_calculations_nonce_field'])
        && !wp_verify_nonce($_POST['vat_calculations_nonce_field'], 'vat_calculations_nonce_action')
    ) {
        function get_user_Ip_addr()
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

        $parameters = $data->get_params();
        $new_post = array(
            'post_title' => $parameters['prod_title'],
            'post_status'   => 'private',
            'post_type' => 'vat_calculations',
            'meta_input' => array(
                'brutto_price' => '22',
                'netto_price' => $parameters['final_amound'],
                'vat' => $parameters['final_vat'],
                'vat_proc' => $parameters['vat_rate'],
                'ip' => get_user_Ip_addr(),
            )
        );
        wp_insert_post($new_post, true);
        var_dump($new_post);
    }
}
add_action('rest_api_init', 'vat_counter');
