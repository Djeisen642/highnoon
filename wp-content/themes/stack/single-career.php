<?php 
	get_header(); 
	the_post();
	
	echo ebor_breadcrumb_section( get_the_title(), false, get_option('stack_career_breadcrumb_image', '') );
?>

<section class="switchable">
    <div class="container">
        <div class="row">
        
            <div class="col-sm-8 col-md-7 article__body">
                <?php
                	the_content();
                	wp_link_pages();
                ?>   
            </div>
            
            <?php get_sidebar('career'); ?>
            
        </div><!--end of row-->
    </div><!--end of container-->
</section>

<?php
	get_template_part('inc/content-careers', 'cta');
	get_template_part('loop/loop-careers', 'related');
	get_footer();