<?php
// Enqueue styles & scripts
function nathalie_enqueue_scripts() {
    wp_enqueue_style('nathalie-style', get_stylesheet_uri());

    // Google Fonts
    wp_enqueue_style('nathalie-google-fonts', 'https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap');

    wp_enqueue_script('jquery');

    // JS principal
    wp_enqueue_script('nathalie-scripts', get_template_directory_uri() . '/js/scripts.js', ['jquery'], '1.0', true);
    
    // JS Lightbox
    wp_enqueue_script('nathalie-lightbox', get_template_directory_uri() . '/js/lightbox.js', ['jquery'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'nathalie_enqueue_scripts');

// Register menus
function nathalie_register_menus() {
    register_nav_menus([
        'header' => 'Menu principal (Header)',
        'footer' => 'Menu pied de page (Footer)',
    ]);
}
add_action('after_setup_theme', 'nathalie_register_menus');

// Add data-modal attribute to "Contact" menu item in header menu
function nathalie_add_data_modal_to_menu($atts, $item, $args) {
    if ($args->theme_location === 'header' && strtolower($item->title) === 'contact') {
        $atts['data-modal'] = 'contact';
        $atts['href'] = '#';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'nathalie_add_data_modal_to_menu', 10, 3);

function nathalie_add_photo_to_home_query($query) {
    if (!is_admin() && $query->is_main_query() && is_home()) {
        $query->set('post_type', ['post', 'photo']);
    }
}
add_action('pre_get_posts', 'nathalie_add_photo_to_home_query');

add_action('after_setup_theme', function() {
    add_theme_support('post-thumbnails');
});

// AJAX Load More Photos - Version avec support lightbox
function load_more_photos_ajax() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    if ($paged < 1) {
        $paged = 1;
    }

    $args = [
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $paged,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : 
            $query->the_post();
            
            // Récupérer les métadonnées pour la lightbox
            $reference = get_field('reference');
            $categories = get_the_terms(get_the_ID(), 'photo_categorie');
            $category_name = '';
            if (!empty($categories) && !is_wp_error($categories)) {
                $category_name = $categories[0]->name;
            }
            
            // URL de l'image en haute résolution
            $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if (!$full_image_url) {
                $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            }
            
            // Construire le HTML pour chaque photo avec support lightbox
            $html .= '<article class="photo-item">';
            $html .= '<div class="photo-container">';
            
            // Lien vers la page détail
            $html .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
            if (has_post_thumbnail()) {
                $html .= get_the_post_thumbnail(get_the_ID(), 'medium', ['class' => 'photo-image']);
            } else {
                $html .= '<div class="no-thumbnail">Pas d\'image disponible</div>';
            }
            $html .= '</a>';
            
            // Overlay avec icônes
            $html .= '<div class="photo-overlay">';
            
            // Icône œil
            $html .= '<a href="' . get_permalink() . '" class="icon-eye" title="Voir les détails">';
            $html .= '<svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">';
            $html .= '<path d="M23 9C17 9 11.73 12.39 9 17.5C11.73 22.61 17 26 23 26C29 26 34.27 22.61 37 17.5C34.27 12.39 29 9 23 9ZM23 22.5C19.97 22.5 17.5 20.03 17.5 17.5C17.5 14.97 19.97 12.5 23 12.5C26.03 12.5 28.5 14.97 28.5 17.5C28.5 20.03 26.03 22.5 23 22.5Z" fill="white"/>';
            $html .= '<circle cx="23" cy="17.5" r="2.5" fill="white"/>';
            $html .= '</svg>';
            $html .= '</a>';
            
            // Icône plein écran avec données lightbox
            $html .= '<a href="#" class="icon-fullscreen" ';
            $html .= 'data-full-src="' . esc_url($full_image_url) . '" ';
            $html .= 'data-reference="' . esc_attr($reference) . '" ';
            $html .= 'data-category="' . esc_attr($category_name) . '" ';
            $html .= 'data-title="' . esc_attr(get_the_title()) . '" ';
            $html .= 'data-permalink="' . esc_url(get_permalink()) . '" ';
            $html .= 'title="Affichage plein écran">';
            $html .= '<img src="' . get_template_directory_uri() . '/assets/images/icon_fullscreen.png" alt="Plein écran" width="34" height="34">';
            $html .= '</a>';
            
            // Métadonnées
            $html .= '<div class="photo-meta">';
            $html .= '<div class="photo-info">';
            if ($reference) {
                $html .= '<span class="photo-reference">' . esc_html($reference) . '</span>';
            }
            if ($category_name) {
                $html .= '<span class="photo-category">' . esc_html($category_name) . '</span>';
            }
            $html .= '</div>';
            $html .= '</div>';
            
            $html .= '</div>'; // fin photo-overlay
            $html .= '</div>'; // fin photo-container
            $html .= '</article>';
            
        endwhile;
    endif;

    wp_reset_postdata();
    echo $html;
    wp_die();
}
add_action('wp_ajax_load_more_photos', 'load_more_photos_ajax');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos_ajax');

// Fonction pour enqueue les scripts avec nonce (sécurité renforcée)
function enqueue_custom_scripts() {
    wp_enqueue_script(
        'main-script',
        get_template_directory_uri() . '/js/scripts.js',
        array('jquery'),
        '1.0.1',
        true
    );

    // Localiser le script avec les variables nécessaires
    wp_localize_script('main-script', 'wp_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_more_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// AJAX Filter Photos - Version avec support lightbox
function filter_photos_ajax() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'desc';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = [
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $paged,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => strtoupper($sort),
    ];

    // Ajouter les filtres taxonomiques
    $tax_query = [];

    if (!empty($category)) {
        $tax_query[] = [
            'taxonomy' => 'photo_categorie',
            'field'    => 'slug',
            'terms'    => $category,
        ];
    }

    if (!empty($format)) {
        $tax_query[] = [
            'taxonomy' => 'photo_format',
            'field'    => 'slug',
            'terms'    => $format,
        ];
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
        if (count($tax_query) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
    }

    $query = new WP_Query($args);
    $html = '';
    $has_more = false;

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Récupérer les métadonnées pour la lightbox
            $reference = get_field('reference');
            $categories = get_the_terms(get_the_ID(), 'photo_categorie');
            $category_name = '';
            if (!empty($categories) && !is_wp_error($categories)) {
                $category_name = $categories[0]->name;
            }
            
            // URL de l'image en haute résolution
            $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if (!$full_image_url) {
                $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            }
            
            // Construire le HTML (même structure que load_more)
            $html .= '<article class="photo-item">';
            $html .= '<div class="photo-container">';
            
            $html .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
            if (has_post_thumbnail()) {
                $html .= get_the_post_thumbnail(get_the_ID(), 'medium', ['class' => 'photo-image']);
            } else {
                $html .= '<div class="no-thumbnail">Pas d\'image disponible</div>';
            }
            $html .= '</a>';
            
            $html .= '<div class="photo-overlay">';
            
            // Icône œil
            $html .= '<a href="' . get_permalink() . '" class="icon-eye" title="Voir les détails">';
            $html .= '<svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">';
            $html .= '<path d="M23 9C17 9 11.73 12.39 9 17.5C11.73 22.61 17 26 23 26C29 26 34.27 22.61 37 17.5C34.27 12.39 29 9 23 9ZM23 22.5C19.97 22.5 17.5 20.03 17.5 17.5C17.5 14.97 19.97 12.5 23 12.5C26.03 12.5 28.5 14.97 28.5 17.5C28.5 20.03 26.03 22.5 23 22.5Z" fill="white"/>';
            $html .= '<circle cx="23" cy="17.5" r="2.5" fill="white"/>';
            $html .= '</svg>';
            $html .= '</a>';
            
            // Icône plein écran
            $html .= '<a href="#" class="icon-fullscreen" ';
            $html .= 'data-full-src="' . esc_url($full_image_url) . '" ';
            $html .= 'data-reference="' . esc_attr($reference) . '" ';
            $html .= 'data-category="' . esc_attr($category_name) . '" ';
            $html .= 'data-title="' . esc_attr(get_the_title()) . '" ';
            $html .= 'data-permalink="' . esc_url(get_permalink()) . '" ';
            $html .= 'title="Affichage plein écran">';
            $html .= '<img src="' . get_template_directory_uri() . '/assets/images/icon_fullscreen.png" alt="Plein écran" width="34" height="34">';
            $html .= '</a>';
            
            $html .= '<div class="photo-meta">';
            $html .= '<div class="photo-info">';
            if ($reference) {
                $html .= '<span class="photo-reference">' . esc_html($reference) . '</span>';
            }
            if ($category_name) {
                $html .= '<span class="photo-category">' . esc_html($category_name) . '</span>';
            }
            $html .= '</div>';
            $html .= '</div>';
            
            $html .= '</div>'; // fin photo-overlay
            $html .= '</div>'; // fin photo-container
            $html .= '</article>';
        }
        
        $has_more = ($query->max_num_pages > $paged);
    }

    wp_reset_postdata();
    
    wp_send_json([
        'html' => $html,
        'has_more' => $has_more,
        'current_page' => $paged,
        'max_pages' => $query->max_num_pages
    ]);
}
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');
?>