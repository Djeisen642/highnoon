<?php $icons = get_post_meta( $post->ID, '_ebor_team_social_icons', true ); ?>

<div class="row switchable switchable--switch team-1">

    <div class="col-sm-6">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large', array('class' => 'border--round')); ?></a>
    </div>
    
    <div class="col-sm-6 col-md-5">
        <div class="switchable__text">
        
            <div class="text-block">
                <?php the_title('<h2>', '</h2><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
            </div>
            
            <?php the_content(); ?>
            
            <?php if( is_array($icons) ) : ?>
	            <ul class="social-list list-inline list--hover">
	            	<?php 
	            		foreach( $icons as $key => $icon ){
	            			if(!( isset( $icon['_ebor_social_icon_url'] ) ))
	            				continue;
	            				
	            			echo '<li><a href="'. $icon['_ebor_social_icon_url'] .'" target="_blank"><span class="socicon '. $icon['_ebor_social_icon'] .' icon icon--xs"></span></a></li>';
	            		}
	            	?>
	            </ul>
            <?php endif; ?>
            
        </div>
    </div>
    
</div><!--end of row-->