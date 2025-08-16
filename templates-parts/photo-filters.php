<?php
/**
 * Template part pour les filtres de photos avec dropdown personnalisé
 */

// Récupérer les catégories de photos
$categories = get_terms([
    'taxonomy' => 'photo_categorie',
    'hide_empty' => true,
]);

// Récupérer les formats de photos
$formats = get_terms([
    'taxonomy' => 'photo_format', 
    'hide_empty' => true,
]);
?>

<div class="filters-section">
    <div class="filters-container">
        <!-- Filtres de gauche (CATÉGORIES et FORMATS ensemble) -->
        <div class="filters-left">
            <!-- Dropdown personnalisé pour CATÉGORIES -->
            <div class="custom-select" data-filter="category">
                <div class="select-selected">CATÉGORIES</div>
                <div class="select-items select-hide">
                    <div data-value="">CATÉGORIES</div>
                    <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <div data-value="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html(strtoupper($category->name)); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Dropdown personnalisé pour FORMATS -->
            <div class="custom-select" data-filter="format">
                <div class="select-selected">FORMATS</div>
                <div class="select-items select-hide">
                    <div data-value="">FORMATS</div>
                    <?php if (!empty($formats) && !is_wp_error($formats)) : ?>
                        <?php foreach ($formats as $format) : ?>
                            <div data-value="<?php echo esc_attr($format->slug); ?>">
                                <?php echo esc_html(strtoupper($format->name)); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Filtre de droite (TRIER PAR seul) -->
        <div class="filters-right">
            <!-- Dropdown personnalisé pour TRIER PAR -->
            <div class="custom-select" data-filter="sort">
                <div class="select-selected">TRIER PAR</div>
                <div class="select-items select-hide">
                    <div data-value="desc">TRIER PAR</div>
                    <div data-value="desc">PLUS RÉCENT</div>
                    <div data-value="asc">PLUS ANCIEN</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicateur de chargement -->
    <div class="loading-spinner" id="loading-spinner" style="display: none;">
        <div class="spinner"></div>
    </div>
</div>