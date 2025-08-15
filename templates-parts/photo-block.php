<div class="photo-block">
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium_large'); ?>
        </a>
        <div class="photo-overlay">
            <a href="#" class="icon-fullscreen" data-full-src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" data-reference="<?php the_field('reference'); ?>" data-category="<?php echo strip_tags(get_the_term_list(get_the_ID(), 'categorie')); ?>">
                </a>
            <a href="<?php the_permalink(); ?>" class="icon-eye">
                </a>
            <div class="photo-meta">
                <span class="photo-reference"><?php the_field('reference'); ?></span>
                <span class="photo-category"><?php echo strip_tags(get_the_term_list(get_the_ID(), 'categorie')); ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>