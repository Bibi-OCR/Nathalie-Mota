<?php
/**
 * Modèle de page : Photo unique.
 * Description : Modèle de page pour une photo unique.
 */

get_header();
?>

<main id="main" class="content-area">
    <div class="zone-contenu mobile-first">
        <div class="left-container">
            <div class="left-contenu">
                <h1><?php the_title(); ?></h1>
<?php
// Référence de la photo (ACF)
$reference = get_field('reference');
if (!empty($reference)) {
    echo '<p>Référence : ' . esc_html($reference) . '</p>';
}

// Catégories de la photo (taxonomie CPT-UI : photo_categorie)
$categories = get_the_terms(get_the_ID(), 'photo_categorie');
if (!empty($categories) && !is_wp_error($categories)) {
    echo '<p>Catégorie : ';
    $category_names = array();
    foreach ($categories as $category) {
        if (!empty($category->name)) {
            $category_names[] = esc_html($category->name);
        }
    }
    echo implode(', ', $category_names);
    echo '</p>';
}

// Formats de la photo (taxonomie CPT-UI : photo_format)
$format_terms = get_the_terms(get_the_ID(), 'photo_format');
if (!empty($format_terms) && !is_wp_error($format_terms)) {
    echo '<p>Format : ';
    $format_names = array();
    foreach ($format_terms as $format_term) {
        if (!empty($format_term->name)) {
            $format_names[] = esc_html($format_term->name);
        }
    }
    echo implode(', ', $format_names);
    echo '</p>';
}

// Type de la photo (ACF)
$type = get_field('type'); 
if ($type) {
    echo '<p>Type : ' . esc_html($type) . '</p>';
}

    


// Année de capture
$date_capture = get_the_date('Y'); 
if ($date_capture) {
    echo '<p>Année : ' . esc_html($date_capture) . '</p>';
}
?>

            </div>
        </div>
        <div class="right-container">
            <?php if (has_post_thumbnail()) : ?>
                <a href="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id(), 'large')[0]; ?>" data-lightbox="image-gallery" class="photo"
                  data-ref="<?php echo esc_attr($reference); ?>">  
                <?php the_post_thumbnail(); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="zone-contact">
        <div class="left-contact">
            <div class="texte-contact">
                <p>Cette photo vous intéresse ?</p>
            </div>
            <div class="bouton-contact">
                <button class="contact-button" data-modal="contact">Contact</button>


                <?php include get_template_directory() . '/templates-parts/modal-contact.php'; ?>
                <?php
                $reference_photo = get_field('reference');
                if ($reference) {
                    echo '<script type="text/javascript">';
                    echo 'var acfReferencePhoto = "' . esc_js($reference) . '";';
                    echo '</script>';
                }
                ?>
            </div>
        </div>
        <div class="right-contact">
            <?php
            // ID du post actuel
            $current_post_id = get_the_ID();

            // Obtenez tous les posts de type 'photo'
            $args = array(
                'post_type' => 'photo',
                'posts_per_page' => -1,
                'order' => 'ASC',
            );
            $all_photo_posts = get_posts($args);

            // Trouvez l'index du post actuel
            $current_post_index = array_search($current_post_id, array_column($all_photo_posts, 'ID'));

            // Calculez les index des posts précédent et suivant
            $prev_post_index = $current_post_index - 1;
            $next_post_index = $current_post_index + 1;

            // Obtenez les posts précédent et suivant
            $prev_post = ($prev_post_index >= 0) ? $all_photo_posts[$prev_post_index] : end($all_photo_posts);
            $next_post = ($next_post_index < count($all_photo_posts)) ? $all_photo_posts[$next_post_index] : reset($all_photo_posts);

            $prev_permalink = get_permalink($prev_post);
            $next_permalink = get_permalink($next_post);

            // Obtenez les miniatures (featured images) des posts précédent et suivant
            $prev_thumbnail = get_the_post_thumbnail($prev_post, 'thumbnail');
            $next_thumbnail = get_the_post_thumbnail($next_post, 'thumbnail');
            ?>

            <div class="prev-nav">
                <a href="<?php echo esc_url($prev_permalink); ?>" class="prev-photo">
                    <?php if ($prev_thumbnail) : ?>
                        <div class="thumbnail-container">
                            <?php echo $prev_thumbnail; ?>
                            <img src="<?php echo get_template_directory_uri(); ?>\assets\images\arrow-left.svg.png" alt="Previous" class="arrow-img-gauche" />
                        </div>
                    <?php endif; ?>
                </a>
            </div>

            <div class="next-nav">
                <a href="<?php echo esc_url($next_permalink); ?>" class="next-photo">
                    <?php if ($next_thumbnail) : ?>
                        <div class="thumbnail-container">
                            <?php echo $next_thumbnail; ?>
                            <img src="<?php echo get_template_directory_uri(); ?>\assets\images\arrow-right.svg.png" alt="Next" class="arrow-img-droite" />
                        </div>
                    <?php endif; ?>
                </a>
            </div>

        </div>
    </div>
</main>

<!-- Section Photos Apparentées -->
    <section class="related-photos">
        <div class="container">
            <h2 class="related-photos-title">Vous aimerez aussi</h2>
            
            <?php
            // Récupérer les catégories de la photo actuelle
            $current_categories = get_the_terms(get_the_ID(), 'photo_categorie');
            
            if (!empty($current_categories) && !is_wp_error($current_categories)) :
                // Récupérer les IDs des catégories
                $category_ids = array();
                foreach ($current_categories as $category) {
                    $category_ids[] = $category->term_id;
                }
                
                // Query pour les photos apparentées
                $related_args = array(
                    'post_type' => 'photo',
                    'posts_per_page' => 2,
                    'post__not_in' => array(get_the_ID()), // Exclure la photo actuelle
                    'orderby' => 'rand', // Ordre aléatoire
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'photo_categorie',
                            'field'    => 'term_id',
                            'terms'    => $category_ids,
                        ),
                    ),
                );
                
                $related_query = new WP_Query($related_args);
                
                if ($related_query->have_posts()) :
            ?>
                    <div class="related-photos-grid">
                        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                            <?php get_template_part('templates-parts/photo-block'); ?>
                        <?php endwhile; ?>
                    </div>
            <?php
                    wp_reset_postdata();
                else :
                    // Si aucune photo de la même catégorie, afficher 2 photos aléatoirement
                    $fallback_args = array(
                        'post_type' => 'photo',
                        'posts_per_page' => 2,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'rand',
                    );
                    
                    $fallback_query = new WP_Query($fallback_args);
                    
                    if ($fallback_query->have_posts()) :
            ?>
                        <div class="related-photos-grid">
                            <?php while ($fallback_query->have_posts()) : $fallback_query->the_post(); ?>
                                <?php get_template_part('templates-parts/photo-block'); ?>
                            <?php endwhile; ?>
                        </div>
            <?php
                        wp_reset_postdata();
                    endif;
                endif;
            endif;
            ?>
        </div>
    </section>

<?php get_footer(); ?>