<?php 

/**
 * variant_page_builder_edit_image_inline
 * 
 * Used for handling drag and drop uploading of images from variant
 * Grabs dropped image and converts to from base64 to .jpg
 * Uploads to WP media gallery, creates attachment post, and returns JSON array of URL & Alt
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_edit_image_inline(){ 
	
	/**
	 * Next, lets' grab an image file from the interwebs
	 */
	$mirror = wp_upload_bits(basename($_POST['image_data']['newImage']), '', file_get_contents($_POST['image_data']['newImageBase64']));
	
	if (!$mirror['error']) {
		$wp_filetype = wp_check_filetype(basename($_POST['image_data']['newImage']), null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', basename($_POST['image_data']['newImage'])),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		$attachment_id = wp_insert_attachment( $attachment, $mirror['file']);
		if (!is_wp_error($attachment_id)) {
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $mirror['file'] );
			wp_update_attachment_metadata( $attachment_id,  $attachment_data );
		}
	}
	
	$src = wp_get_attachment_image_src($attachment_id, 'full');
	
	wp_die(
		json_encode(
			array(
				'src' => $src[0], 
				'alt' => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true )
			)
		)
	);
	
}
add_action('wp_ajax_variant_page_builder_edit_image_inline', 'variant_page_builder_edit_image_inline');

function variant_page_builder_update_post() {
	
	if( isset($_POST['post_id']) && isset($_POST['param']) && isset($_POST['content']) ){
		
		$param = $_POST['param'];
		$post_id = (int) $_POST['post_id'];
		$content = $_POST['content'];
		
		if( 'post_title' == $param ){
			
			//Handle parameters if we're dealing with the post_title
			$args = array(
				'ID' => $post_id,
				$param => $content
			);
			wp_update_post($args, true);
		
		} elseif( substr($param, 0, 1) === '_' ){
			
			//Handle Parameters if we're dealing with a post meta key (indicated by leading underscore)	
			update_post_meta($post_id, $param, $content);
			
		}
	
	}
	
	wp_die();
}
add_action('wp_ajax_variant_page_builder_update_post', 'variant_page_builder_update_post');