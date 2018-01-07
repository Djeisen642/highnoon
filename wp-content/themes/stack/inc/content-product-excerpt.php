<?php
	if( 'no' == get_option('stack_product_excerpt', 'no') ){
		return false;	
	}
?>

<span class="block"><?php echo get_the_excerpt(); ?></span>