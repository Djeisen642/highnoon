function makeTitle(slug) {
    var words = slug.split('-');

    for(var i = 0; i < words.length; i++) {
      var word = words[i];
      words[i] = word.charAt(0).toUpperCase() + word.slice(1);
    }

    return words.join(' ');
}

jQuery(document).ready(function(){
	"use strict";
	
	//Lightbox text
	if( typeof lightbox !== null ){
		lightbox.option({
			'albumLabel': stack_data.lightbox_text
	    });
	}
	
	//Horizontal Filters
	if( jQuery('body').hasClass('filters-horizontal') ){
		jQuery('.masonry-filter-holder').addClass('masonry-filters--horizontal');	
	}
	
	//WordPress Tweaks
	jQuery('.sidebar select, .product select, .wpb_text_column select, .gform_wrapper select').attr('style', '').wrap('<div class="input-select" />');
	jQuery('.wpb_text_column > div > div > label, .wpb_text_column > div > div >.input-select + br').remove();
	jQuery('button[type="submit"][value]:not(.wpforms-submit)').each(function(){
		jQuery(this).text(jQuery(this).attr('value'));
	});
	
	//Gravity Forms
	jQuery('.gform_body .button').addClass('btn btn--primary btn--small type--uppercase');
	
	//Accordions
	if(!( jQuery('body').hasClass('no-active-tabs') )){
		jQuery('.tabs:not(.no-active) > li:first-child').addClass('active');
	}
	if(!( jQuery('body').hasClass('no-active-accordions') )){
		jQuery('.accordion:not(.no-active) > li:first-child').addClass('active');
	}
	
	//Btn inners
	jQuery('a.btn:not(:has(>span))').wrapInner('<span class="btn__text" />');
	
	//Scrolling links
	jQuery('a[href^="#"]:not(a[href="#"]):not(.vc_tta a)').addClass('inner-link').click(function(){
		jQuery('.hamburger-toggle.toggled-class').trigger('click');
	});
	
	jQuery('p:empty, li:empty').remove();
	
	//Video Backgrounds
	jQuery('video[data-src-mp4]').each(function(){
		var $this = jQuery(this),
			$mp4  = $this.attr('data-src-mp4'),
			$webm = $this.attr('data-src-webm');  
		
		jQuery('source[type="video/mp4"]', $this).attr('src', $mp4);
		jQuery('source[type="video/webm"]', $this).attr('src', $webm);
		
		$this[0].load();
	});
	
	if( jQuery('.page-navigator').length && !jQuery('body').hasClass('variant-active') ){
		jQuery('.page-navigator').prependTo('body');
		
		if( jQuery('body').hasClass('variant-content') ){
			
			jQuery('.in-page-link').each(function(){
				var $this = jQuery(this),
					$id = $this.attr('id');
				
				if(!( '' == $id )){
					var content = '<li><a href="#'+ $id +'" class="inner-link" data-title="'+ makeTitle($id) +'"></a></li>';
					jQuery('.page-navigator ul').append(content);
					jQuery(this).next('section').attr('id', $id);
					$this.remove();
				}
				
			});
			
		} else {
			
			jQuery('.main-container > div[id], .main-container section[id]').each(function(){
				if(!( '' == jQuery(this).attr('id') )){
					var content = '<li><a href="#'+ jQuery(this).attr('id') +'" class="inner-link" data-title="'+ makeTitle(jQuery(this).attr('id')) +'"></a></li>';
					jQuery('.page-navigator ul').append(content);
				}
			});
		
		}
		
	}
	
	//Login form
	jQuery('#loginform input').each(function(){
		
		var $this = jQuery(this);
		
		if( 'user_pass' == $this.attr('id') || 'user_login' == $this.attr('id') ){
			$this.attr('placeholder', $this.prev().text());
		}
		
	});
	
	//Mega Menu
	jQuery('.add-h4 li').addClass('h4');
	
	jQuery('.input-checkbox input[type="checkbox"], .input-radio input[type="radio"]').each(function(index){
	    var input = jQuery(this),
	        label = input.siblings('label'),
	        id    = "input-assigned-"+index;
	    if(typeof input.attr('id') === typeof undefined || input.attr('id') === ""){
	        input.attr('id', id);
	        label.attr('for', id);
	    }else{
	        id = input.attr('id');
	        label.attr('for', id);
	    }
	    input.after(label);
	});
	
	//Variant stuff
	if( jQuery('body').hasClass('variant-active') ){
		
		if(!_.isUndefined(mr.sliders.draggable)) mr.sliders.draggable = false;
		
		jQuery(window).on("load", function(){	
			if(window.mr_parallax !== undefined){
				window.mr_parallax.callback = function(element){
					window.mr_parallax.profileParallaxElements();
	
					console.log("ParallaxCallback element: "+element)
					if( (!jQuery(element).hasClass('parallax'))  &&  jQuery(element).is('section:nth-of-type(1), header:nth-of-type(1)')){
						jQuery(element).find('.background-image-holder').each(function(){
							jQuery(this).css('top','0px');
							window.mr_parallax.mr_setTranslate3DTransform(jQuery(this).get(0), 0);
						});
					}else if(jQuery(element).hasClass('parallax')  &&  jQuery(element).is('section:nth-of-type(1), header:nth-of-type(1)')){	
						if(jQuery('.viu nav').css('position') !== 'fixed')
						{
							jQuery(element).find('.background-image-holder').css('top', -(jQuery('.viu nav').outerHeight(true)));
							if(jQuery(element).outerHeight(true) == jQuery(window).height()){
									jQuery(element).find('.background-image-holder').css('height', jQuery(window).height() + jQuery('.viu nav').outerHeight(true));
							}
						}
						else{
							jQuery(element).find('.background-image-holder').css('top', 0);	
						}
					}else if(!jQuery(element).hasClass('parallax')  &&  !jQuery(element).is('section:nth-of-type(1), header:nth-of-type(1)')){
						jQuery(element).find('.background-image-holder').each(function(){
							jQuery(this).css('top','0px');
							window.mr_parallax.mr_setTranslate3DTransform(jQuery(this).get(0), 0);
						});
					}else if(jQuery(element).hasClass('parallax')  &&  !jQuery(element).is('section:nth-of-type(1), header:nth-of-type(1)')){
						jQuery(element).find('.background-image-holder').each(function(){
							jQuery(this).css('top','0px');
							window.mr_parallax.mr_setTranslate3DTransform(jQuery(this).get(0), 0);
						});
					}
				};
			}
		});
		    	
	}
	
});