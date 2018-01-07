<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $css
 * @var $el_id
 * @var $equal_height
 * @var $content_placement
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row_Inner
 */
$el_class = $equal_height = $content_placement = $css = $el_id = '';
$disable_element = '';
$output = $after_output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $css, $image);
if(!( isset($image[0][0]) ))
	$image[0][0] = false;

$el_class = $this->getExtraClass( $el_class );
$css_classes = array(
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_inner',
	'vc_row-fluid',
	$el_class
);
if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
	'border',
	'background',
) ) ) {
	$css_classes[] = 'vc_row-has-fill';
}

if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="row ' . esc_attr( trim( $css_class ) ) . '"';

if( 'tab' == $ebor_type ){
	
	$background_image = ($image[0][0]) ? '<div class="background-image-holder"><img alt="background" src="'. $image[0][0] .'" /></div>' : false;
	$overlay = ($image[0][0]) ? 'data-overlay="4"' : false;
	$gradient = ($ebor_gradient) ? "data-gradient-bg='". $ebor_gradient ."'" : false;
	
	$output .= '
		<li>
			<div class="tab__title">
				<span class="h5">'. $ebor_title .'</span>
			</div>
			
			<div class="tab__content">
				<section class="'. $el_class .'" '. $overlay .' '. $gradient .'>
				
					'. $background_image .'
					
					<div class="container">
						<div ' . implode( ' ', $wrapper_attributes ) . '>'. wpb_js_remove_wpautop( $content ) .'</div><!--end row-->
					</div><!--end of container-->
				</section>
			</div>
		</li>
	';
			
	echo ( $output ) ? $output : false;
	
} else {
	
	$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= wpb_js_remove_wpautop( $content );
	$output .= '</div>';
	$output .= $after_output;
	
	echo ( $output ) ? $output : false;

}
