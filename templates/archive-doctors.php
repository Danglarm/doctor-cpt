<?php
/**
 * Archive Doctors Template
 *
 * @package DoctorsCPT
 */

get_header(); ?>

<div class="container doctors-archive">
    
    <header class="page-header">
        <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
        <?php if (get_the_archive_description()) : ?>
            <div class="archive-description"><?php the_archive_description(); ?></div>
        <?php endif; ?>
    </header>
    
    <?php do_action('doctors_archive_filters'); ?>
    
    <?php if (have_posts()) : ?>
        
        <div class="doctors-grid">
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('doctor-card'); ?>>
                    
                    <div class="doctor-card-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', ['class' => 'doctor-thumbnail']); ?>
                            <?php else : ?>
                                <div class="doctor-thumbnail-placeholder">
                                    <span class="dashicons dashicons-businessperson"></span>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <div class="doctor-card-content">
                        <h2 class="doctor-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <?php
                        // Get specializations (limit to 2)
                        $specializations = DoctorsCPT\get_doctor_specializations();
                        if ($specializations && !is_wp_error($specializations)) :
                            $specializations = array_slice($specializations, 0, 2);
                        ?>
                            <div class="doctor-card-specialization">
                                <?php
                                $spec_names = [];
                                foreach ($specializations as $term) {
                                    $spec_names[] = esc_html($term->name);
                                }
                                echo implode(', ', $spec_names);
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="doctor-card-meta">
                            <div class="meta-item">
                                <span class="meta-label"><?php _e('Experience:', 'doctors-cpt'); ?></span>
                                <span class="meta-value">
                                    <?php echo DoctorsCPT\get_doctor_experience(); ?> <?php _e('years', 'doctors-cpt'); ?>
                                </span>
                            </div>
                            
                            <div class="meta-item">
                                <span class="meta-label"><?php _e('Price from:', 'doctors-cpt'); ?></span>
                                <span class="meta-value">
                                    $<?php echo DoctorsCPT\get_doctor_price(); ?>
                                </span>
                            </div>
                            
                            <div class="meta-item rating">
                                <span class="meta-label"><?php _e('Rating:', 'doctors-cpt'); ?></span>
                                <span class="meta-value">
                                    <?php
                                    $rating = DoctorsCPT\get_doctor_rating();
                                    DoctorsCPT\display_doctor_rating($rating, false);
                                    ?>
                                    <span class="rating-number"><?php echo number_format($rating, 1); ?></span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="doctor-card-link">
                            <a href="<?php the_permalink(); ?>" class="view-details">
                                <?php _e('View Details', 'doctors-cpt'); ?>
                            </a>
                        </div>
                    </div>
                    
                </article>
                
            <?php endwhile; ?>
        </div>
        
        <?php
        // Pagination
        the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => __('&larr; Previous', 'doctors-cpt'),
            'next_text' => __('Next &rarr;', 'doctors-cpt'),
        ]);
        ?>
        
    <?php else : ?>
        
        <div class="no-doctors-found">
            <p><?php _e('No doctors found.', 'doctors-cpt'); ?></p>
            
            <?php
            // Check if filters are active
            if (!empty($_GET['specialization']) || !empty($_GET['city']) || !empty($_GET['sort'])) : ?>
                <p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('doctors')); ?>" class="clear-filters">
                        <?php _e('Clear filters and show all doctors', 'doctors-cpt'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
        
    <?php endif; ?>
    
</div>

<style>
.doctors-archive {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 20px;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-title {
    color: #2c3e50;
    font-size: 2.5em;
    margin-bottom: 20px;
}

.archive-description {
    color: #7f8c8d;
    font-size: 1.1em;
    max-width: 800px;
    margin: 0 auto;
}

.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.doctor-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.doctor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}

.doctor-card-image {
    height: 200px;
    overflow: hidden;
}

.doctor-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.doctor-card:hover .doctor-thumbnail {
    transform: scale(1.05);
}

.doctor-thumbnail-placeholder {
    width: 100%;
    height: 100%;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.doctor-thumbnail-placeholder .dashicons {
    font-size: 80px;
    width: 80px;
    height: 80px;
    color: #ccc;
}

.doctor-card-content {
    padding: 25px;
}

.doctor-card-title {
    margin: 0 0 15px 0;
    font-size: 1.3em;
    line-height: 1.4;
}

.doctor-card-title a {
    color: #2c3e50;
    text-decoration: none;
}

.doctor-card-title a:hover {
    color: #3498db;
}

.doctor-card-specialization {
    color: #3498db;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 0.95em;
    display: flex;
    align-items: center;
    gap: 5px;
}

.doctor-card-specialization::before {
    content: '👨‍⚕️';
    font-size: 1.2em;
}

.doctor-card-meta {
    margin-bottom: 20px;
}

.doctor-card-meta .meta-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    font-size: 0.9em;
}

.doctor-card-meta .meta-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.doctor-card-meta .meta-label {
    color: #7f8c8d;
    font-weight: 500;
}

.doctor-card-meta .meta-value {
    color: #2c3e50;
    font-weight: 600;
}

.doctor-card-meta .rating .meta-value {
    display: flex;
    align-items: center;
    gap: 10px;
}

.doctor-card-meta .stars {
    color: #ffd700;
    font-size: 16px;
}

.doctor-card-meta .stars .star.filled {
    color: #ffb900;
}

.doctor-card-meta .stars .star.half {
    background: linear-gradient(90deg, #ffb900 50%, #ddd 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.doctor-card-meta .rating-number {
    color: #f39c12;
    font-weight: bold;
}

.doctor-card-link {
    text-align: center;
}

.view-details {
    display: inline-block;
    background: #3498db;
    color: white;
    text-decoration: none;
    padding: 10px 25px;
    border-radius: 4px;
    font-weight: 600;
    transition: background 0.3s ease;
}

.view-details:hover {
    background: #2980b9;
    color: white;
}

.no-doctors-found {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.no-doctors-found p {
    font-size: 1.2em;
    color: #6c757d;
    margin-bottom: 20px;
}

.clear-filters {
    color: #3498db;
    text-decoration: none;
    font-weight: 600;
}

.clear-filters:hover {
    text-decoration: underline;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 40px;
}

.page-numbers {
    padding: 10px 15px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    text-decoration: none;
    color: #3498db;
}

.page-numbers.current {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.page-numbers:hover:not(.current) {
    background: #f8f9fa;
}

.page-numbers.dots {
    border: none;
    color: #6c757d;
}

@media (max-width: 768px) {
    .doctors-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .page-title {
        font-size: 2em;
    }
    
    .doctor-card-content {
        padding: 20px;
    }
}
</style>

<?php get_footer(); ?>