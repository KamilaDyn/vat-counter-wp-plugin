<?php

/*
* file to register CPT
* @package vat-counter
*/

function vat_calculations_order_page()
{
    $labels = array(
        'name' => __('Vats Obliczenie', 'vat-counter'),
        'singular_name' => __('Vat obliczenie', 'vat-counter'),
        'menu_name' => __('Obliczenie Vat', 'vat-counter'),
        'name_admin_bar' => __('Obliczenie', 'vat-counter'),
        'add_new' => __('Dodaj', 'vat-counter'),
        'add_new_item' => __('Add New Vat', 'vat-counter'),
        'new_item' => __('Nowy Vat', 'vat-counter'),
        'edit_item' => __('Edit Vat', 'vat-counter'),
        'view_item' => __('View Vat', 'vat-counter'),
        'all_items' => __('Wszystkie', 'vat-counter'),
        'search_items' => __('Szukaj Vat', 'vat-counter'),
        'parent_item_colon' => __('Parent Orders:', 'vat-counter'),
        'not_found' => __('Nie znaleziono.', 'vat-counter'),
        'not_found_in_trash' => __('Nie znaleziono w koszu.', 'vat-counter')
    );


    // access to administrator screen options
    $capability = 'manage_options';
    $capabilities = array(
        'edit_post' => $capability,
        'read_post' => $capability,
        'delete_post' => $capability,
        'create_posts' => $capability,
        'edit_posts' => $capability,
        'edit_others_posts' => $capability,
        'publish_posts' => $capability,
        'read_private_posts' => $capability,
        'read' => $capability,
        'delete_posts' => $capability,
        'delete_private_posts' => $capability,
        'delete_published_posts' => $capability,
        'delete_others_posts' => $capability,
        'edit_private_posts' => $capability,
        'edit_published_posts' => $capability
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'show_in_menu' => current_user_can('manage_options') ? true : false,
        'query_var' => false,
        'rewrite' => false,
        'capabilities' => $capabilities,
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('editor', 'title'),
        'menu_icon' => 'dashicons-money-alt',
    );

    register_post_type('vat_calculations', $args);
}


/// create own table with columns

function vat_calculations_column($columns)
{
    unset($columns['title']);
    unset($columns['date']);
    $edit_columns = array(
        'cb' => '&lt;input type="checkbox" />',
        'title' => __('Nazwa Produktu', 'vat-counter'),
        'netto_price' =>  __('Kwota Netto', 'vat-counter'),
        'brutto_price' => __('Kwota Brutto', 'vat-counter'),
        'vat' => __('Vat', 'vat-counter'),
        'vat_proc' => __('Stawka Vat', 'vat-counter'),
        'ip' => __('Numer IP', 'vat-counter'),
        'date' => __('Data', 'vat-counter'),
    );

    return array_merge($columns, $edit_columns);
}



// meta boxes

function vat_calculations_order_meta_box($post)
{
    $brutto = esc_attr(get_post_meta(get_the_ID(), 'brutto_price', true));
    $vat = get_post_meta($post->ID, 'vat', true);
    $ip = get_post_meta($post->ID, 'ip', true);
    $netto_price  =  get_post_meta($post->ID, 'netto_price', true);
    $vat_proc  =  get_post_meta($post->ID, 'vat_proc', true);
    // Add an nonce field 
    wp_nonce_field('vat_counter_meta_box', 'vat_counter_meta_box_nonce');

?>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="vat"><?php _e('Kwota Netto', 'vat-counter'); ?></label></th>
                <td><input name="vat" type="text" id="vat" value="<?php echo $netto_price; ?>" class="regular-text">
                    <p class="description">Cena Produktu (netto)</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="brutto_price"><?php _e('Kwota Brutto', 'vat-counter'); ?></label></th>
                <td><input name="brutto_price" type="text" id="brutto_price" value="<?php echo $brutto; ?>" class="regular-text">
                    <p class="description">Cena Produktu (brutto)</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="netto_price"><?php _e('Kwota Podatku', 'vat-counter'); ?></label></th>
                <td><input name="netto_price" type="text" id="netto_price" value="<?php echo $vat ?>" class="regular-text">
                    <p class="description">Podatek vat</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="vat_proc"><?php _e('Stawka Vat', 'vat-counter'); ?></label></th>
                <td><input name="vat_proc" type="text" id="vat_proc" value="<?php echo $vat_proc ?>" class="regular-text">
                    <p class="description">Stawka Vat</p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="ip"><?php _e('IP Użytkownika', 'vat-counter'); ?></label></th>
                <td><input name="ip" type="text" id="ip" value="<?php echo $ip; ?>" class="regular-text">
                    <p class="description">IP</p>
                </td>
            </tr>
        </tbody>

    </table>

<?php
}

function my_manage_vat_calculation_columns($column, $post_id)
{
    switch ($column) {
        case 'title':
            echo $post_id;
            break;
        case 'netto_price':
            echo get_post_meta($post_id, 'netto_price', true);
            break;
        case 'brutto_price':
            echo get_post_meta($post_id, 'brutto_price', true);
            break;
        case 'vat':
            echo get_post_meta($post_id, 'vat', true);
            break;
        case 'vat_proc':
            echo get_post_meta($post_id, 'vat_proc', true);
            break;
        case 'ip':
            $ip = get_post_meta($post_id, 'ip', true);
            if (empty($ip)) {
                echo __('Unknown');
            } else {
                echo get_post_meta($post_id, 'ip', true);
            }
            break;
    }
}




function my_vat_calculations_meta_boxes()
{
    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */
    // Check if our nonce is set.
    if (!isset($_POST['vat_counter_meta_box_nonce'])) {
        return;
    }
    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['vat_counter_meta_box_nonce'], 'vat_counter_meta_box')) {
        return;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Users permision
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }
    $field_lists = [
        'brutto_price',
        'netto_price',
        'vat',
        'vat_proc',
        'ip',
    ];
    // doesn't update
    foreach ($field_lists as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'my_vat_calculations_meta_boxes');
