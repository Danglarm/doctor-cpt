<?php
/**
 * Plugin Name: Custom Post Type: Doctors
 * Plugin URI: https://sevastopol.hh.ru/resume/d291066aff0f02747a0039ed1f6b6a33334c6a
 * Description: Creates a custom post type for doctors with taxonomies and custom fields
 * Version: 1.0.0
 * Author: Danglarm
 * Author URI: https://sevastopol.hh.ru/resume/d291066aff0f02747a0039ed1f6b6a33334c6a
 * License: GPL v2 or later
 * Text Domain: doctors-cpt
 * Domain Path: /languages
 * 
 * @package DoctorsCPT
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DOCTORS_CPT_VERSION', '1.0.0');
define('DOCTORS_CPT_PATH', plugin_dir_path(__FILE__));
define('DOCTORS_CPT_URL', plugin_dir_url(__FILE__));

// Include installation script
require_once DOCTORS_CPT_PATH . 'install.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'DoctorsCPT\\';
    $base_dir = DOCTORS_CPT_PATH . 'includes/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . str_replace('_', '-', strtolower($relative_class)) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize the plugin
add_action('plugins_loaded', 'doctors_cpt_init');
function doctors_cpt_init() {
    // Load text domain
    load_plugin_textdomain(
        'doctors-cpt',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
    
    // Initialize components
    if (is_admin()) {
        new DoctorsCPT\Metaboxes();
    }
    
    new DoctorsCPT\Post_Type();
    new DoctorsCPT\Taxonomies();
    new DoctorsCPT\Archive_Filters();
    
    // Load template functions
    require_once DOCTORS_CPT_PATH . 'includes/template-functions.php';
    
    // Register templates
    add_filter('template_include', 'DoctorsCPT\template_loader');
}

// Add admin notice for first activation
add_action('admin_notices', 'doctors_cpt_admin_notice');
function doctors_cpt_admin_notice() {
    if (get_option('doctors_cpt_first_activation')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong>Doctors CPT Plugin activated!</strong>
                Demo data has been created. 
                <a href="<?php echo admin_url('edit.php?post_type=doctors'); ?>">View Doctors</a> | 
                <a href="<?php echo get_post_type_archive_link('doctors'); ?>" target="_blank">View Archive</a>
            </p>
        </div>
        <?php
        delete_option('doctors_cpt_first_activation');
    }
}

// Combined activation: set flag + schedule demo data creation after taxonomies are ready
register_activation_hook(__FILE__, function() {
    update_option('doctors_cpt_first_activation', '1');
    
    // Schedule one-time action on next 'init' (when taxonomies are registered)
    if (! wp_next_scheduled('doctors_cpt_create_demo_data')) {
        wp_schedule_single_event(time() + 5, 'doctors_cpt_create_demo_data');
    }
});

// Hook for scheduled demo data
add_action('doctors_cpt_create_demo_data', 'doctors_cpt_install');

// Register deactivation hook (flush rewrite rules)
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

// Register uninstall hook (cleanup)
register_uninstall_hook(__FILE__, 'doctors_cpt_uninstall');