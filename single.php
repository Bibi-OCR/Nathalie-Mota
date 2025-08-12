<?php
/**
 * Template Name: Blog
 */

get_header();
?>

<main class="main-content-post">
    <?php
    if (have_posts()) : // Vérifie s'il y a des articles à afficher.
        while (have_posts()) : // Tant qu'il y a des articles à afficher, boucle.
            the_post(); // Charge l'article actuel.
    ?>
    <div class="post">
    <h1 class="post-title"><?php the_title(); ?></h1>
        <div>
            <?php the_content(); // Affiche le contenu de l'article. ?>
        </div>
    <?php
        endwhile;
    endif; // Fin de la vérification de la présence d'articles.
    ?>
</main>

<?php
get_footer();