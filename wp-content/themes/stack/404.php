<?php get_header(); ?>

<section class="height-100 bg--dark text-center">
	<div class="container pos-vertical-center">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="h1--large"><?php esc_html_e( "404", 'stack' ); ?></h1>
				<p class="lead"><?php esc_html_e( "The page you were looking for doesn't appear to exist.", 'stack' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( "Go back to home page", 'stack' ); ?></a>
			</div>
		</div><!--end of row-->
	</div><!--end of container-->
</section>

<?php get_footer();