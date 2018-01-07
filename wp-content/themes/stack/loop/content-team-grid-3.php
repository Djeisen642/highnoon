<div id="<?php the_ID(); ?>" <?php post_class('col-sm-4'); ?>>
    <div class="feature feature-8">
    	<a href="<?php the_permalink(); ?>" class="image-wrapper background-image-holder">
       		<?php the_post_thumbnail('medium'); ?>
        </a>
        <?php the_title('<h5 data-ajax-identifier="post_title"><a class="h5" href="'. get_permalink() .'">', '</a></h5><span data-ajax-identifier="_ebor_the_job_title">'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
    </div>
</div>