<div class="nav-container">
	<nav class="bar bar-toggle">
		<div class="container">
			<div class="row">
			
				<div class="col-md-1 col-sm-2 col-xs-3">
					<div class="bar__module">
						<?php get_template_part('inc/content-header', 'logo'); ?>
					</div><!--end module-->
				</div>
				
				<div class="col-md-11 col-sm-10 col-xs-9">
					<div class="bar__module">
						<div class="modal-instance pull-right">
							<a class="modal-trigger menu-toggle" href="#">
								<i class="stack-interface stack-menu"></i>
							</a>
							<div class="modal-container menu-fullscreen">
								<div class="modal-content" data-width="100%" data-height="100%">
									<div class="pos-vertical-center pos-asbolute text-center">
										<div class="heading-block">
											<?php get_template_part('inc/content-header', 'logo'); ?>
											<p class="lead"><?php echo esc_html(get_bloginfo('description')); ?></p>
										</div>
										<?php  
											if ( has_nav_menu( 'sidebar' ) ){
											    wp_nav_menu( 
											    	array(
												        'theme_location'    => 'sidebar',
												        'depth'             => 0,
												        'container'         => false,
												        'container_class'   => false,
												        'menu_class'        => 'menu-vertical add-h4'
											        )
											    ); 
											}
										?>
									</div><!--end centered-->
									<div class="pos-absolute pos-bottom text-center">
										<?php get_template_part('inc/content-header', 'social'); ?>
										<?php get_template_part('inc/content-footer', 'copyright'); ?>
									</div>
								</div>
							</div>
						</div><!--end of modal instance-->
					</div><!--end module-->
				</div><!--end columns-->
					
			</div><!--end of row-->
		</div><!--end of container-->
	</nav>
</div>