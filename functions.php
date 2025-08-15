<?php
// Enqueue styles & scripts
function nathalie_enqueue_scripts() {
    wp_enqueue_style('nathalie-style', get_stylesheet_uri());

    // Google Fonts
    wp_enqueue_style('nathalie-google-fonts', 'https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap');

    wp_enqueue_script('jquery');

    // JS principal
    wp_enqueue_script('nathalie-scripts', get_template_directory_uri() . '/js/scripts.js', ['jquery'], '1.0', true);
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


// AJAX Load More Photos - Version améliorée
function load_more_photos_ajax() {
    // Vérifier le nonce pour la sécurité (optionnel mais recommandé)
    // if (!wp_verify_nonce($_POST['nonce'], 'load_more_nonce')) {
    //     wp_die('Erreur de sécurité');
    // }

    // Récupérer et valider le numéro de page
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    // S'assurer que la page est au minimum 1
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
    
    // Variable pour stocker le HTML
    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : 
            $query->the_post();
            
            // Construire le HTML pour chaque photo
            $html .= '<article class="photo-item">';
            $html .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
            
            if (has_post_thumbnail()) {
                $html .= get_the_post_thumbnail(get_the_ID(), 'medium');
            } else {
                $html .= '<div class="no-thumbnail">Pas d\'image disponible</div>';
            }
            
            $html .= '</a>';
            $html .= '</article>';
            
        endwhile;
    endif;

    wp_reset_postdata();
    
    // Retourner le HTML ou une chaîne vide si pas de résultats
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
        '1.0.1', // Incrémenter la version pour forcer le rechargement
        true
    );

    // Localiser le script avec les variables nécessaires
    wp_localize_script('main-script', 'wp_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_more_nonce'), // Pour la sécurité
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Debug function - à supprimer en production
function debug_load_more() {
    error_log('Action load_more_photos appelée');
    error_log('POST data: ' . print_r($_POST, true));
}
// add_action('wp_ajax_load_more_photos', 'debug_load_more', 5);
// add_action('wp_ajax_nopriv_load_more_photos', 'debug_load_more', 5);
?>