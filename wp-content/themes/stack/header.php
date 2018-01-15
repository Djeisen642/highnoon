<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-smooth-scroll-offset="<?php echo (int) esc_attr( get_option( 'stack_scroll_offset', '0' ) ); ?>">

<a href="#" id="start" title="<?php esc_attr_e('Start', 'stack'); ?>"></a>

<?php
	do_action( 'ebor_before_header' );
	
	/**
	 * First, we need to check if we're going to override the header layout (with post meta)
	 * Or if we're going to display the global choice from the theme options.
	 * This is what ebor_get_header_layout is in charge of.
	 */
	get_template_part( 'inc/layout-header', ebor_get_header_layout() );
	
	do_action( 'ebor_after_header' );
?>

<div class="main-container">

<?php do_action( 'ebor_before_content' );