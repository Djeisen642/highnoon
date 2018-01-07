<?php $background = get_option('stack_header_background', 'original--bg'); ?>

<div class="nav-container">
	<nav class="bar bar-1 <?php echo esc_attr($background); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="bar__module">
						<?php get_template_part('inc/content-header', 'logo'); ?>		
					</div><!--end module-->
				</div>
			</div><!--end of row-->
		</div><!--end of container-->
	</nav><!--end bar-->
</div>