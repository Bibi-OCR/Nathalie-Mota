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
    <nav class="site-header__nav">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'header',
          'container' => false,
          'menu_class' => 'menu'
        ));
      ?>
    </nav>
  </div>
</header>


<section class="hero-header">
  <div class="hero-container">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/nathalie-1.jpeg" alt="Image Hero" class="hero-image" />
    <div class="hero-title-wrapper">
      <h1 class="hero-title">PHOTOGRAPHE EVENT</h1>
    </div>
  </div>
</section>

