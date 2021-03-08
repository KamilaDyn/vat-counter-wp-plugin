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
$tablePostMeta = $wpdb->prefix . 'postmeta';
$tablePosts = $wpdb->prefix . 'posts';

$wpdb->query("DELETE FROM $tablePosts WHERE post_type = 'vat_calculations'");
$wpdb->query("DELETE FROM $tablePostMeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
