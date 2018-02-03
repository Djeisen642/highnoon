<?php 
	get_header(); 
	$total_results = $wp_query->found_posts;	
?>

<section class="text-center">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <h2><?php echo esc_html__( 'Showing results for', 'stack' ); ?> <span>&ldquo;<?php echo ucfirst( get_search_query() ); ?>&rdquo;</span></h2>
                <p class="lead">
                    <span><?php echo esc_html( $total_results ); ?></span> <?php esc_html_e( 'results found', 'stack' ); ?>
                </p>
            </div>
        </div><!--end of row-->
    </div><!--end of container-->
</section>

<section>
	<div class="container">
		<?php get_template_part( 'loop/loop-post', get_option( 'search_layout', 'search' ) ); ?>
	</div><!--end of container-->
</section>
            
<?php get_footer();