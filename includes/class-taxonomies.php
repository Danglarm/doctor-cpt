<?php
namespace DoctorsCPT;

class Taxonomies {
    
    public function __construct() {
        add_action('init', [$this, 'register_taxonomies']);
    }
    
    public function register_taxonomies() {
        // Specialization - hierarchical (like categories)
        // Я выбрал hierarchical, потому что специализации могут иметь иерархию
        // (например: Хирургия → Кардиохирургия → Детская кардиохирургия)
        $labels_spec = [
            'name'              => _x('Specializations', 'Taxonomy General Name', 'doctors-cpt'),
            'singular_name'     => _x('Specialization', 'Taxonomy Singular Name', 'doctors-cpt'),
            'menu_name'         => __('Specializations', 'doctors-cpt'),
            'all_items'         => __('All Specializations', 'doctors-cpt'),
            'parent_item'       => __('Parent Specialization', 'doctors-cpt'),
            'parent_item_colon' => __('Parent Specialization:', 'doctors-cpt'),
            'new_item_name'     => __('New Specialization Name', 'doctors-cpt'),
            'add_new_item'      => __('Add New Specialization', 'doctors-cpt'),
            'edit_item'         => __('Edit Specialization', 'doctors-cpt'),
            'update_item'       => __('Update Specialization', 'doctors-cpt'),
            'view_item'         => __('View Specialization', 'doctors-cpt'),
            'separate_items_with_commas' => __('Separate specializations with commas', 'doctors-cpt'),
            'add_or_remove_items'        => __('Add or remove specializations', 'doctors-cpt'),
            'choose_from_most_used'      => __('Choose from the most used', 'doctors-cpt'),
            'popular_items'              => __('Popular Specializations', 'doctors-cpt'),
            'search_items'               => __('Search Specializations', 'doctors-cpt'),
            'not_found'                  => __('Not Found', 'doctors-cpt'),
            'no_terms'                   => __('No specializations', 'doctors-cpt'),
            'items_list'                 => __('Specializations list', 'doctors-cpt'),
            'items_list_navigation'      => __('Specializations list navigation', 'doctors-cpt'),
        ];
        
        $args_spec = [
            'labels'            => $labels_spec,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,
            'rewrite'           => ['slug' => 'specialization'],
        ];
        
        register_taxonomy('specialization', ['doctors'], $args_spec);
        
        // City - non-hierarchical (like tags)
        // Я выбрал non-hierarchical, потому что города обычно не имеют иерархии
        // (хотя в некоторых случаях можно было бы сделать hierarchical для стран/регионов/городов,
        // но для простоты оставляем как теги)
        $labels_city = [
            'name'              => _x('Cities', 'Taxonomy General Name', 'doctors-cpt'),
            'singular_name'     => _x('City', 'Taxonomy Singular Name', 'doctors-cpt'),
            'menu_name'         => __('Cities', 'doctors-cpt'),
            'all_items'         => __('All Cities', 'doctors-cpt'),
            'new_item_name'     => __('New City Name', 'doctors-cpt'),
            'add_new_item'      => __('Add New City', 'doctors-cpt'),
            'edit_item'         => __('Edit City', 'doctors-cpt'),
            'update_item'       => __('Update City', 'doctors-cpt'),
            'view_item'         => __('View City', 'doctors-cpt'),
            'separate_items_with_commas' => __('Separate cities with commas', 'doctors-cpt'),
            'add_or_remove_items'        => __('Add or remove cities', 'doctors-cpt'),
            'choose_from_most_used'      => __('Choose from the most used', 'doctors-cpt'),
            'popular_items'              => __('Popular Cities', 'doctors-cpt'),
            'search_items'               => __('Search Cities', 'doctors-cpt'),
            'not_found'                  => __('Not Found', 'doctors-cpt'),
            'no_terms'                   => __('No cities', 'doctors-cpt'),
            'items_list'                 => __('Cities list', 'doctors-cpt'),
            'items_list_navigation'      => __('Cities list navigation', 'doctors-cpt'),
        ];
        
        $args_city = [
            'labels'            => $labels_city,
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,
            'rewrite'           => ['slug' => 'city'],
        ];
        
        register_taxonomy('city', ['doctors'], $args_city);
    }
}