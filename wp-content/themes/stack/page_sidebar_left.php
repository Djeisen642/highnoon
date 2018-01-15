<?php
	/* Template Name: Page With Left Sidebar */
	get_header();
	the_post();
	
	$active = is_active_sidebar( 'page' );
	$class  = ( $active ) ? 'col-sm-10 col-md-8' : 'col-sm-12';
	
	echo ebor_breadcrumb_section( get_the_title() );
?>

<section>
	<div class="container">
		<div class="row">
			
			<?php 
				if( $active ){
					get_sidebar( 'page' ); 
				}
			?>
			
			<div class="<?php echo esc_attr( $class ); ?> article__body post-content">
				<article>
					<?php
						the_content();
						wp_link_pages();
					?>
				</article>
			</div>
			
		</div><!--end of row-->
	</div><!--end of container-->
</section>

<?php 
	if( comments_open() || get_comments_number() ){
		comments_template();
	}
            
	get_footer();