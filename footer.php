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

<?php wp_footer(); ?>
</body>
</html>