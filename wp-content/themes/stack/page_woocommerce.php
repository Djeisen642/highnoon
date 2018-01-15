<?php
	get_header();
	the_post();
	
	$title           = get_option( 'stack_shop_title', 'Our Shop' );
	$container_class = false;
	
	if( class_exists( 'woocommerce' ) && function_exists( 'is_account_page' ) ){
		if( is_account_page() ){
			$container_class = 'bg--secondary';
			$title           = get_the_title();
		}
	}
	
	echo ebor_breadcrumb_section( $title );
?>

<section class="<?php echo esc_attr( $container_class ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
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
            
<?php get_footer();