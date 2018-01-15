<?php 
	get_header(); 
	the_post();
	
	echo ebor_breadcrumb_section( 
			get_option( 'stack_portfolio_title', 'Our Portfolio' ), 
			false, 
			get_option( 'stack_portfolio_breadcrumb_image', '' ) 
	);
?>

<section class="space--sm">
	<div class="container">
		<?php get_template_part( 'inc/content-single-portfolio', get_option( 'stack_single_portfolio_layout', 'split' ) ); ?>
	</div><!--end of container-->
</section>
            
<?php 
	//Post nav
	if( 'yes' == get_option( 'stack_single_portfolio_nav', 'yes' ) ){
		get_template_part( 'inc/content-portfolio', 'nav' );
	}
	
	get_footer();