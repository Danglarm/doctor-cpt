<?php
namespace DoctorsCPT;

function template_loader($template) {
    if (is_singular('doctors')) {
        $template = locate_template(['single-doctors.php']);
        if (!$template) {
            $template = DOCTORS_CPT_PATH . 'templates/single-doctors.php';
        }
    } elseif (is_post_type_archive('doctors')) {
        $template = locate_template(['archive-doctors.php']);
        if (!$template) {
            $template = DOCTORS_CPT_PATH . 'templates/archive-doctors.php';
        }
    }
    return $template;
}

function get_doctor_experience($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $experience = get_post_meta($post_id, '_experience', true);
    return $experience ? absint($experience) : 0;
}

function get_doctor_price($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $price = get_post_meta($post_id, '_price_from', true);
    return $price ? absint($price) : 0;
}

function get_doctor_rating($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $rating = get_post_meta($post_id, '_rating', true);
    return $rating ? floatval($rating) : 0;
}

function display_doctor_rating($rating, $show_value = true) {
    $full_stars = floor($rating);
    $has_half_star = ($rating - $full_stars) >= 0.5;
    
    echo '<div class="doctor-rating">';
    echo '<span class="stars">';
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $full_stars) {
            echo '<span class="star filled">★</span>';
        } elseif ($has_half_star && $i == $full_stars + 1) {
            echo '<span class="star half">★</span>';
        } else {
            echo '<span class="star">☆</span>';
        }
    }
    
    echo '</span>';
    
    if ($show_value) {
        echo '<span class="rating-value">' . number_format($rating, 1) . '</span>';
    }
    
    echo '</div>';
}

function get_doctor_specializations($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    return get_the_terms($post_id, 'specialization');
}

function get_doctor_cities($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    return get_the_terms($post_id, 'city');
}

function display_doctor_meta($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    ?>
    <div class="doctor-meta">
        <?php
        $experience = get_doctor_experience($post_id);
        if ($experience): ?>
            <div class="meta-item experience">
                <span class="label"><?php _e('Experience:', 'doctors-cpt'); ?></span>
                <span class="value"><?php echo sprintf(_n('%d year', '%d years', $experience, 'doctors-cpt'), $experience); ?></span>
            </div>
        <?php endif; ?>
        
        <?php
        $price = get_doctor_price($post_id);
        if ($price): ?>
            <div class="meta-item price">
                <span class="label"><?php _e('Price from:', 'doctors-cpt'); ?></span>
                <span class="value">$<?php echo esc_html($price); ?></span>
            </div>
        <?php endif; ?>
        
        <?php
        $rating = get_doctor_rating($post_id);
        if ($rating): ?>
            <div class="meta-item rating">
                <span class="label"><?php _e('Rating:', 'doctors-cpt'); ?></span>
                <span class="value">
                    <?php display_doctor_rating($rating); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
    <?php
}