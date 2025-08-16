<?php get_header(); ?>
<!-- page d'accueil -->
<main class="main-content">
    <div class="container">

<?php
// Récupération de l'image du Hero via ACF
$hero_image = get_field('hero_image');
if (!$hero_image) {
    $hero_image = get_stylesheet_directory_uri() . '/assets/images/nathalie-1.jpeg';
}
?>
<section class="hero-header">
  <div class="hero-container" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
    <div class="hero-title-wrapper">
      <h1 class="hero-title">PHOTOGRAPHE EVENT</h1>
    </div>
  </div>
</section>

<?php get_template_part('templates-parts/photo-filters'); ?>

<?php while (have_posts()) : the_post(); ?>
    <article class="page-content-wrapper">

        <?php if (has_post_thumbnail()) : ?>
            <div class="page-featured-image">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <div class="page-content">
            <?php the_content(); ?>
        </div>

        <!-- Début galerie photos -->
        <section class="frontpage-photos">
            <?php
            $args = [
                'post_type' => 'photo',
                'posts_per_page' => 8,
                'orderby' => 'date',
                'order' => 'DESC',
                'paged' => 1,
            ];
            $photos_query = new WP_Query($args);

            if ($photos_query->have_posts()) :
                echo '<div class="photo-grid" id="photo-grid">'; // <-- ID ajouté
                while ($photos_query->have_posts()) :
                    $photos_query->the_post(); ?>
                    <article class="photo-item">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('medium');
                            } else {
                                echo '<div class="no-thumbnail">Pas d’image disponible</div>';
                            }
                            ?>
                        </a>
                    </article>
                <?php endwhile;
                echo '</div>';
                wp_reset_postdata();
            else :
                echo '<p>Aucune photo trouvée.</p>';
            endif;
            ?>
        </section>
        <!-- Fin galerie photos -->

    </article>
<?php endwhile; ?>
</div>
</main>


            <div class="load-more-container">
                <button id="load-more-photos" data-page="1">Charger plus</button>
            </div>

<?php get_footer(); ?>
