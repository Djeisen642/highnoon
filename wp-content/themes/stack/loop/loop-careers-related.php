<?php 
	if(!( 'career' == get_post_type() ))
		return false;

	$terms = get_the_terms( $post->ID , 'career_category', 'string');
	$term_ids = array_values( wp_list_pluck( $terms,'term_id' ) );
	
	$related_query = new WP_Query( 
		array(
			'post_type' => 'career',
			'tax_query' => array(
				array(
					'taxonomy' => 'career_category',
					'field' => 'id',
					'terms' => $term_ids,
					'operator'=> 'IN' //Or 'AND' or 'NOT IN'
				)
			),
			'posts_per_page' => 3,
			'ignore_sticky_posts' => 1,
			'orderby' => 'rand',
			'post__not_in' => array($post->ID)
		) 
	);
	
	if( $related_query->found_posts == 0)
		return false;
?> 

<section class="bg--secondary">
    <div class="container">
        <div class="row">
        
            <div class="col-sm-12">
                <h4><?php esc_html_e('Similar Openings', 'stack'); ?></h4>   
            </div>
            
            <?php
            	if ( $related_query->have_posts() ) : while ( $related_query->have_posts() ) : $related_query->the_post();
            	
            		/**
            		 * Get blog posts by blog layout.
            		 */
            		get_template_part('loop/content', 'career');
            	
            	endwhile;	
            	else : 
            		
            		/**
            		 * Display no posts message if none are found.
            		 */
            		get_template_part('loop/content','none');
            		
            	endif;
            ?>
            
        </div><!--end of row-->
    </div><!--end of container-->
</section>