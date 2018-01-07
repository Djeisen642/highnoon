<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $nav_menu
 * @var $el_class
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Wp_Custommenu
 */
$title = $nav_menu = $el_class = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$output = '<footer class="unpad--top unpad--bottom">';
$type = 'WP_Nav_Menu_Widget';
$args = array(
	'before_title' => '<h6 class="type--uppercase">',
	'after_title' => '</h6>'
);
global $wp_widget_factory;
// to avoid unwanted warnings let's check before using widget
if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
	ob_start();

	the_widget( $type, $atts, $args );
	$output .= ob_get_clean();
	
	$output .= '</footer>';

	echo ( $output ) ? $output : false;
} else {
	echo wp_kses_post($this->debugComment( 'Widget ' . esc_attr( $type ) . 'Not found in : vc_wp_custommenu' ));
}
