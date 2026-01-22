<?php
/**
 * Uninstall script for Doctors CPT Plugin
 * 
 * This file is executed when the plugin is deleted
 * WARNING: This will delete all doctors data!
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Security check
if (!current_user_can('activate_plugins')) {
    exit;
}

// Option: Remove all plugin data on uninstall
$remove_data = true; // Set to false if you want to keep data

if ($remove_data) {
    global $wpdb;
    
    // Delete all doctors posts
    $doctors = get_posts([
        'post_type' => 'doctors',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);
    
    foreach ($doctors as $doctor_id) {
        wp_delete_post($doctor_id, true);
    }
    
    // Delete plugin options
    delete_option('doctors_demo_data_created');
    delete_option('doctors_cpt_first_activation');
    
    // Delete custom tables if any were created
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}doctors_custom_table");
    
    // Clear any transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_doctors_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_doctors_%'");
}

// Note: We don't delete taxonomies because they might be used by other plugins
// If you want to delete them:
// $terms = get_terms(['taxonomy' => ['specialization', 'city'], 'hide_empty' => false]);
// foreach ($terms as $term) {
//     wp_delete_term($term->term_id, $term->taxonomy);
// }