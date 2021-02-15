<?php
/*
* file to unistall plugin
* @packgage vat-counter
*/

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// clean DB via sql

global $wpdb;

$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'vat_calculations'");
$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
$wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");
