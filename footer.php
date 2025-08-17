<footer class="site-footer">
  <div class="container">
    <div class="footer-content">
      <!-- Menu footer WordPress -->
      <nav class="footer-navigation">
        <?php
          wp_nav_menu(array(
            'theme_location' => 'footer',
            'container' => false,
            'menu_class' => 'footer-menu',
            'fallback_cb' => false,
          ));
        ?>
      </nav>
    </div>
  </div>
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
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Navigation suivante -->
        <button class="lightbox-nav lightbox-next" aria-label="Photo suivante">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Contenu principal -->
        <div class="lightbox-content">
            <!-- Image -->
            <div class="lightbox-image-container">
                <img src="" alt="" class="lightbox-image">
                <div class="lightbox-loading">
                    <div class="loading-spinner"></div>
                </div>
            </div>

            <!-- Informations -->
            <div class="lightbox-info">
                <div class="lightbox-meta">
                    <h3 class="lightbox-title"></h3>
                    <div class="lightbox-details">
                        <span class="lightbox-reference"></span>
                        <span class="lightbox-category"></span>
                    </div>
                </div>
                <div class="lightbox-counter">
                    <span class="current-photo">1</span> / <span class="total-photos">1</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>