<?php
/*
* file to uninstall plugin
* @package vat-counter
*/

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// clean DB via sql

global $wpdb;
$table_post_meta = $wpdb->prefix . 'postmeta';
$table_posts = $wpdb->prefix . 'posts';

$wpdb->query("DELETE FROM $table_posts WHERE post_type = 'vat_calculations'");
$wpdb->query("DELETE FROM $table_post_meta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
