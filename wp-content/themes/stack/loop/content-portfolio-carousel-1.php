<li class="<?php echo esc_attr(get_option('stack_portfolio_carousel_columns', 'col-sm-6')); ?> text-center">
    <div class="project-thumb">
    
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('large', array('class' => 'border--round')); ?>
        </a>
        
        <?php the_title('<h4>', '</h4><span>'. ebor_the_terms('portfolio_category', ', ', 'name') .'</span>'); ?>
        
    </div>
</li>