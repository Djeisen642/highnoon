<div class="col-md-4 hidden-sm">
	
	<?php do_action('ebor_before_sidebar'); ?>
	
		<div class="sidebar boxed boxed--border boxed--lg bg--secondary">
			<?php 
				do_action('ebor_before_sidebar_content');
				dynamic_sidebar('primary');
				do_action('ebor_after_sidebar_content'); 
			?>
		</div>
		
	<?php do_action('ebor_after_sidebar'); ?>
	
</div>