<?php
	if( 'no' == get_option('stack_portfolio_filters', 'yes') ){
		return false;	
	}
?>

<div class="masonry-filter-container text-center">
    <span><?php esc_html_e('Category:', 'stack'); ?></span>
    <div class="masonry-filter-holder">
        <div class="masonry__filters" data-filter-all-text="<?php esc_attr_e('All Categories', 'stack'); ?>"></div>
    </div>
</div><!--end masonry filters-->

<hr />