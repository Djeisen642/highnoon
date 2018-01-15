<?php
	get_header();
	the_post();
	
	$active = ( isset($_GET['layout']) ) ? 'yes' : get_option('stack_post_sidebar', 'no');
	$class = ( 'yes' == $active ) ? 'col-sm-12 col-md-8 blog-post--sidebar' : 'col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2';
	
	if( 'yes' == get_option( 'stack_blog_single_breadcrumbs', 'no' ) ){
		echo ebor_breadcrumb_section( 
			get_option('stack_blog_title', 'Our Blog'), 
			get_option('stack_blog_description', ''), 
			get_option('stack_blog_breadcrumb_image', '') 
		);
	}
	
	get_template_part('inc/content-format', get_post_format());
?>

<section>
	<div class="container">
		<div class="row">
		
			<div class="<?php echo esc_attr($class); ?>">
				<article>
				
					<div class="article__title text-center">
						<?php 
							//Post title on/off
							if( 'yes' == get_option('stack_single_post_title', 'yes') ){
								the_title('<h1 class="h2">', '</h1>'); 
							}
							
							//Post meta on/off
							if( 'yes' == get_option('stack_single_post_meta', 'yes') ){
								get_template_part('inc/content-post', 'meta');
							}
						?>
					</div><!--end article title-->
					
					<div class="article__body post-content">
						
						<div class="featured-image-holder">
							<?php 
								//Featured image on/off
								if( 'yes' == get_option('stack_single_featured_image', 'no') ){
									the_post_thumbnail('large', array('class' => 'border--round box-shadow-wide'));
								}
							?>
						</div>
						
						<?php
							the_content();
							wp_link_pages();
						?>
						
						<div class="post__tags">
							<?php 
								if( 'yes' == get_option( 'stack_single_post_tags', 'yes' ) ){
									the_tags(); 
								}
							?>
						</div>
						
					</div>
					
					<?php
						//Single post sharing
						if( 'yes' == get_option( 'stack_single_post_sharing', 'yes' ) ){
							get_template_part( 'inc/content-post', 'sharing' );
						}
					?>
					
				</article><!--end item-->
			</div>	
			
			<?php 
				if( 'yes' == $active )
					get_sidebar(); 
			?>
			
		</div><!--end of row-->
	</div><!--end of container-->
</section>

<?php 
	if( 'yes' == get_option( 'stack_single_post_author', 'yes' ) ){
		get_template_part( 'inc/content-post', 'author' );
	}
	
	//Single post CTA
	if( 'yes' == get_option('stack_single_post_cta', 'yes') ){
		get_template_part( 'inc/content-post', 'cta' );
	}
	
	if( comments_open() || get_comments_number() ){
		comments_template();
	}
	
	//Post nav
	if( 'yes' == get_option( 'stack_single_post_nav', 'yes' ) ){
		get_template_part( 'inc/content-post', 'nav' );
	}
	
	//Related posts	
	if( 'yes' == get_option( 'stack_single_post_related', 'yes' ) ){
		get_template_part( 'loop/loop-post', 'related' );
	}
	
	get_footer();