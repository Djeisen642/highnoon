<div class="col-md-4">
	
	<?php do_action('ebor_before_sidebar'); ?>
	
		<div class="sidebar boxed boxed--border boxed--lg bg--secondary sidebar-loop">
			<?php 
				do_action('ebor_before_sidebar_content');
				dynamic_sidebar('shop');
				do_action('ebor_after_sidebar_content');
			?>
		</div>
		
	<?php do_action('ebor_after_sidebar'); ?>
	
</div>