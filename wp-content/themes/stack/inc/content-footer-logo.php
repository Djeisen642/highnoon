<?php
	$alt        = get_option('stack_logo_alt_text', 'logo');
	$logo_dark  = get_option('stack_logo_dark', EBOR_THEME_DIRECTORY . 'style/img/logo-dark.png');
	$logo_light = get_option('stack_logo_light', EBOR_THEME_DIRECTORY . 'style/img/logo-light.png');
	
	if(!( '' == get_option('stack_footer_logo_dark', '') )){
		$logo_dark = get_option('stack_footer_logo_dark', '');
	}
	
	if(!( '' == get_option('stack_footer_logo_light', '') )){
		$logo_light = get_option('stack_footer_logo_light', '');
	}
?>

<a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-holder logo-holder">
	<img class="logo logo-dark" alt="<?php echo esc_attr($alt); ?>" src="<?php echo esc_url($logo_dark); ?>" />
	<img class="logo logo-light" alt="<?php echo esc_attr($alt); ?>" src="<?php echo esc_url($logo_light); ?>" />
</a>