<?php 
	get_header(); 
	$title = $description = false;
	
	if( is_date() ){
		
		if ( is_day() ){
			$title = get_the_date();
		} elseif ( is_month() ){
			$title = get_the_date('F Y' );
		} elseif ( is_year() ){
		    $title = get_the_date('Y');
		}
		
	} else {
		
		$term        = get_queried_object();
		$title       = $term->name;
		$description = $term->description;
		
	}
	
	echo ebor_breadcrumb_section( 
			$title, 
			$description, 
			get_option( 'stack_blog_breadcrumb_image', '' ) 
	);
?>

<section class="space--sm unpad--top">
	<div class="container">
		<?php get_template_part( 'loop/loop-post', get_option( 'stack_blog_layout', 'cards-sidebar' ) ); ?>
	</div><!--end of container-->
</section>
            
<?php get_footer();