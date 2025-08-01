<?php get_header(); ?>

<main class="main-content">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article class="single-photo">
                <header class="photo-header">
                    <h1 class="photo-title"><?php the_title(); ?></h1>
                    <div class="photo-meta">
                        <span class="photo-date">Publié le <?php echo get_the_date('d F Y'); ?></span>
                        <?php if (has_category()) : ?>
                            <span class="photo-categories"><?php the_category(', '); ?></span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="photo-featured">
                        <?php the_post_thumbnail('large', array('class' => 'photo-main')); ?>
                    </div>
                <?php endif; ?>

                <div class="photo-content">
                    <?php the_content(); ?>
                </div>

                <footer class="photo-footer">
                    <?php if (has_tag()) : ?>
                        <div class="photo-tags">
                            <?php the_tags('Mots-clés : ', ', '); ?>
                        </div>
                    <?php endif; ?>
                    
                    <button class="contact-btn" data-modal="contact">
                        Cette photo vous intéresse ?
                    </button>
                </footer>

                <!-- Navigation entre photos -->
                <nav class="photo-navigation">
                    <?php
                    $prev = get_previous_post();
                    $next = get_next_post();
                    ?>
                    <?php if ($prev) : ?>
                        <a href="<?php echo get_permalink($prev); ?>" class="nav-prev">
                            Photo précédente
                        </a>
                    <?php endif; ?>
                    <?php if ($next) : ?>
                        <a href="<?php echo get_permalink($next); ?>" class="nav-next">
                            Photo suivante
                        </a>
                    <?php endif; ?>
                </nav>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>