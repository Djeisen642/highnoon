<?php
	/* Template Name: Page With No Sidebar */
	get_header();
	the_post();
	
	echo ebor_breadcrumb_section( get_the_title() );
?>

<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 article__body post-content">
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

<?php 
	if( comments_open() || get_comments_number() ){
		comments_template();
	}
            
	get_footer();