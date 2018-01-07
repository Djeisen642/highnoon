<div class="masonry__item col-sm-6 text-center" data-masonry-filter="<?php echo esc_attr(ebor_the_terms('portfolio_category', ',', 'name')); ?>">
    <div class="project-thumb">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('large', array('class' => 'border--round')); ?>
        </a>
        <?php the_title('<h4>', '</h4><span>'. ebor_the_terms('portfolio_category', ', ', 'name') .'</span>'); ?>
    </div>
</div><!--end item-->