<?php
namespace DoctorsCPT;

class Archive_Filters {
    
    public function __construct() {
        add_action('pre_get_posts', [$this, 'modify_archive_query']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('doctors_archive_filters', [$this, 'render_filters']);
    }
    
    public function modify_archive_query($query) {
        if (is_admin() || !$query->is_main_query() || !is_post_type_archive('doctors')) {
            return;
        }
        
        // Set posts per page
        $query->set('posts_per_page', 9);
        
        // Handle taxonomy filters
        $meta_query = [];
        
        if (!empty($_GET['specialization'])) {
            $specialization = sanitize_text_field($_GET['specialization']);
            $query->set('tax_query', [
                [
                    'taxonomy' => 'specialization',
                    'field'    => 'slug',
                    'terms'    => $specialization,
                ]
            ]);
        }
        
        if (!empty($_GET['city'])) {
            $city = sanitize_text_field($_GET['city']);
            $tax_query = $query->get('tax_query') ?: [];
            
            $tax_query[] = [
                'taxonomy' => 'city',
                'field'    => 'slug',
                'terms'    => $city,
            ];
            
            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }
            
            $query->set('tax_query', $tax_query);
        }
        
        // Handle sorting
        if (!empty($_GET['sort'])) {
            $sort = sanitize_text_field($_GET['sort']);
            
            switch ($sort) {
                case 'rating_desc':
                    $query->set('meta_key', '_rating');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                    break;
                    
                case 'price_asc':
                    $query->set('meta_key', '_price_from');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'ASC');
                    break;
                    
                case 'experience_desc':
                    $query->set('meta_key', '_experience');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                    break;
                    
                default:
                    $query->set('orderby', 'title');
                    $query->set('order', 'ASC');
            }
        }
        
        // Handle meta query for filters that require meta values
        if (!empty($meta_query)) {
            $query->set('meta_query', $meta_query);
        }
    }
    
    public function enqueue_scripts() {
        if (is_post_type_archive('doctors')) {
            wp_enqueue_style('doctors-archive', DOCTORS_CPT_URL . 'assets/css/archive.css', [], DOCTORS_CPT_VERSION);
        }
    }
    
    public function render_filters() {
        $current_specialization = isset($_GET['specialization']) ? sanitize_text_field($_GET['specialization']) : '';
        $current_city = isset($_GET['city']) ? sanitize_text_field($_GET['city']) : '';
        $current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
        
        // Get all terms for dropdowns
        $specializations = get_terms([
            'taxonomy' => 'specialization',
            'hide_empty' => true,
            'orderby' => 'name',
        ]);
        
        $cities = get_terms([
            'taxonomy' => 'city',
            'hide_empty' => true,
            'orderby' => 'name',
        ]);
        ?>
        
        <div class="doctors-filters">
            <form method="GET" action="<?php echo esc_url(get_post_type_archive_link('doctors')); ?>" class="filter-form">
                
                <div class="filter-group">
                    <label for="specialization"><?php _e('Specialization:', 'doctors-cpt'); ?></label>
                    <select name="specialization" id="specialization">
                        <option value=""><?php _e('All Specializations', 'doctors-cpt'); ?></option>
                        <?php foreach ($specializations as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>" 
                                <?php selected($current_specialization, $term->slug); ?>>
                                <?php echo esc_html($term->name); ?>
                                <?php if ($term->count): ?>
                                    (<?php echo absint($term->count); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="city"><?php _e('City:', 'doctors-cpt'); ?></label>
                    <select name="city" id="city">
                        <option value=""><?php _e('All Cities', 'doctors-cpt'); ?></option>
                        <?php foreach ($cities as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>" 
                                <?php selected($current_city, $term->slug); ?>>
                                <?php echo esc_html($term->name); ?>
                                <?php if ($term->count): ?>
                                    (<?php echo absint($term->count); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sort"><?php _e('Sort by:', 'doctors-cpt'); ?></label>
                    <select name="sort" id="sort">
                        <option value=""><?php _e('Default', 'doctors-cpt'); ?></option>
                        <option value="rating_desc" <?php selected($current_sort, 'rating_desc'); ?>>
                            <?php _e('Rating (High to Low)', 'doctors-cpt'); ?>
                        </option>
                        <option value="price_asc" <?php selected($current_sort, 'price_asc'); ?>>
                            <?php _e('Price (Low to High)', 'doctors-cpt'); ?>
                        </option>
                        <option value="experience_desc" <?php selected($current_sort, 'experience_desc'); ?>>
                            <?php _e('Experience (High to Low)', 'doctors-cpt'); ?>
                        </option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="filter-button">
                        <?php _e('Apply Filters', 'doctors-cpt'); ?>
                    </button>
                    <a href="<?php echo esc_url(get_post_type_archive_link('doctors')); ?>" class="reset-button">
                        <?php _e('Reset', 'doctors-cpt'); ?>
                    </a>
                </div>
                
                <!-- Keep existing pagination parameter -->
                <?php if (get_query_var('paged')): ?>
                    <input type="hidden" name="paged" value="<?php echo absint(get_query_var('paged')); ?>">
                <?php endif; ?>
                
            </form>
        </div>
        
        <style>
        .doctors-filters {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-end;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }
        .filter-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background: white;
            font-size: 14px;
        }
        .filter-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .filter-button, .reset-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .filter-button {
            background: #007cba;
            color: white;
        }
        .filter-button:hover {
            background: #005a87;
            color: white;
        }
        .reset-button {
            background: #6c757d;
            color: white;
            display: inline-block;
        }
        .reset-button:hover {
            background: #545b62;
            color: white;
        }
        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
            }
            .filter-group {
                width: 100%;
            }
        }
        </style>
        
        <?php
    }
}