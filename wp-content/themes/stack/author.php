<?php 
	get_header(); 
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	
	echo ebor_breadcrumb_section( 
			esc_html__( 'Posts By: ','stack' ) . $author->display_name, 
			false, 
			get_option( 'stack_blog_breadcrumb_image', '' ) 
	);
?>

<section class="space--sm unpad--top">
	<div class="container">
		<?php get_template_part( 'loop/loop-post', get_option( 'stack_blog_layout', 'cards-sidebar' ) ); ?>
	</div><!--end of container-->
</section>
            
<?php get_footer();