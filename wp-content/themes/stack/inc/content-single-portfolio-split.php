<div class="row team-1">

    <div class="col-sm-6">
        <?php the_post_thumbnail('large', array('class' => 'border--round')); ?>
    </div>
    
    <div class="col-sm-6 col-md-5">
        <div class="switchable__text">
        
            <div class="text-block">
                <?php the_title('<h2>', '</h2><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
            </div>
            
            <div class="article__body">
            	<?php the_content(); ?>
            </div>
            
        </div>
    </div>
    
</div><!--end of row-->