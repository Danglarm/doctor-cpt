<?php
/**
 * Single Doctor Template
 *
 * @package DoctorsCPT
 */

get_header(); ?>

<div class="container doctor-single">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('doctor-detail'); ?>>
            
            <header class="doctor-header">
                <div class="doctor-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', ['class' => 'doctor-photo']); ?>
                    <?php else : ?>
                        <div class="doctor-photo-placeholder">
                            <span class="dashicons dashicons-businessperson"></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="doctor-info">
                    <h1 class="doctor-title"><?php the_title(); ?></h1>
                    
                    <?php
                    // Display taxonomies
                    $specializations = DoctorsCPT\get_doctor_specializations();
                    $cities = DoctorsCPT\get_doctor_cities();
                    ?>
                    
                    <div class="doctor-taxonomies">
                        <?php if ($specializations && !is_wp_error($specializations)) : ?>
                            <div class="taxonomy specialization">
                                <strong><?php _e('Specialization:', 'doctors-cpt'); ?></strong>
                                <?php
                                $specialization_names = [];
                                foreach ($specializations as $term) {
                                    $specialization_names[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                                }
                                echo implode(', ', $specialization_names);
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($cities && !is_wp_error($cities)) : ?>
                            <div class="taxonomy city">
                                <strong><?php _e('City:', 'doctors-cpt'); ?></strong>
                                <?php
                                $city_names = [];
                                foreach ($cities as $term) {
                                    $city_names[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                                }
                                echo implode(', ', $city_names);
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php DoctorsCPT\display_doctor_meta(); ?>
                </div>
            </header>
            
            <div class="doctor-content">
                <?php if (has_excerpt()) : ?>
                    <div class="doctor-excerpt">
                        <h2><?php _e('About Doctor', 'doctors-cpt'); ?></h2>
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
                
                <div class="doctor-full-content">
                    <h2><?php _e('Detailed Information', 'doctors-cpt'); ?></h2>
                    <?php the_content(); ?>
                </div>
            </div>
            
            <footer class="doctor-footer">
                <a href="<?php echo esc_url(get_post_type_archive_link('doctors')); ?>" class="back-to-archive">
                    &larr; <?php _e('Back to All Doctors', 'doctors-cpt'); ?>
                </a>
            </footer>
            
        </article>
        
    <?php endwhile; ?>
    
</div>

<style>
.doctor-single {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.doctor-detail {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 40px;
}

.doctor-header {
    display: flex;
    gap: 40px;
    margin-bottom: 40px;
    align-items: flex-start;
}

.doctor-image {
    flex: 0 0 300px;
}

.doctor-photo {
    width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
}

.doctor-photo-placeholder {
    width: 300px;
    height: 300px;
    background: #f5f5f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.doctor-photo-placeholder .dashicons {
    font-size: 150px;
    width: 150px;
    height: 150px;
    color: #ccc;
}

.doctor-info {
    flex: 1;
}

.doctor-title {
    margin-top: 0;
    margin-bottom: 20px;
    color: #2c3e50;
    font-size: 2em;
}

.doctor-taxonomies {
    margin-bottom: 30px;
}

.doctor-taxonomies .taxonomy {
    margin-bottom: 15px;
    font-size: 1.1em;
}

.doctor-taxonomies strong {
    color: #34495e;
    margin-right: 10px;
}

.doctor-taxonomies a {
    color: #3498db;
    text-decoration: none;
}

.doctor-taxonomies a:hover {
    text-decoration: underline;
}

.doctor-meta {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.doctor-meta .meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 1.1em;
}

.doctor-meta .meta-item:last-child {
    margin-bottom: 0;
}

.doctor-meta .label {
    font-weight: 600;
    color: #495057;
    min-width: 120px;
}

.doctor-meta .value {
    color: #212529;
}

.doctor-rating .stars {
    color: #ffd700;
    font-size: 20px;
    margin-right: 10px;
}

.doctor-rating .stars .star.filled {
    color: #ffb900;
}

.doctor-rating .stars .star.half {
    background: linear-gradient(90deg, #ffb900 50%, #ddd 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.doctor-content {
    margin-top: 40px;
}

.doctor-excerpt,
.doctor-full-content {
    margin-bottom: 30px;
}

.doctor-excerpt h2,
.doctor-full-content h2 {
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

.doctor-excerpt p {
    font-size: 1.1em;
    line-height: 1.6;
    color: #555;
}

.doctor-full-content {
    line-height: 1.8;
    color: #333;
}

.doctor-footer {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.back-to-archive {
    display: inline-block;
    color: #3498db;
    text-decoration: none;
    font-weight: 600;
}

.back-to-archive:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .doctor-header {
        flex-direction: column;
        gap: 20px;
    }
    
    .doctor-image {
        flex: 0 0 auto;
        text-align: center;
    }
    
    .doctor-photo-placeholder {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .doctor-title {
        font-size: 1.5em;
    }
    
    .doctor-meta .meta-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .doctor-meta .label {
        margin-bottom: 5px;
        min-width: auto;
    }
}
</style>

<?php get_footer(); ?>