jQuery(document).ready(function() {
	
	updateOption = function(){
		
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {
				action: 'variant_page_builder_update_option',
				optionName: 'variant_page_builder_vc_notification',
				optionValue: 'no'
			},
			error: function(response) {},
			success: function(response) {}
		});
			
	}
	
	/**
	 * Move the variant meta box over the standard WYSIWYG editor
	 */
	jQuery('#variant-launch').appendTo('#titlediv')
	jQuery('#variant-meta-box').prependTo('#postdivrich');
	jQuery('#variant-meta-box > button, #variant-meta-box > h2').remove();
	
	jQuery('body').on('click', '.variant-vc-dismiss .notice-dismiss', function(){
		updateOption('variant_page_builder_vc_notification', 'no');
	});
	
});