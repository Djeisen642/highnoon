<li class="col-sm-6 col-xs-12">
    <a class="block" href="<?php the_permalink(); ?>">
        <article class="imagebg" data-scrim-bottom="8">
        
            <?php if(ebor_the_terms('category', ', ', 'name')) : ?>
                <span class="label"><?php echo ebor_the_terms('category', ', ', 'name'); ?></span>
            <?php endif; ?>
            
            <div class="background-image-holder">
                <?php the_post_thumbnail('large'); ?>
            </div>
            
            <div class="article__title">
                <span><?php the_time(get_option('date_format')); ?></span>
                <?php the_title('<h4>', '</h4>'); ?>
            </div>
            
        </article>
    </a>
</li>