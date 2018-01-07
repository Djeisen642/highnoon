/**
 * Drag and drop image replacement
 */
jQuery(document).on('dragenter', '.viu', function(){
	jQuery('body').addClass('dragging');
});
jQuery(document).on('dragexit', '.viu', function(){
	jQuery('body').removeClass('dragging');
});
jQuery(document).on('mouseenter', '.viu', function(){
	jQuery('body').removeClass('dragging');
});

jQuery('body').on('dragover', 'img, .imagebg', function(e) {
	jQuery(this).css('opacity', '0.5');
	e.preventDefault();
	e.stopPropagation();
});
jQuery('body').on('dragleave', 'img, .imagebg', function(e) {
	jQuery(this).css('opacity', '1');
});

document.addEventListener("drop", function( event ) {
	
	// prevent default action (open as link for some elements)
	event.preventDefault();
	event.stopPropagation();
	
	jQuery('body').removeClass('dragging');
	
	if( event.target.nodeName == 'IMG' && event.target.hasAttribute('vic') || jQuery(event.target).hasClass('imagebg') ){
		
		var file = event.dataTransfer.files[0],
			fileReader = new FileReader();
		
		fileReader.onload = (function(file) {
			
			return function(e) {
			
				//Sets the global ID so that Variant knows what element we're working with
				mr_variant.wp.editImageID = jQuery(event.target).attr('vic');
				
				if( jQuery(event.target).hasClass('imagebg') ){
					mr_variant.wp.editImageID = jQuery('.background-image-holder img', event.target).attr('vic');
				}
				
				var image = {
					newImage: jQuery(file).get(0).name,
					newImageBase64: e.target.result
				};

	        	//James: Immediately replace visible image's src with the base64 
	       		jQuery('.viu .'+mr_variant.wp.editImageID).attr('src',e.target.result).css('opacity', '1');
	       		
				mr_variant.wp.editImage(image);
				
			};
			
		})(file);
		
		fileReader.readAsDataURL(file);
	  
	}
      
}, false);

mr_variant.wp.editImage = function(image) {

    if( image.newImageBase64 ){

    	jQuery.ajax({
			type: "POST",
			url: wp_data.ajax_url,
			data: {
				action: 'variant_page_builder_edit_image_inline',
				post_id: wp_data.post_id,
				image_data: image
			},
			error: function(response) {
				console.log(response);
			},
			success: function(response) {
				var attachment = JSON.parse(response);
				//console.log(attachment);
				mr_variant.wp.saveImage(attachment.src, attachment.alt);
			}
		});
    		
    } else {
    	
	    // variable for the wp.media file_frame
	    var file_frame;
	
	    // if the file_frame has already been created, just reuse it
		if ( file_frame ) {
			file_frame.open();
			return;
		} 
	
		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select or Upload Media',
			button: {
				text: 'Use this media'
			},
			multiple: false // set this to true for multiple file selection
		});
	
		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();
			//return the attachment url and attachment alt to our save functions
			mr_variant.wp.saveImage(attachment.url, attachment.alt);
		});
	
		file_frame.open();
	
    }
    	
};

/**
 * AJAX update post
 * 
 * Deferred for future enhancement
 */
/*mr_variant.wp.updatePost = function($post_id, $param, $content){

	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_update_post',
			post_id: $post_id,
			param: $param,
			content: $content
		},
		error: function(response) {
			console.log(response);
		},
	    success: function(response) {
	        console.log(response);
	    }
	});
	
}*/

jQuery('.wysiwyg-save-changes').click(function(){
	
	var $target = jQuery('.wysiwyg-active');
	
	jQuery('.wp-editor-overlay').removeClass('active');
	
	//Handle editing post objects
	if( typeof tinymce != "undefined" && $target.attr('data-ajax-identifier') ) {
		
		var editor = tinyMCE.activeEditor,
			$param = $target.attr('data-ajax-identifier'),
			$post_id = $target.parents('.hentry').attr('id');
			
		if( editor && editor instanceof tinymce.Editor ) {
			var $content = editor.getContent({ format: 'text' });
		    $target.html( $content );
		    mr_variant.wp.updatePost($post_id, $param, $content);
		}
	
	//Just editing plain HTML objects	
	} else if( typeof tinymce != "undefined" ){
		
	    var editor = tinyMCE.activeEditor;
	    if( editor && editor instanceof tinymce.Editor ) {
	        jQuery('[vic="'+ $target.attr('vic') +'"]').html( editor.getContent() );
	        jQuery('[vic="'+ $target.attr('vic') +'"], [vic="'+ $target.attr('vic') +'"] *').attr('contenteditable', 'true').trigger('input').removeAttr('contenteditable');
	    }
	    
	}
	
	$target.removeClass('wysiwyg-active');
	mr_variant.variantNotification('Text Updated', 'circle-check', 'success', 1500);
	
	return false;
});