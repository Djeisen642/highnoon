<?php $alt = get_option('stack_logo_alt_text', 'logo'); ?>

<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-holder">
	<img class="logo logo-dark" alt="<?php echo esc_attr($alt); ?>" src="<?php echo esc_url(get_option('stack_logo_dark', EBOR_THEME_DIRECTORY . 'style/img/logo-dark.png')); ?>" />
	<img class="logo logo-light" alt="<?php echo esc_attr($alt); ?>" src="<?php echo esc_url(get_option('stack_logo_light', EBOR_THEME_DIRECTORY . 'style/img/logo-light.png')); ?>" />
</a>