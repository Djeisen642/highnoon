<?php 
	get_header(); 
	
	$term = get_queried_object();
	echo ebor_breadcrumb_section( $term->name, $term->description, get_option('stack_portfolio_breadcrumb_image', '') );	
?>

<section class="space--sm">
	<div class="container">
		<?php get_template_part('loop/loop-portfolio', get_option('stack_portfolio_layout', 'titles-inside-2')); ?>
	</div><!--end of container-->
</section>

<section class="space--sm">
	<div class="container">
		<?php get_template_part('inc/content-pagination', get_option('stack_pagination_layout', 'text')); ?>
	</div>
</section>
            
<?php get_footer();