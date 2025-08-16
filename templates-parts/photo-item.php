<?php
/**
 * Template part pour afficher une photo dans la grille
 */
?>

<article class="photo-item" data-photo-id="<?php echo get_the_ID(); ?>">
    <a href="<?php the_permalink(); ?>" 
       title="<?php the_title_attribute(); ?>" 
       class="photo-link">
        
        <?php if (has_post_thumbnail()) : ?>
            <?php 
            the_post_thumbnail('medium', [
                'class' => 'photo-image',
                'loading' => 'lazy', // Lazy loading pour optimiser les performances
                'alt' => get_the_title(),
            ]); 
            ?>
            
            <!-- Overlay avec informations de la photo -->
            <div class="photo-overlay">
                <div class="photo-info">
                    <h3 class="photo-title"><?php the_title(); ?></h3>
                    <?php 
                    // Afficher la catégorie si elle existe
                    $categories = get_the_terms(get_the_ID(), 'categorie-photo');
                    if ($categories && !is_wp_error($categories)) :
                        $category = array_shift($categories);
                        echo '<span class="photo-category">' . esc_html($category->name) . '</span>';
                    endif;
                    
                    // Afficher la date si nécessaire
                    ?>
                    <span class="photo-date"><?php echo get_the_date('j M Y'); ?></span>
                </div>
                
                <!-- Icône pour indiquer qu'on peut cliquer -->
                <div class="photo-action">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M15 12H9M12 9V15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" 
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            
        <?php else : ?>
            <div class="no-thumbnail">
                <span>Pas d'image disponible</span>
            </div>
        <?php endif; ?>
    </a>
</article>