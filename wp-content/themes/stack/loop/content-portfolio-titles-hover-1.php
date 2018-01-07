<div class="masonry__item col-sm-12" data-masonry-filter="<?php echo esc_attr(ebor_the_terms('portfolio_category', ',', 'name')); ?>">
    <div class="project-thumb hover-element border--round">
        <a href="<?php the_permalink(); ?>">
        
            <div class="hover-element__initial">
                <div class="background-image-holder">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            </div>
            
            <div class="hover-element__reveal" data-scrim-top="5">
                <div class="project-thumb__title">
                    <?php the_title('<h4>', '</h4><span>'. ebor_the_terms('portfolio_category', ', ', 'name') .'</span>'); ?>
                </div>
            </div>
            
        </a>
    </div>
</div><!--end item-->