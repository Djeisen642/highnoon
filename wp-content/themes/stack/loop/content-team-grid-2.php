<div class="col-sm-6">
    <div class="feature feature-9">
        <a href="<?php the_permalink(); ?>">
        	<?php the_post_thumbnail('large', array('class' => 'border--round')); ?>
        </a>
        <?php the_title('<h4>', '</h4><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
    </div>
</div>