<?php
	$button_1_link = get_option('stack_header_button_url_1', '#');
	$button_2_link = get_option('stack_header_button_url_2', '#');
?>

<div class="bar__module stack-header-buttons">
	
	<?php if( $button_1_link ) : ?>
		<a 
			class="<?php echo esc_attr(get_option('stack_header_button_class_1', 'btn btn--sm type--uppercase')); ?>" 
			href="<?php echo esc_url($button_1_link); ?>" 
			target="<?php echo esc_attr(get_option('stack_header_button_target_1')); ?>"
		>
			<span class="btn__text"><?php echo wp_kses_post(get_option('stack_header_button_text_1', 'Try Stack')); ?></span>
		</a>
	<?php endif; ?>
	
	<?php if( $button_2_link ) : ?>
		<a 
			class="<?php echo esc_attr(get_option('stack_header_button_class_2', 'btn btn--sm btn--primary type--uppercase')); ?>" 
			href="<?php echo esc_url($button_2_link); ?>" 
			target="<?php echo esc_attr(get_option('stack_header_button_target_2')); ?>"
		>
			<span class="btn__text"><?php echo wp_kses_post(get_option('stack_header_button_text_2', 'Buy Now')); ?></span>
		</a>
	<?php endif; ?>
	
</div><!--end module-->