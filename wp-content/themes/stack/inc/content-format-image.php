<section class="imagebg text-center height-60" data-overlay="5">

	<div class="background-image-holder">
		<?php the_post_thumbnail('full'); ?>
	</div>
	
	<div class="container pos-vertical-center">
		<div class="row">
			<div class="col-sm-12">
				<div class="article__title">
					<?php 
						the_title('<h1>', '</h1>'); 
						
						if( 'yes' == get_option('stack_single_post_meta', 'yes') ){
							get_template_part('inc/content-post', 'meta');
						}
					?>
				</div>
			</div>
		</div><!--end of row-->
	</div><!--end of container-->
	
	<?php if( 'yes' == get_option('stack_image_format_post_author', 'yes') ) : ?>
		<div class="pos-absolute pos-bottom col-xs-12 text-center format-image-post-author">
			<div class="article__author">
				<?php echo get_avatar( get_the_author_meta('email'), 52 ); ?>
				<h6 class="type--uppercase"><?php echo esc_html(get_the_author()); ?></h6>
			</div>
		</div>
	<?php endif; ?>
	
</section>