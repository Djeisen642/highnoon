<?php 
	get_header(); 
	
	$term = get_queried_object();
	echo ebor_breadcrumb_section( $term->name, $term->description, get_option('stack_career_breadcrumb_image', '') );	
?>

<section class="space--sm">
    <div class="container">
        <?php 
        	get_template_part('loop/loop', 'career');
        	get_template_part('inc/content-pagination', 'text'); 
        ?>
    </div><!--end of container-->
</section>
            
<?php
	get_template_part('inc/content-careers', 'cta');

	get_footer();