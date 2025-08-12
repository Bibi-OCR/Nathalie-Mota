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


