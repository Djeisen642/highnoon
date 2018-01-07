jQuery.noConflict();

CHARITABLE_RECURRING = {};

/**
 * Donation amount selection
 */
CHARITABLE_RECURRING = {
    
    init : function() {

        jQuery( 'html' ).addClass( 'js' );

        jQuery( '.recurring-donation' ).find( 'input:checked' ).each( function(i) {
             jQuery(this).addClass('selected');
        });

        // Toggle between monthly and one time
        jQuery( 'body' ).on( 'change', '.recurring-donation-options [name=recurring_donation]', function( event ) { 
            CHARITABLE_RECURRING.toggle_tabs( jQuery(this) );
        });

        jQuery( '.recurring-donation-options [name=recurring_donation]' ).change();

    },

    toggle_tabs : function( $radio ) {

            var $form = $radio.closest('form');

            jQuery('.recurring-donation-option').removeClass( 'selected' );
            jQuery('input[name=recurring_donation]:checked', '.charitable-form').closest('li').addClass('selected');

            var val = jQuery('input[name=recurring_donation]:checked', '.charitable-form').val();
            
            if( val == 'month' ){
                $form.find( '.charitable-recurring-donation-options' ).show().addClass('active');
                $form.find( '.charitable-donation-options:not(.charitable-recurring-donation-options)' ).hide().removeClass('active');
                CHARITABLE_RECURRING.toggle_gateways();               
            } else {
                $form.find( '.charitable-recurring-donation-options' ).hide().removeClass('active');
                $form.find( '.charitable-donation-options:not(.charitable-recurring-donation-options)' ).show().addClass('active');
                CHARITABLE_RECURRING.toggle_gateways(true);
            }

    },

    toggle_gateways : function( show_all ) {
        var show_all;

        if( typeof( show_all ) === 'undefined' ){
            show_all = false;
        }
        
        if( show_all == true ){
            jQuery( '#charitable-gateway-selector li' ).show();
        } else {
            jQuery( '#charitable-gateway-selector' ).find( 'input' ).each( function(i) {
                if( jQuery.inArray( jQuery(this).val(), Charitable_Recurring.supported_gateways ) !== -1 ){
                    jQuery(this).parent().show();
                } else {
                    jQuery(this).parent().hide();
                }
            } );  
        }

    }
};

(function( $ ) {

    $( 'body' ).on( 'charitable:form:initialize', function() { 
        CHARITABLE_RECURRING.init(); 
    });

})(jQuery);

