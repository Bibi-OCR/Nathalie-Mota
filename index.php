

<?php get_header();?>

            <?php while(have_posts()) : the_post() ?>
                    <h1><?php the_title() ?></h1>
                    <?php the_content() ?>
                <?php endwhile; ?>

                <?php
/*
Template Name: Page d'accueil motaphoto
*/
get_header(); ?>

<!-- Section galerie avec filtres -->
<section class="photo-gallery-section">
    <!-- Filtres alignés horizontalement -->
    <div class="filters-container">
        <div class="filter-item">
            <select id="category-filter" name="category-filter">
                <option value="">CATÉGORIES</option>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'photo_categorie',
                    'hide_empty' => true,
                ));
                if ($categories && !is_wp_error($categories)) {
                    foreach ($categories as $category) {
                        echo '<option value="' . $category->slug . '">' . esc_html($category->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="filter-item">
            <select id="format-filter" name="format-filter">
                <option value="">FORMATS</option>
                <?php
                $formats = get_terms(array(
                    'taxonomy' => 'photo_format',
                    'hide_empty' => true,
                ));
                if ($formats && !is_wp_error($formats)) {
                    foreach ($formats as $format) {
                        echo '<option value="' . $format->slug . '">' . esc_html($format->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="filter-item">
            <select id="date-filter" name="date-filter">
                <option value="">TRIER PAR</option>
                <option value="DESC">À partir des plus récentes</option>
                <option value="ASC">À partir des plus anciennes</option>
            </select>
        </div>
    </div>

    <!-- Grille de photos -->
    <div class="photo-grid" id="photo-container">
        <?php
        // Requête pour charger les photos (8 par défaut comme dans motaphoto)
        $photos_query = new WP_Query(array(
            'post_type' => 'photo',
            'posts_per_page' => 8,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if ($photos_query->have_posts()) :
            while ($photos_query->have_posts()) : $photos_query->the_post();
                get_template_part('template-parts/photo-block');
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>

    <!-- Bouton Charger plus -->
    <div class="load-more-wrapper">
        <?php if ($photos_query->max_num_pages > 1) : ?>
            <button id="load-more-btn" class="btn-load-more" data-page="1" data-max-pages="<?php echo $photos_query->max_num_pages; ?>">
                Charger plus
            </button>
        <?php endif; ?>
    </div>
</section>

<script type="text/javascript">
jQuery(document).ready(function($) {
    let currentPage = 1;
    let maxPages = parseInt($('#load-more-btn').data('max-pages')) || 1;
    let isLoading = false;

    // Fonction de chargement des photos
    function loadPhotos(page = 1, append = false) {
        if (isLoading) return;
        
        isLoading = true;
        
        const categoryFilter = $('#category-filter').val();
        const formatFilter = $('#format-filter').val();
        const dateFilter = $('#date-filter').val();

        $.ajax({
            url: ajaxurl || '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'load_photos_motaphoto',
                page: page,
                category: categoryFilter,
                format: formatFilter,
                date_order: dateFilter,
                security: '<?php echo wp_create_nonce('motaphoto_nonce'); ?>'
            },
            beforeSend: function() {
                if (append) {
                    $('#load-more-btn').text('Chargement...');
                } else {
                    $('#photo-container').addClass('loading');
                }
            },
            success: function(response) {
                if (response.success) {
                    if (append) {
                        $('#photo-container').append(response.data.html);
                    } else {
                        $('#photo-container').html(response.data.html);
                    }
                    
                    maxPages = response.data.max_pages;
                    
                    // Gestion bouton "Charger plus"
                    if (page >= maxPages) {
                        $('#load-more-btn').hide();
                    } else {
                        $('#load-more-btn').show().text('Charger plus');
                    }
                } else {
                    console.log('Erreur lors du chargement des photos');
                }
            },
            complete: function() {
                isLoading = false;
                $('#photo-container').removeClass('loading');
                $('#load-more-btn').text('Charger plus');
            }
        });
    }

    // Gestion des filtres
    $('.filters-container select').on('change', function() {
        currentPage = 1;
        loadPhotos(1, false);
    });

    // Bouton "Charger plus"
    $(document).on('click', '#load-more-btn', function() {
        currentPage++;
        loadPhotos(currentPage, true);
    });
});
</script>

<?php get_footer(); ?>

<?php get_footer(); ?>