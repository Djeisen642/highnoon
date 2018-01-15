<?php 
	get_header(); 
	
	echo ebor_breadcrumb_section( 
			get_option( 'stack_team_title', 'Our Team' ), 
			false, 
			get_option( 'stack_team_breadcrumb_image', '' ) 
	);
?>

<section class="space--sm">
	<div class="container">
		<?php get_template_part( 'loop/loop-team', 'grid-1' ); ?>
	</div><!--end of container-->
</section>
            
<?php 
	//Post nav
	if( 'yes' == get_option( 'stack_single_team_nav', 'yes' ) ){
		get_template_part( 'inc/content-team', 'nav' );
	}
	
	get_footer();