<?php get_header(); ?>
<!-- page d'accueil -->
<main class="main-content">
    <div class="container">
       
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
                    ];
                    $photos_query = new WP_Query($args);

                    if ($photos_query->have_posts()) :
                        echo '<div class="photo-grid">';
                        
                       
                        while ($photos_query->have_posts()) :
                          
                            $photos_query->the_post();
                            
                           
                            ?>
                            <article class="photo-item">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('medium');
                                    } else {
                                        echo '<div class="no-thumbnail">Pas d’image disponible</div>';
                                    }
                                    ?>
                                    <h3><?php the_title(); ?></h3>
                                </a>
                            </article>
                            
                            <?php
                           
                        endwhile;
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

<?php get_footer(); ?>
