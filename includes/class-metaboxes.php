<?php
namespace DoctorsCPT;

class Metaboxes {
    
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_metaboxes']);
        add_action('save_post_doctors', [$this, 'save_metaboxes']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }
    
    public function add_metaboxes() {
        add_meta_box(
            'doctor_details',
            __('Doctor Details', 'doctors-cpt'),
            [$this, 'render_doctor_details_metabox'],
            'doctors',
            'normal',
            'high'
        );
    }
    
    public function render_doctor_details_metabox($post) {
        wp_nonce_field('doctor_details_nonce', 'doctor_details_nonce_field');
        
        $experience = get_post_meta($post->ID, '_experience', true);
        $price_from = get_post_meta($post->ID, '_price_from', true);
        $rating = get_post_meta($post->ID, '_rating', true);
        ?>
        <div class="doctor-metabox">
            <div class="form-field">
                <label for="doctor_experience"><?php _e('Experience (years)', 'doctors-cpt'); ?></label>
                <input type="number" 
                       id="doctor_experience" 
                       name="doctor_experience" 
                       value="<?php echo esc_attr($experience); ?>" 
                       min="0" 
                       max="50"
                       step="1">
                <p class="description"><?php _e('Number of years of experience', 'doctors-cpt'); ?></p>
            </div>
            
            <div class="form-field">
                <label for="doctor_price_from"><?php _e('Price From ($)', 'doctors-cpt'); ?></label>
                <input type="number" 
                       id="doctor_price_from" 
                       name="doctor_price_from" 
                       value="<?php echo esc_attr($price_from); ?>" 
                       min="0" 
                       step="10">
                <p class="description"><?php _e('Starting consultation price', 'doctors-cpt'); ?></p>
            </div>
            
            <div class="form-field">
                <label for="doctor_rating"><?php _e('Rating (0-5)', 'doctors-cpt'); ?></label>
                <input type="number" 
                       id="doctor_rating" 
                       name="doctor_rating" 
                       value="<?php echo esc_attr($rating); ?>" 
                       min="0" 
                       max="5" 
                       step="0.1">
                <p class="description"><?php _e('Rating from 0 to 5 (can use decimals like 4.5)', 'doctors-cpt'); ?></p>
                
                <div class="rating-preview">
                    <strong><?php _e('Preview:', 'doctors-cpt'); ?></strong>
                    <span class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo ($rating && $i <= round($rating)) ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </span>
                    <span class="rating-value"><?php echo $rating ? number_format($rating, 1) : '0.0'; ?></span>
                </div>
            </div>
        </div>
        
        <style>
        .doctor-metabox .form-field {
            margin: 1em 0;
        }
        .doctor-metabox label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .doctor-metabox input[type="number"] {
            width: 100%;
            max-width: 300px;
            padding: 5px;
        }
        .doctor-metabox .description {
            margin-top: 5px;
            color: #666;
            font-style: italic;
        }
        .rating-preview {
            margin-top: 10px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 4px;
        }
        .rating-preview .stars {
            font-size: 20px;
            color: #ddd;
            margin: 0 10px;
        }
        .rating-preview .stars .star.filled {
            color: #ffb900;
        }
        .rating-preview .rating-value {
            font-weight: bold;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('#doctor_rating').on('input', function() {
                var rating = parseFloat($(this).val()) || 0;
                rating = Math.min(5, Math.max(0, rating));
                
                $('.rating-preview .star').each(function(index) {
                    if (index < Math.round(rating)) {
                        $(this).addClass('filled');
                    } else {
                        $(this).removeClass('filled');
                    }
                });
                
                $('.rating-preview .rating-value').text(rating.toFixed(1));
            });
        });
        </script>
        <?php
    }
    
    public function save_metaboxes($post_id) {
        // Check nonce
        if (!isset($_POST['doctor_details_nonce_field']) || 
            !wp_verify_nonce($_POST['doctor_details_nonce_field'], 'doctor_details_nonce')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Sanitize and save fields
        if (isset($_POST['doctor_experience'])) {
            $experience = absint($_POST['doctor_experience']);
            update_post_meta($post_id, '_experience', $experience);
        }
        
        if (isset($_POST['doctor_price_from'])) {
            $price_from = absint($_POST['doctor_price_from']);
            update_post_meta($post_id, '_price_from', $price_from);
        }
        
        if (isset($_POST['doctor_rating'])) {
            $rating = floatval($_POST['doctor_rating']);
            $rating = max(0, min(5, $rating));
            update_post_meta($post_id, '_rating', $rating);
        }
    }
    
    public function enqueue_admin_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        global $post_type;
        if ('doctors' !== $post_type) {
            return;
        }
        
        wp_enqueue_style('doctors-cpt-admin', DOCTORS_CPT_URL . 'assets/css/admin.css', [], DOCTORS_CPT_VERSION);
    }
}