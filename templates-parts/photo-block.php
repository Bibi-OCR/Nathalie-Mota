<?php
/**
 * Template pour l'affichage d'un bloc photo avec support lightbox
 */

$reference   = get_field('reference');
$categories  = get_the_terms(get_the_ID(), 'photo_categorie');
$category_name = '';
if (!empty($categories) && !is_wp_error($categories)) {
    $category_name = $categories[0]->name;
}

// URL de l'image en haute résolution pour la lightbox
$full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$full_image_url) {
    $full_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
}
?>

<div class="photo-block">
    <?php if (has_post_thumbnail()) : ?>
        <div class="photo-container">
            <?php the_post_thumbnail('medium_large', ['class' => 'photo-image']); ?>
            
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

                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" 
                         viewBox="0 0 24 24" fill="none" stroke="white" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9V5a2 2 0 0 1 2-2h4"/>
                        <path d="M21 9V5a2 2 0 0 0-2-2h-4"/>
                        <path d="M21 15v4a2 2 0 0 1-2 2h-4"/>
                        <path d="M3 15v4a2 2 0 0 0 2 2h4"/>
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
    <?php endif; ?>
</div>
