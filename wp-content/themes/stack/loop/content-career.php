<div class="col-sm-4">
    <div class="feature feature-1 boxed boxed--border">
    
	    <?php if(ebor_the_terms('career_category', ', ', 'name')) : ?>
	        <span class="label"><?php echo ebor_the_terms('career_category', ', ', 'name'); ?></span>
	    <?php endif; ?>
    
        <?php the_title('<h5>', '</h5><p>'. get_post_meta($post->ID, '_ebor_the_job_location', 1) .'</p>'); ?>
        
        <a href="<?php the_permalink(); ?>"><?php esc_html_e('View Posting', 'stack'); ?></a>
        
    </div><!--end feature-->
</div>