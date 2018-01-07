<?php 
	if(!( 'post' == get_post_type() ))
		return false;

	$terms = get_the_terms( $post->ID , 'category', 'string');
	$term_ids = array_values( wp_list_pluck( $terms,'term_id' ) );
	
	$related_query = new WP_Query( 
		array(
			'post_type' => 'post',
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
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
	
		<div class="row text-block loop-related">
			<div class="col-sm-12">
				<h3><?php esc_html_e('More recent stories', 'stack'); ?></h3>
			</div>
		</div><!--end of row-->
		
		<div class="row">
			<div class="col-sm-12">
				<div class="masonry">
					<div class="row">
						<div class="masonry__container">
							<div class="masonry__item col-md-4 col-sm-6"></div>
							
							<?php
								if ( $related_query->have_posts() ) : while ( $related_query->have_posts() ) : $related_query->the_post();
								
									/**
									 * Get blog posts by blog layout.
									 */
									get_template_part('loop/content-post', 'cards');
								
								endwhile;	
								else : 
									
									/**
									 * Display no posts message if none are found.
									 */
									get_template_part('loop/content','none');
									
								endif;
								
								wp_reset_postdata();
							?>
							
						</div><!--end of masonry container-->
					</div><!--end row-->
				</div><!--end masonry-->
			</div>
		</div>
		
	</div><!--end of container-->
</section>