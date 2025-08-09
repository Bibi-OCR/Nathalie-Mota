<?php 
get_header();

// identifiant photo dans l'URL
$slug = get_query_var('photo');

// critères recherche
$args = [
   'post_type' => 'photo',
   'name' => $slug,
   'posts_per_page' => 1
];

// Requête database wordpress 
$custom_query = new WP_Query($args);

if ($custom_query->have_posts()) :
   while ($custom_query->have_posts()) : $custom_query->the_post();

   $reference = get_field('reference');
   $categories = wp_get_post_terms(get_the_ID(), 'photo_categorie');
   $formats = wp_get_post_terms(get_the_ID(), 'photo_format');
   $type = get_field('type'); 
   $annee = get_the_date('Y');
?>

<section class="photo-choice"> 
   <div class="infos">
       <div class="description"> 
           <h2><?php the_title(); ?></h2>             
           <p>référence : <span id="photo-ref"><?= $reference; ?></span></p>  
           <p>catégorie : 
               <?php 
               if ($categories && !is_wp_error($categories)) {
                   foreach ($categories as $categorie) { 
                       echo esc_html($categorie->name); 
                   } 
               }
               ?>
           </p> 
           <p>format : 
               <?php 
               if ($formats && !is_wp_error($formats)) {
                   foreach ($formats as $format) { 
                       echo esc_html($format->name);
                   } 
               }
               ?>
           </p>  
           <p>type : <?= $type; ?></p>  
           <p>année : <?= $annee; ?></p>          
       </div>
       <div class="infos-photo">
           <!-- 'medium large' custom size -->
           <?php the_post_thumbnail('medium_large') ?>
       </div>
   </div>

       <?php
       endwhile;
       wp_reset_postdata();
   endif;
   ?>

   <div class="interactions">
       <div class="interactions-contact">
           <p>Cette photo vous intéresse ?</p> 
           <button class="btn-contact" data-refphoto="<?= esc_attr($reference); ?>">Contact</button>

       </div>     

       <div class="photo-navigation">
           <div class="arrow">
               <?php
                   // Requête pour obtenir le dernier post
                   $args_dernier = array(
                       'post_type' => 'photo', 
                       'posts_per_page' => 1,
                       'orderby' => 'date',
                       'order' => 'DESC',
                   );
                   $last_post = new WP_Query($args_dernier);

                   // Requête pour obtenir le premier post
                   $args_premier = array(
                       'post_type' => 'photo', 
                       'posts_per_page' => 1,
                       'orderby' => 'date',
                       'order' => 'ASC',
                   );
                   $first_post = new WP_Query($args_premier);
               ?>
               <div class="arrow-left">
                   <?php $previous_post = get_previous_post(); ?>

                   <?php if (!empty($previous_post)): ?>
                       <a href="<?= get_permalink($previous_post); ?>">
                           <img src="<?= get_stylesheet_directory_uri() . '/assets/images/arrow-left.svg' ?>" alt="previous" />
                       </a>

                   <?php else : $last_post = $last_post->posts[0]; ?>
                       <a href="<?= get_permalink($last_post); ?>">
                           <img src="<?= get_stylesheet_directory_uri() . '/assets/images/arrow-left.svg' ?>" alt="previous" />
                       </a>
                   <?php endif; ?>
               </div>

               <div class="thumbnail-left">
                   <?php
                       // Récupération de la photo du post précédent
                       if (!empty($previous_post)) {
                           //  'thumbnail' custom size 
                           $thumbnail_left = get_the_post_thumbnail($previous_post->ID, 'custom-thumbnail');
                       } else {
                           $thumbnail_left = get_the_post_thumbnail($last_post, 'custom-thumbnail');
                       }
                       echo $thumbnail_left;
                   ?>
               </div>

               <div class="arrow-right">
                   <?php $next_post = get_next_post(); ?>

                   <?php if (!empty($next_post)): ?>
                       <a href="<?= get_permalink($next_post); ?>">
                           <img src="<?= get_stylesheet_directory_uri() . '/assets/images/arrow-right.svg' ?>" alt="next" />
                       </a>

                   <?php else : $first_post = $first_post->posts[0]; ?>
                       <a href="<?= get_permalink($first_post); ?>">
                           <img src="<?= get_stylesheet_directory_uri() . '/assets/images/arrow-right.svg' ?>" alt="next" />
                       </a>
                   <?php endif; ?>

               </div>

               <div class="thumbnail-right">
                   <?php
                       // Récupération de la photo du post suivant
                       if (!empty($next_post)) {
                           $thumbnail_right = get_the_post_thumbnail($next_post->ID, 'custom-thumbnail');
                       } else {
                           $thumbnail_right = get_the_post_thumbnail($first_post, 'custom-thumbnail');
                       }

                       echo $thumbnail_right;
                   ?>
               </div>
           </div>        
       </div> 
   </div>
</section>

<!-- partie 2-->
<section class="siblings">
   <h3>Vous aimerez aussi</h3>
   <div class="siblings-items">

       <?php
           // recup catégorie photo cette page
           $categories = wp_get_post_terms(get_the_ID(), 'photo_categorie');
           if ($categories && !is_wp_error($categories)) {
               $ID_categories = wp_list_pluck($categories, 'term_id');
               // 2 photos aléatoires même catégorie excluant celle de cette page
               $photos_siblings = new WP_Query(array(
                   'post_type' => 'photo',
                   'posts_per_page' => 2,
                   'post__not_in' => array(get_the_ID()), //requiert une array
                   'orderby' => 'rand',
                   'tax_query' => array(
                       array(
                           'taxonomy' => 'photo_categorie',
                           'field' => 'id',
                           'terms' => $ID_categories,
                       ),
                   ),
               ));

               if ($photos_siblings->have_posts()) {
                   while ($photos_siblings->have_posts()) {
                       $photos_siblings->the_post();
                       get_template_part('template_parts/block-photo');
                   }
                   wp_reset_postdata();
               } else {            
                   echo '<p>D\'autres photos similaires à découvrir... bientôt</p>';
               }
           }
       ?>
   </div>
</section> 

<?php
// Récupérer la catégorie principale de la photo actuelle
$categories = wp_get_post_terms(get_the_ID(), 'photo_categorie');
$category_slug = '';

if ($categories && !is_wp_error($categories)) {
    $category_slug = $categories[0]->slug;
}

if ($category_slug) :
    $related_photos_query = new WP_Query(array(
        'post_type' => 'photo',
        'posts_per_page' => 6,       // nombre de photos apparentées à afficher
        'post__not_in' => array(get_the_ID()), // exclure la photo courante
        'tax_query' => array(
            array(
                'taxonomy' => 'photo_categorie',
                'field' => 'slug',
                'terms' => $category_slug,
            )
        )
    ));

    if ($related_photos_query->have_posts()) : ?>
        <section class="related-photos">
            <h2>Photos apparentées</h2>
            <div class="related-photos-grid">
                <?php
                while ($related_photos_query->have_posts()) {
                    $related_photos_query->the_post();
                    // Utilise le template part photo-block (à adapter si besoin)
                    get_template_part('template-parts/photo-block');
                }
                ?>
            </div>
        </section>
    <?php
    endif;
    wp_reset_postdata();
endif;
?>


<?php 
get_footer(); 
?>