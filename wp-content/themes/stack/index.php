<?php 
	get_header(); 
	
	echo ebor_breadcrumb_section( 
			get_option( 'stack_blog_title', 'Our Blog' ), 
			get_option( 'stack_blog_description', '' ), 
			get_option( 'stack_blog_breadcrumb_image', '' ) 
	);
?>

<section class="space--sm">
	<div class="container">
		<?php get_template_part( 'loop/loop-post', get_option( 'stack_blog_layout', 'cards-sidebar' ) ); ?>
	</div><!--end of container-->
</section>
            
<?php get_footer();