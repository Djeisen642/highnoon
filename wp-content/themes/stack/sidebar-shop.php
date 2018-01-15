<div class="row">
	<div class="col-sm-12">
		
		<?php do_action('ebor_before_sidebar'); ?>
		
			<div class="sidebar boxed boxed--border boxed--lg bg--secondary">
				<div class="row">
					<?php 
						do_action('ebor_before_sidebar_content');
						dynamic_sidebar('shop');
						do_action('ebor_after_sidebar_content'); 
					?>
				</div>
			</div>
			
		<?php do_action('ebor_after_sidebar'); ?>
		
	</div>
</div>