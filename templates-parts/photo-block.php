<?php
/**
 * Template part pour afficher une photo dans la grille
 * Utilisé dans la page d'accueil et les requêtes AJAX
 */

$reference = get_field('reference');
$categories = wp_get_post_terms(get_the_ID(), 'photo_categorie');
$category_name = '';
if ($categories && !is_wp_error($categories)) {
    $category_name = $categories[0]->name;
}
?>

<div class="photo-item" data-reference="<?php echo esc_attr($reference); ?>">
    <div class="photo-wrapper">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium_large', array('alt' => get_the_title())); ?>
        <?php endif; ?>
        
        <!-- Overlay qui apparaît au survol -->
        <div class="photo-overlay">
            <!-- Icônes d'action -->
            <div class="overlay-icons">
                <!-- Icône œil - lien vers la page single photo -->
                <a href="<?php echo home_url('wp-content\themes\Nathalie-Mota\assets\images' . get_post_field('post_name')); ?>" class="icon-eye" aria-label="Voir les détails">
                    <svg width="46" height="32" viewBox="0 0 46 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M23 0C12.6 0 3.4 6.4 0 16C3.4 25.6 12.6 32 23 32C33.4 32 42.6 25.6 46 16C42.6 6.4 33.4 0 23 0ZM23 26.7C16.8 26.7 11.7 21.6 11.7 15.3C11.7 9.1 16.8 4 23 4C29.2 4 34.3 9.1 34.3 15.3C34.3 21.6 29.2 26.7 23 26.7ZM23 8C18.6 8 15 11.6 15 16C15 20.4 18.6 24 23 24C27.4 24 31 20.4 31 16C31 11.6 27.4 8 23 8Z" fill="white"/>
                    </svg>
                </a>
                
                <!-- Icône plein écran - ouvre la lightbox -->
                <a href="<?php echo get_the_post_thumbnail_url('full'); ?>" class="icon-fullscreen lightbox-trigger" data-lightbox="gallery" aria-label="Voir en plein écran">
                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 12V2H12" stroke="white" stroke-width="3"/>
                        <path d="M32 12V2H22" stroke="white" stroke-width="3"/>
                        <path d="M32 22V32H22" stroke="white" stroke-width="3"/>
                        <path d="M2 22V32H12" stroke="white" stroke-width="3"/>
                    </svg>
                </a>
            </div>
            
            <!-- Informations photo en bas -->
            <div class="photo-info">
                <div class="photo-reference"><?php echo esc_html($reference); ?></div>
                <div class="photo-category"><?php echo esc_html($category_name); ?></div>
            </div>
        </div>
    </div>
</div>