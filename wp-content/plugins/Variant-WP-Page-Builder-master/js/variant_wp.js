/**
 * Replace broken images
 * 
 * Added this via jQuery as it's much faster for Variant to load doing this, than it is to check
 * file_exists over every image in PHP, nearly 3 seconds difference.
 * 
 * @blame tommus
 * @since v1.0.0
 */
jQuery('img').on('error', function(){
	jQuery(this).attr('src', wp_data.plugin_url + 'img/placeholder.png');
});
jQuery('.vhv img').on('error', function(){
	jQuery(this).attr('src', wp_data.plugin_url + 'img/placeholder-sidebar.png');
});
jQuery('body').removeClass('admin-bar');

/**
 * Disable backslash entry
 */
document.onkeydown = function (e) {
	if(e.which == 220){
		return false;
	}
}

mr_variant.wp.checkUser = function(){

	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_check_user_ajax'
		},
		error: function(response) {
			 mr_variant.variantNotification('Error Checking User', 'circle-x', 'error', 7000);
		},
		success: function(response) {
			if( response == 'false' ){
				jQuery('.variant-login-modal').modal({
				    autoResize: true,
				    overlayClose: true,
				    opacity: 0,
				    minHeight: 620,
				    overlayCss: {
				        "background-color": "#3e3e3e"
				    },
				    closeClass: 'vex',
				    onShow: function() {
				        setTimeout(function() {
				            jQuery('.simplemodal-container').addClass('vko');
				            jQuery('.simplemodal-overlay').addClass('vko');
				        }, 100);
				        mr_variant.initSizes();
				    },
				    onClose: function() {
				        setTimeout(function() {
				            jQuery.modal.close();
				            mr_variant.initSizes();
				        }, 300);
				        jQuery('.simplemodal-container').removeClass('vko');
				        jQuery('.simplemodal-overlay').removeClass('vko');
				    }
				});
			}
		}
	});
	
}

mr_variant.wp.editImage = function(image) {
    	
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
		
		//Tell variant a change has been made
		mr_variant.setUnsaved();
	});

	file_frame.open();

};

/**
 * mr_variant.wp.load
 * 
 * Loads in data from the _variant_page_builder_variant meta key for the given post
 * We load this in via AJAX so that is can be properly returned and parsed as JSON
 * 
 * Returns error if no meta is found.
 * 
 * @blame tommus
 * @since v1.0.0
 * 
 * @toDo: Setup request so we receive a success response rather than an error if no meta found
 */
mr_variant.wp.load = function() {

	jQuery.ajax({
    	type: "POST",
    	url: wp_data.ajax_url,
    	dataType: 'json',
    	data: {
    		action: 'variant_page_builder_load_variant',
    		post_id: wp_data.post_id
    	},
    	error: function(response) {
			mr_variant.variantNotification('Error loading page.', 'circle-x', 'error', 7000);
    	},
    	success: function(response) {
    		mr_variant.importState(response);
    	},
      	complete: function(){
	        mr_variant.wp.renderNavContainer(wp_data.current_page.header_layout);
	        mr_variant.wp.renderFooter(wp_data.current_page.footer_layout);
	        mr_variant.wp.showWelcomeModal();
	        
	        //Fix Pricing Tables
            jQuery('span.checkmark.bg--primary-1:empty').text(' ');
            
            //Add alternate area to CF7 options
            jQuery('.cf7-holder').each(function(){
            	var $this = jQuery(this);
            	if( !$this.next('.shortcode-holder-wrapper').length ){
            		$this.clone().insertAfter($this).removeClass('cf7-holder').addClass('hide-form shortcode-holder-wrapper').html('<div class="shortcode-holder lead" data-shortcode=""></div>');
            	}
            });
      	}
    });
    
};

/**
 * Create a new page
 */
mr_variant.wp.newPage = function($pageTitle, $saveType = 'newpage', $json, $html){
	
	mr_variant.wp.checkUser();
	
  	mr_variant.startLoading('.viu');
  	
  	if( 'newpage' == $saveType ){
  		
		jQuery.ajax({
			type: "POST",
			url: wp_data.ajax_url,
			dataType: 'json',
			data: {
				action: 'variant_page_builder_create_new_page',
				title: $pageTitle
			},
			error: function(response) {
            	mr_variant.variantNotification('Failed to create new page.  Try again.', 'circle-x', 'warning', 3000);
			},
			success: function(response) {
			    wp_data = response;
	      		mr_variant.wpNewPage(mr_variant.wp.newPageName);
			},
		    complete: function() {
		        mr_variant.stopLoading('.viu');
		    }
		});
	
  	} else {
  		
  		jQuery.ajax({
  			type: "POST",
  			url: wp_data.ajax_url,
  			dataType: 'json',
  			data: {
  				action: 'variant_page_builder_create_new_page',
  				title: $pageTitle,
  				variant: $json,
  				html: $html
  			},
  			error: function(response) {
  			    console.log(response);
  			},
  			success: function(response) {
  				  wp_data = response;
  		      mr_variant.wpNewPage(mr_variant.wp.newPageName);
  			},
        complete: function() {
  		       mr_variant.stopLoading('.viu');
  		  }
  		});
  		
  	}
	
};

/**
 * mr_variant.wp.saveHTML
 * 
 * Saves HTML from variant output via wp_ajax to the _variant_page_builder_html meta key for the current post
 * 
 * @var $html = Variant HTML output
 * @blame tommus
 * @since v1.0.0
 */
mr_variant.wp.saveHTML = function($html) {

    //Parse var $html as HTML, creates a false DOM so that we can run find() over the contents.
    var el = jQuery('<div></div>');
    el.html('<html><head></head><body>' + $html + '</body></html>');
    
    //Remove footer from HTML output
    jQuery('.main-container > footer', el).remove();
    jQuery('div[class="wysiwyg"]', el).contents().unwrap();
    jQuery('.wysiwyg', el).removeClass('wysiwyg');
    
    //Resolve shortcode holders
    jQuery('.shortcode-holder', el).each(function(){
        jQuery(this).before(jQuery(this).attr('data-shortcode'));
        jQuery(this).remove();
    });
      
    //Lock in resolved HTML and pass to our meta key
    $html = el.find('.main-container').html();
    
    console.log($html);
	
	//Check var $html exists, so we don't overwrite the page layout with nothing!
	if( typeof $html !== typeof undefined && $html !== false ){
		
	    jQuery.ajax({
	    	type: "POST",
	    	url: wp_data.ajax_url,
	    	data: {
	    		action: 'variant_page_builder_save_html',
	    		variant_page_builder_html: $html,
	    		post_id: wp_data.post_id
	    	},
	    	error: function(response) {
	    		  mr_variant.variantNotification('Failed to save HTML.  Try saving again.', 'circle-x', 'error', 3000);
	    	},
	    	success: function(response) {
	    	    if(!response.match('variant_page_builder_save_html_success')) {
	    		      mr_variant.variantNotification('There was a problem saving HTML.  Try saving again.', 'circle-x', 'error', 3000);	  
	    		  }
	    	},
        complete: function(){
            mr_variant.stopLoading('.viu');
        }
	    });
    
    } else {
    	  console.log($html);
        mr_variant.variantNotification('Warning: Cannot Save HTML. Refresh Page', 'circle-x', 'warning', 3000);
    }
   
};

/**
 * mr_variant.wp.saveVariant
 * 
 * Saves variant data to meta key, dataType is NOT set to JSON as it only needs to be parsed back as JSON
 * Leaving data type blank means we can parse the success response properly, this will be key to altering
 * users that the page data has been successfully saved.
 * 
 * @blame tommus
 * @since v1.0.0
 */
mr_variant.wp.saveVariant = function(json, saveType = 'manual') {
    //console.log(json);
    
    mr_variant.wp.checkUser();
    
    //Check var json exists, so we don't overwrite the page layout with nothing!
    if( typeof json !== typeof undefined && json !== false && json !== '' ){
    	
		jQuery.ajax({
	    	type: "POST",
	    	url: wp_data.ajax_url,
	    	data: {
	    		action: 'variant_page_builder_save_variant',
	    		variant_page_builder_variant: json,
	    		post_id: wp_data.post_id,
	    		save_type: saveType
	    	},
	    	error: function(response) {
	    		//console.log(response);
	    	},
	    	success: function(response) {
  	    		if(response.match('variant_page_builder_save_variant_success')) {
                mr_variant.variantNotification('Page '+(saveType === 'auto' ? 'Autosaved' : 'Saved'), 'circle-check', 'success', 3000);
                mr_variant.setSaved();
  	    		}
	            if(response.match('0')) {
	                mr_variant.variantNotification('Warning: Page Not saved', 'bug', 'warning', 3000);
	            }
	    	}
	    });
	    
    } else {
    	
          mr_variant.variantNotification('Warning: Cannot '+(saveType === 'auto' ? 'autosave' : 'save')+' Variant. Please refresh page.', 'circle-x', 'error', 3000);
    	
    }

};

/**
 * mr_variant.wp.renderHeader
 * 
 * Renders the header on initial page load by prepending it to .viu
 * This may not be required in the final build!
 * 
 * @blame tommus
 * @since v1.0.0
 * @toDo check with James if this is actually needed
 */
mr_variant.wp.renderNavContainer = function($layout){
	
  mr_variant.startLoading('.nav-container');
	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_load_header',
			post_id: wp_data.post_id,
			layout: $layout
		},
		error: function(response) {
        	console.log(response);
		},
		success: function(response) {
        	mr_variant.renderNavContainer(response);
		},
		complete: function(){
			//Fix nested WYSIWYG
			jQuery('.wysiwyg .wysiwyg').removeClass('wysiwyg');
		}
	});
	
};

mr_variant.wp.updateHeader = function($layout){
	
  mr_variant.startLoading('.nav-container');
	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_update_header',
			post_id: wp_data.post_id,
			layout: $layout
		},
		error: function(response) {
        console.log(response);
		},
		success: function(response) {
        mr_variant.variantNotification('Header Updated', 'circle-check', 'success', 3000);
		}
	});
	
	mr_variant.wp.renderNavContainer($layout);
	
};

/**
 * mr_variant.wp.renderFooter
 * 
 * Renders the footer on initial page load by prepending it to .viu
 * This may not be required in the final build!
 * 
 * @blame tommus
 * @since v1.0.0
 * @toDo check with James if this is actually needed
 */
mr_variant.wp.renderFooter = function($layout){
	
	mr_variant.startLoading(jQuery('.viu footer').length? 'footer':'.viu');
	
	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_load_footer',
			post_id: wp_data.post_id,
			layout: $layout
		},
		error: function(response) {
			//console.log(response);
		},
		success: function(response) {
			//console.log(response);
			mr_variant.renderFooter(response);
		}
	});
	
};

mr_variant.wp.updateFooter = function($layout){
	
  mr_variant.startLoading(jQuery('.viu footer').length? 'footer':'.viu');
	
  jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_update_footer',
			post_id: wp_data.post_id,
			layout: $layout
		},
		error: function(response) {
			//console.log(response);
		},
		success: function(response) {
			mr_variant.variantNotification('Footer Updated', 'circle-check', 'success', 3000);
		}
	});
	
	mr_variant.wp.renderFooter($layout);
	
};

/**
 * mr_variant.wp.renderShortcode
 * 
 * Renders shortcode HTML by passing a shortcode string into WP via wp_ajax
 * Returns HTML from rendered shortcode
 * 
 * @blame tommus & james
 * @since v1.0.0
 */
mr_variant.wp.renderShortcode = function(shortcode, targetID){
	
	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_render_shortcode',
			shortcode: shortcode
		},
		error: function(response) {
			//console.log(response);
		},
	    success: function(response) {
	        var rendered = {html: response, target: targetID};
	        mr_variant.renderShortCode(rendered);
	    }
	});

};

/**
 * Handles the shortcode added in the shortcode blocks
 */
mr_variant.wp.handleShortcodeBlock = function(data){
	jQuery(data).text('');
};

/**
 * Shows welcome modal if page is empty
 */

mr_variant.wp.showWelcomeModal = function(){
    if( !jQuery('.viu .main-container section').length && jQuery('.variant-welcome-modal').length ){
        mr_variant.showSectionsSelector();
        jQuery('.variant-welcome-modal').modal({
            autoResize: true,
            overlayClose: true,
            opacity: 0,
            minHeight: 620,
            overlayCss: {
                "background-color": "#3e3e3e"
            },
            closeClass: 'vex',
            onShow: function() {
                setTimeout(function() {
                    jQuery('.simplemodal-container').addClass('vko');
                    jQuery('.simplemodal-overlay').addClass('vko');
                }, 100);
                mr_variant.initSizes();
            },
            onClose: function() {
                setTimeout(function() {
                    jQuery.modal.close();
                    mr_variant.initSizes();
                }, 300);
                jQuery('.simplemodal-container').removeClass('vko');
                jQuery('.simplemodal-overlay').removeClass('vko');
            }
        });
    }
};

mr_variant.wp.editLink = function(href, text, target, variantID){
	//Hide the text input, we won't be using it
	jQuery('#wp-link').addClass('hide-text').attr('data-variant-id', variantID);
	
	//Open the link editor modal
	wpLink.open('link-edit-area', href, variantID, target);
	
	//Force our href in there
	jQuery('#wp-link-url').val(href);
}

mr_variant.wp.updateWpData = function($post_id, callback){
	
	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		dataType: 'json',
		data: {
			action: 'variant_page_builder_update_wp_data',
			post_id: $post_id
		},
		error: function(response) {
			//console.log(response);
		},
    success: function(response) {
        wp_data = response;
        if(!_.isUndefined(callback)){
            callback();
        }
	  }
	});
	
}

mr_variant.wp.updateOption = function(name, value){
	
	jQuery.ajax({
		type: "POST",
		url: wp_data.ajax_url,
		data: {
			action: 'variant_page_builder_update_option',
			optionName: name,
			optionValue: value
		},
		error: function(response) {},
		success: function(response) {}
	});
		
}

//Load variant asap
mr_variant.wp.load();

jQuery(window).load(function(){
	
	/**
	 * Call the WP WYSIWYG
	 */
	jQuery('body').on('click', '.wysiwyg, [data-ajax-identifier]', function(){
		
		var $this = jQuery(this);
		
		jQuery('.vnw', $this).remove();
		var content = $this.html().replace('&nbsp;',"");
		
		$this.addClass('wysiwyg-active');
		jQuery('.wp-editor-overlay').addClass('active transition');
		
		// (bool) is rich editor enabled and active
		var rich = (typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
		
		if( rich ) {
		    var editor = tinyMCE.get('mycustomeditor');
		    if( editor ) {
		        editor.setContent( content );
		    }
		} else {
			jQuery('#mycustomeditor').val(content);
		}
		
		return false;
		
	});
	
	jQuery('.wysiwyg-discard-changes').click(function(){
		
		jQuery('.wp-editor-overlay').removeClass('active');
		
		// (bool) is rich editor enabled and active
		var rich = (typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
		
		if( rich ) {
		    var editor = tinyMCE.get('mycustomeditor');
		    if( editor ) {
		        editor.setContent('');
		    }
		} else {
			jQuery('#mycustomeditor').val('');
		}
		
		jQuery('.wysiwyg-active').removeClass('wysiwyg-active');
		//mr_variant.variantNotification('Text Changes Discarded', 'bug', 'warning', 1500);
		
		return false;
	});
	
	jQuery('.wysiwyg-save-changes').click(function(){
		
		var $target = jQuery('.wysiwyg-active');
		
		jQuery('.wp-editor-overlay').removeClass('active');
		
		// (bool) is rich editor enabled and active
		var rich = (typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
		
		if( rich ){
			
		    var editor = tinyMCE.get('mycustomeditor');
		    if( editor ) {
		    	var $content = editor.getContent();
		    } 
		    
		} else {
			var $content = jQuery('#mycustomeditor').val();
		}
		
		//Get rid of backslashes
		$content = $content.replace(/\\/g, "").replace(/\[\[/g, '[').replace(/\]\]/g, ']').replace(/\[/g, '[[').replace(/\]/g, ']]').replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
		
		jQuery('[vic="'+ $target.attr('vic') +'"]').html( $content );
		jQuery('[vic="'+ $target.attr('vic') +'"] p:not(.lead, .type--fine-print) a:first-child:last-child').each(function(){
			var $this       = jQuery(this),
				$parentText = $this.parent().text().trim(),
				$thisText   = $this.text().trim();
				
			if( $thisText == $parentText ){
				$this.unwrap();	
			}
		});
		jQuery('[vic="'+ $target.attr('vic') +'"] a.btn:not(:has(>span))').wrapInner('<span class="btn__text" />');
		jQuery('[vic="'+ $target.attr('vic') +'"]').attr('contenteditable', 'true').trigger('input').removeAttr('contenteditable');
		
		$target.removeClass('wysiwyg-active');
		mr_variant.variantNotification('Text Updated', 'circle-check', 'success', 1500);
		
		//Tell variant a change has been made
		mr_variant.setUnsaved();
		
		return false;
	});
	
	//If the modal is closed without saving, clear our changes and remove added classes
	jQuery('body').on('click', '#wp-link-cancel, #wp-link-backdrop, #wp-link-close', function(event) {
		jQuery('#wp-link').removeClass('hide-text');
		jQuery('#link-edit-area').val('');
		mr_variant.variantNotification('Link Changes Discarded', 'bug', 'warning', 1500);
	});
	
	//If we click submit, send our link HTML off to the hidden textarea, then capture, parse & return it
	jQuery('body').on('click', '.hide-text #wp-link-submit', function(event) {
	
		var link = jQuery('#link-edit-area').val(),
			html = jQuery.parseHTML(link),
			target = jQuery(html).attr('target'),
			$href = jQuery(html).attr('href'),
			$rel = jQuery(html).attr('rel'),
			$title = jQuery(html).attr('title'),
			attrContentEditable = jQuery(html).attr('contenteditable'),
			variantID = '.' + jQuery('#wp-link').attr('data-variant-id');
	
		jQuery('#link-edit-area').val('');
		jQuery('#wp-link').removeClass('hide-text');
		
		//Save href
		jQuery(variantID).attr('href', $href);
		
		//Save target
		if( typeof target !== typeof undefined && target !== false ){
			jQuery(variantID).attr('target', '_blank');
		} else {
			jQuery(variantID).attr('target', '');
		}
		
		//Save title
		if( typeof $title !== typeof undefined && $title !== false ){
			jQuery(variantID).attr('title', $title);
		} else {
			jQuery(variantID).attr('title', '');
		}
		
		//Save Rel
		if( typeof $rel !== typeof undefined && $rel !== false ){
			jQuery(variantID).attr('rel', $rel);
		} else {
			jQuery(variantID).attr('rel', '');
		}
		
		//Trigger Change
		if( typeof attrContentEditable !== typeof undefined && attrContentEditable !== false ){
			jQuery(variantID).trigger('input');
		} else {
			jQuery(variantID).attr('contenteditable', 'true').trigger('input').removeAttr('contenteditable');
		}
		
		mr_variant.variantNotification('Link Updated', 'circle-check', 'success', 1500);
		
		//Tell variant a change has been made
		mr_variant.setUnsaved();
		
	});
	
	jQuery('.variant-welcome-modal .vex').click(function(){
		mr_variant.wp.updateOption('variant_show_welcome_modal', 'no');
	});

});