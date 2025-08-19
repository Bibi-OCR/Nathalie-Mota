<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
  <div class="container">
    <div class="site-header__logo">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png" alt="Nathalie Mota">
      </a>
    </div>
    
    <!-- Menu desktop -->
    <nav class="site-header__nav desktop-nav">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'header',
          'container' => false,
          'menu_class' => 'menu'
        ));
      ?>
    </nav>
    
    <!-- Bouton menu mobile -->
    <button class="mobile-menu-toggle" aria-label="Ouvrir le menu">
      <span class="menu-text">MENU</span>
      <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </button>
  </div>
  
  <!-- Menu mobile overlay -->
  <div class="mobile-menu-overlay">
    <nav class="mobile-nav">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'header',
          'container' => false,
          'menu_class' => 'mobile-menu'
        ));
      ?>
    </nav>
  </div>
</header>




