<footer class="site-footer">
  <div class="container">
    <div class="footer-content">
      <!-- Menu footer desktop -->
      <nav class="footer-navigation desktop-footer">
        <?php
          wp_nav_menu(array(
            'theme_location' => 'footer',
            'container' => false,
            'menu_class' => 'footer-menu',
            'fallback_cb' => false,
          ));
        ?>
      </nav>
      
      
</footer>

<?php get_template_part('templates-parts/modal-contact'); ?>


<!-- Lightbox Structure -->
<div id="nathalie-lightbox" class="lightbox-overlay">
    <div class="lightbox-container">
        <!-- Bouton fermeture -->
        <button class="lightbox-close" aria-label="Fermer la lightbox">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Navigation précédente -->
        <button class="lightbox-nav lightbox-prev" aria-label="Photo précédente">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg.png" alt="Flèche gauche" class="arrow-icon">
        </button>

        <!-- Navigation suivante -->
        <button class="lightbox-nav lightbox-next" aria-label="Photo suivante">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-right.svg.png" alt="Flèche droite" class="arrow-icon">
        </button>

        <!-- Contenu principal -->
        <div class="lightbox-content">
            <!-- Image -->
            <figure class="lightbox-image-container">
                <img src="" alt="" class="lightbox-image">
                 <!-- AJOUT: Informations de la photo -->
                <figcaption class="lightbox-info">
                  
                  <span class="lightbox-reference"></span>
                  <span class="lightbox-category"></span>
                    
                </figcaption>
                <div class="lightbox-loading">
                    <div class="loading-spinner"></div>
                </div>
            </figure>
            
           
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>