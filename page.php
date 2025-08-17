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
                        echo '<div class="photo-grid" id="photo-grid">';
                        while ($photos_query->have_posts()) :
                            $photos_query->the_post(); 
                            
                            // Récupérer les métadonnées pour la lightbox
                            $reference = get_field('reference');
                            $categories = get_the_terms(get_the_ID(), 'photo_categorie');
                            $category_name = '';
                            if (!empty($categories) && !is_wp_error($categories)) {
                                $category_name = $categories[0]->name;
                            }
                            
                            // URL de l'image en haute résolution
                            $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            if (!$full_image_url) {
                                $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            }
                            ?>
                            
                            <article class="photo-item">
                                <div class="photo-container">
                                    <!-- Image avec lien vers la page détail -->
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <?php
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('medium', ['class' => 'photo-image']);
                                        } else {
                                            echo '<div class="no-thumbnail">Pas d\'image disponible</div>';
                                        }
                                        ?>
                                    </a>
                                    
                                    <!-- Overlay avec icônes -->
                                    <div class="photo-overlay">
                                        <!-- Icône œil -->
                                        <a href="<?php the_permalink(); ?>" class="icon-eye" title="Voir les détails">
                                            <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M23 9C17 9 11.73 12.39 9 17.5C11.73 22.61 17 26 23 26C29 26 34.27 22.61 37 17.5C34.27 12.39 29 9 23 9ZM23 22.5C19.97 22.5 17.5 20.03 17.5 17.5C17.5 14.97 19.97 12.5 23 12.5C26.03 12.5 28.5 14.97 28.5 17.5C28.5 20.03 26.03 22.5 23 22.5Z" fill="white"/>
                                                <circle cx="23" cy="17.5" r="2.5" fill="white"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Icône plein écran avec données pour la lightbox -->
                                        <a href="#" class="icon-fullscreen" 
                                           data-full-src="<?php echo esc_url($full_image_url); ?>" 
                                           data-reference="<?php echo esc_attr($reference); ?>" 
                                           data-category="<?php echo esc_attr($category_name); ?>"
                                           data-title="<?php echo esc_attr(get_the_title()); ?>"
                                           data-permalink="<?php echo esc_url(get_permalink()); ?>"
                                           title="Affichage plein écran">
                                            <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.5 9.5H16.5V12.5H12.5V16.5H9.5V9.5ZM33.5 9.5V16.5H30.5V12.5H26.5V9.5H33.5ZM30.5 33.5V29.5H33.5V36.5H26.5V33.5H30.5ZM16.5 33.5V36.5H9.5V29.5H12.5V33.5H16.5Z" fill="white"/>
                                            </svg>
                                        </a>
                                        
                                        <div class="photo-meta">
                                            <div class="photo-info">
                                                <?php if ($reference) : ?>
                                                    <span class="photo-reference"><?php echo esc_html($reference); ?></span>
                                                <?php endif; ?>
                                                <?php if ($category_name) : ?>
                                                    <span class="photo-category"><?php echo esc_html($category_name); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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