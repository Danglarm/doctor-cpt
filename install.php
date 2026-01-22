<?php
/**
 * Installation script for Doctors CPT Plugin
 * Runs when plugin is activated
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create demo data on plugin activation
 */
function doctors_cpt_install() {
    error_log('===== doctors_cpt_install() STARTED =====');
    global $wpdb;
    
    // Check if demo data already exists
    if (get_option('doctors_demo_data_created')) {
        error_log('Demo data already exists - skipping');
        return;
    }
    
    // Get table prefix
    $prefix = $wpdb->prefix;
    
    error_log('Creating specializations...');
    // 1. Create specializations
    $specializations = [
        'cardiology' => 'Кардиология',
        'neurology' => 'Неврология',
        'surgery' => 'Хирургия',
        'pediatrics' => 'Педиатрия',
        'dentistry' => 'Стоматология',
        'orthopedics' => 'Ортопедия',
        'ophthalmology' => 'Офтальмология',
        'dermatology' => 'Дерматология',
    ];
    
    $spec_ids = [];
    foreach ($specializations as $slug => $name) {
        $term = term_exists($name, 'specialization');
        if (!$term) {
            $term = wp_insert_term($name, 'specialization', ['slug' => $slug]);
        }
        if (is_wp_error($term)) {
            error_log('Ошибка создания специализации "' . $name . '": ' . $term->get_error_message());
        } else {
            $spec_ids[$slug] = is_array($term) ? $term['term_id'] : $term;
            error_log('Создана специализация: ' . $name . ' (ID: ' . $spec_ids[$slug] . ')');
        }
    }
    
    error_log('Creating cities...');
    // 2. Create cities
    $cities = [
        'moscow' => 'Москва',
        'saint-petersburg' => 'Санкт-Петербург',
        'ekaterinburg' => 'Екатеринбург',
        'novosibirsk' => 'Новосибирск',
        'kazan' => 'Казань',
    ];
    
    $city_ids = [];
    foreach ($cities as $slug => $name) {
        $term = term_exists($name, 'city');
        if (!$term) {
            $term = wp_insert_term($name, 'city', ['slug' => $slug]);
        }
        if (is_wp_error($term)) {
            error_log('Ошибка создания города "' . $name . '": ' . $term->get_error_message());
        } else {
            $city_ids[$slug] = is_array($term) ? $term['term_id'] : $term;
            error_log('Создан город: ' . $name . ' (ID: ' . $city_ids[$slug] . ')');
        }
    }
    
    error_log('Creating doctors...');
    // 3. Create doctors
    $doctors = [
        [
            'title' => 'Иванов Иван Петрович',
            'content' => '<h3>Кардиолог с 15-летним опытом</h3><p>Специалист по лечению сердечно-сосудистых заболеваний.</p>',
            'specializations' => ['cardiology'],
            'cities' => ['moscow'],
            'experience' => 15,
            'price' => 1500,
            'rating' => 4.5,
        ],
        [
            'title' => 'Петрова Анна Сергеевна',
            'content' => '<h3>Детский невролог</h3><p>Специалист по детской неврологии.</p>',
            'excerpt' => 'Детский невролог с большим опытом',
            'specializations' => ['neurology', 'pediatrics'],
            'cities' => ['saint-petersburg'],
            'experience' => 12,
            'price' => 2500,
            'rating' => 4.9
        ],
        [
            'title' => 'Сидоров Алексей Владимирович',
            'content' => '<h3>Стоматолог-ортопед</h3><p>Специалист по протезированию зубов.</p>',
            'excerpt' => 'Врач-стоматолог высшей категории',
            'specializations' => ['dentistry'],
            'cities' => ['moscow', 'ekaterinburg'],
            'experience' => 18,
            'price' => 5000,
            'rating' => 4.7
        ],
        [
            'title' => 'Кузнецова Елена Александровна',
            'content' => '<h3>Хирург</h3><p>Специалист по лапароскопическим операциям.</p>',
            'excerpt' => 'Опытный хирург',
            'specializations' => ['surgery'],
            'cities' => ['novosibirsk'],
            'experience' => 14,
            'price' => 15000,
            'rating' => 4.6
        ],
        [
            'title' => 'Морозов Дмитрий Игоревич',
            'content' => '<h3>Ортопед</h3><p>Специалист по эндопротезированию суставов.</p>',
            'excerpt' => 'Врач-ортопед, травматолог',
            'specializations' => ['orthopedics'],
            'cities' => ['kazan'],
            'experience' => 16,
            'price' => 4000,
            'rating' => 4.8
        ],
        [
            'title' => 'Волкова Марина Олеговна',
            'content' => '<h3>Офтальмолог</h3><p>Специалист по лазерной коррекции зрения.</p>',
            'excerpt' => 'Врач-офтальмолог, хирург',
            'specializations' => ['ophthalmology'],
            'cities' => ['moscow'],
            'experience' => 11,
            'price' => 8000,
            'rating' => 4.9
        ],
        [
            'title' => 'Громов Павел Викторович',
            'content' => '<h3>Дерматолог</h3><p>Специалист по лечению кожных заболеваний.</p>',
            'excerpt' => 'Врач-дерматолог, косметолог',
            'specializations' => ['dermatology'],
            'cities' => ['saint-petersburg'],
            'experience' => 13,
            'price' => 2000,
            'rating' => 4.5
        ],
        [
            'title' => 'Смирнова Ольга Викторовна',
            'content' => '<h3>Невролог</h3><p>Специалист по лечению головных болей.</p>',
            'excerpt' => 'Врач-невролог высшей категории',
            'specializations' => ['neurology'],
            'cities' => ['moscow'],
            'experience' => 10,
            'price' => 2800,
            'rating' => 4.7
        ],
        [
            'title' => 'Васильев Андрей Николаевич',
            'content' => '<h3>Хирург</h3><p>Опытный хирург с многолетним стажем.</p>',
            'excerpt' => 'Врач-хирург',
            'specializations' => ['surgery'],
            'cities' => ['kazan'],
            'experience' => 20,
            'price' => 12000,
            'rating' => 4.8
        ]
    ];
    
    foreach ($doctors as $doctor_data) {
        $post_data = [
            'post_title'   => $doctor_data['title'],
            'post_content' => $doctor_data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'doctors',
            'post_author'  => 1,  // Default admin
        ];
        
        $post_id = wp_insert_post($post_data);
        if (is_wp_error($post_id)) {
            error_log('Ошибка создания врача "' . $doctor_data['title'] . '": ' . $post_id->get_error_message());
            continue;
        } else {
            error_log('Создан врач: ' . $doctor_data['title'] . ' (ID: ' . $post_id . ')');
        }
        
        // Set specializations
        $spec_terms = [];
        foreach ($doctor_data['specializations'] as $spec_slug) {
            if (isset($spec_ids[$spec_slug])) {
                $spec_terms[] = (int) $spec_ids[$spec_slug];
            }
        }
        if (!empty($spec_terms)) {
            $result = wp_set_post_terms($post_id, $spec_terms, 'specialization');
            if (is_wp_error($result)) {
                error_log('Ошибка привязки специализаций к врачу ID ' . $post_id . ': ' . $result->get_error_message());
            }
        }
        
        // Set cities
        $city_terms = [];
        foreach ($doctor_data['cities'] as $city_slug) {
            if (isset($city_ids[$city_slug])) {
                $city_terms[] = (int) $city_ids[$city_slug];
            }
        }
        if (!empty($city_terms)) {
            $result = wp_set_post_terms($post_id, $city_terms, 'city');
            if (is_wp_error($result)) {
                error_log('Ошибка привязки городов к врачу ID ' . $post_id . ': ' . $result->get_error_message());
            }
        }
        
        // Set meta fields
        update_post_meta($post_id, '_experience', $doctor_data['experience']);
        update_post_meta($post_id, '_price_from', $doctor_data['price']);
        update_post_meta($post_id, '_rating', $doctor_data['rating']);
    }
    
    // Mark as installed
    update_option('doctors_demo_data_created', '1');
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    error_log('===== doctors_cpt_install() FINISHED =====');
}

/**
 * Uninstall cleanup
 */
function doctors_cpt_uninstall() {
    // Optional: Remove demo data on uninstall
    // Uncomment if you want to clean up
    
    /*
    global $wpdb;
    
    // Delete all doctors
    $doctors = get_posts([
        'post_type' => 'doctors',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);
    
    foreach ($doctors as $doctor_id) {
        wp_delete_post($doctor_id, true);
    }
    
    // Delete terms
    $terms = get_terms([
        'taxonomy' => ['specialization', 'city'],
        'hide_empty' => false,
        'fields' => 'ids'
    ]);
    
    foreach ($terms as $term_id) {
        wp_delete_term($term_id, 'specialization');
        wp_delete_term($term_id, 'city');
    }
    
    delete_option('doctors_demo_data_created');
    */
}