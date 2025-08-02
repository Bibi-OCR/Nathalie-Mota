<?php
// Enregistrement des styles et scripts
function theme_enqueue_scripts() {
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

// Integration des Google Fonts
function nathalie_mota_google_fonts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap');
}
add_action('wp_enqueue_scripts', 'nathalie_mota_google_fonts');

function register_custom_menus() {
  register_nav_menus(array(
    'header' => 'Menu principal (Header)',
    'footer' => 'Menu pied de page (Footer)',
  ));
}
add_action('after_setup_theme', 'register_custom_menus');
