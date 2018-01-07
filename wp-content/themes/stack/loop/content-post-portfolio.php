<div class="masonry__item col-sm-6 text-center">
    <div class="project-thumb">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('large', array('class' => 'border--round')); ?>
        </a>
        <?php the_title('<h4><a href="'. get_permalink() .'">', '</a></h4><span>'. ebor_the_terms('category', ', ', 'name') .'</span>'); ?>
    </div>
</div><!--end item-->