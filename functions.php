<?php
// Enregistrement des styles et scripts
function nathalie_mota_enqueue_scripts() {
    wp_enqueue_style('theme-style', get_stylesheet_uri());

    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap');

    // jQuery
    wp_enqueue_script('jquery');

    // Script principal (ton fichier js/scripts.js)
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts');


// Enregistrement des menus personnalisés
function nathalie_mota_register_menus() {
    register_nav_menus(array(
        'header' => __('Menu principal (Header)', 'nathalie-mota'),
        'footer' => __('Menu pied de page (Footer)', 'nathalie-mota'),
    ));
}
add_action('after_setup_theme', 'nathalie_mota_register_menus');


// Ajout d'attributs spécifiques sur le lien 'Contact' du menu header pour ouvrir la modale
function nathalie_mota_add_data_modal_attribute($atts, $item, $args) {
    if ($args->theme_location === 'header' && strtolower($item->title) === 'contact') {
        $atts['data-modal'] = 'contact';
        $atts['href'] = '#';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'nathalie_mota_add_data_modal_attribute', 10, 3);



