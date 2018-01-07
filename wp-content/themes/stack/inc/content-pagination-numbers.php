<div class="pagination">
	<?php 
		/**
		 * Post pagination, use ebor_pagination() first and fall back to default
		 */
		echo function_exists('ebor_pagination') ? ebor_pagination() : posts_nav_link();
	?>
</div>