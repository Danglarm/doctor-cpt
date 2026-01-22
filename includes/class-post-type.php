<?php
namespace DoctorsCPT;

class Post_Type {
    
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_meta_fields']);
        add_filter('manage_doctors_posts_columns', [$this, 'add_admin_columns']);
        add_action('manage_doctors_posts_custom_column', [$this, 'render_admin_columns'], 10, 2);
        add_filter('manage_edit-doctors_sortable_columns', [$this, 'make_columns_sortable']);
        add_action('pre_get_posts', [$this, 'handle_custom_sorting']);
    }
    
    public function register_post_type() {
        $labels = [
            'name'                  => _x('Doctors', 'Post Type General Name', 'doctors-cpt'),
            'singular_name'         => _x('Doctor', 'Post Type Singular Name', 'doctors-cpt'),
            'menu_name'             => __('Doctors', 'doctors-cpt'),
            'name_admin_bar'        => __('Doctor', 'doctors-cpt'),
            'archives'              => __('Doctor Archives', 'doctors-cpt'),
            'attributes'            => __('Doctor Attributes', 'doctors-cpt'),
            'parent_item_colon'     => __('Parent Doctor:', 'doctors-cpt'),
            'all_items'             => __('All Doctors', 'doctors-cpt'),
            'add_new_item'          => __('Add New Doctor', 'doctors-cpt'),
            'add_new'               => __('Add New', 'doctors-cpt'),
            'new_item'              => __('New Doctor', 'doctors-cpt'),
            'edit_item'             => __('Edit Doctor', 'doctors-cpt'),
            'update_item'           => __('Update Doctor', 'doctors-cpt'),
            'view_item'             => __('View Doctor', 'doctors-cpt'),
            'view_items'            => __('View Doctors', 'doctors-cpt'),
            'search_items'          => __('Search Doctor', 'doctors-cpt'),
            'not_found'             => __('Not found', 'doctors-cpt'),
            'not_found_in_trash'    => __('Not found in Trash', 'doctors-cpt'),
            'featured_image'        => __('Doctor Photo', 'doctors-cpt'),
            'set_featured_image'    => __('Set doctor photo', 'doctors-cpt'),
            'remove_featured_image' => __('Remove doctor photo', 'doctors-cpt'),
            'use_featured_image'    => __('Use as doctor photo', 'doctors-cpt'),
            'insert_into_item'      => __('Insert into doctor', 'doctors-cpt'),
            'uploaded_to_this_item' => __('Uploaded to this doctor', 'doctors-cpt'),
            'items_list'            => __('Doctors list', 'doctors-cpt'),
            'items_list_navigation' => __('Doctors list navigation', 'doctors-cpt'),
            'filter_items_list'     => __('Filter doctors list', 'doctors-cpt'),
        ];
        
        $args = [
            'label'               => __('Doctor', 'doctors-cpt'),
            'description'         => __('Doctor post type', 'doctors-cpt'),
            'labels'              => $labels,
            'supports'            => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
            'taxonomies'          => ['specialization', 'city'],
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-businessperson',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'archive_slug'        => 'doctors',
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rewrite'             => ['slug' => 'doctors', 'with_front' => false],
        ];
        
        register_post_type('doctors', $args);
    }
    
    public function register_meta_fields() {
        register_post_meta('doctors', '_experience', [
            'type' => 'integer',
            'description' => 'Doctor experience in years',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'absint',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ]);
        
        register_post_meta('doctors', '_price_from', [
            'type' => 'integer',
            'description' => 'Price from',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'absint',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ]);
        
        register_post_meta('doctors', '_rating', [
            'type' => 'number',
            'description' => 'Rating from 0 to 5',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => function($value) {
                $value = floatval($value);
                return max(0, min(5, $value));
            },
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ]);
    }
    
    public function add_admin_columns($columns) {
        $columns = [
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'thumbnail' => __('Photo', 'doctors-cpt'),
            'specialization' => __('Specialization', 'doctors-cpt'),
            'city' => __('City', 'doctors-cpt'),
            'experience' => __('Experience', 'doctors-cpt'),
            'price_from' => __('Price From', 'doctors-cpt'),
            'rating' => __('Rating', 'doctors-cpt'),
            'date' => $columns['date']
        ];
        return $columns;
    }
    
    public function render_admin_columns($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    the_post_thumbnail('thumbnail', ['style' => 'max-width: 80px; height: auto;']);
                } else {
                    echo '<span class="dashicons dashicons-businessperson" style="font-size: 80px;"></span>';
                }
                break;
                
            case 'specialization':
                $terms = get_the_terms($post_id, 'specialization');
                if ($terms && !is_wp_error($terms)) {
                    $term_names = wp_list_pluck($terms, 'name');
                    echo esc_html(implode(', ', $term_names));
                }
                break;
                
            case 'city':
                $terms = get_the_terms($post_id, 'city');
                if ($terms && !is_wp_error($terms)) {
                    $term_names = wp_list_pluck($terms, 'name');
                    echo esc_html(implode(', ', $term_names));
                }
                break;
                
            case 'experience':
                $experience = get_post_meta($post_id, '_experience', true);
                echo $experience ? absint($experience) . ' ' . __('years', 'doctors-cpt') : '—';
                break;
                
            case 'price_from':
                $price = get_post_meta($post_id, '_price_from', true);
                echo $price ? '$' . absint($price) : '—';
                break;
                
            case 'rating':
                $rating = get_post_meta($post_id, '_rating', true);
                if ($rating) {
                    echo '<div class="doctor-rating">';
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= round($rating) ? '★' : '☆';
                    }
                    echo ' (' . number_format($rating, 1) . ')';
                    echo '</div>';
                } else {
                    echo '—';
                }
                break;
        }
    }
    
    public function make_columns_sortable($columns) {
        $columns['experience'] = 'experience';
        $columns['price_from'] = 'price_from';
        $columns['rating'] = 'rating';
        return $columns;
    }
    
    public function handle_custom_sorting($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        
        $orderby = $query->get('orderby');
        
        switch ($orderby) {
            case 'experience':
                $query->set('meta_key', '_experience');
                $query->set('orderby', 'meta_value_num');
                break;
                
            case 'price_from':
                $query->set('meta_key', '_price_from');
                $query->set('orderby', 'meta_value_num');
                break;
                
            case 'rating':
                $query->set('meta_key', '_rating');
                $query->set('orderby', 'meta_value_num');
                break;
        }
    }
}