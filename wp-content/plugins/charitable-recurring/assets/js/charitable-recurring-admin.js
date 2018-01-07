( function( $ ) {

    $( document ).ready( function() {

        var $mode = $( '#campaign-recurring-donations' );

        var toggle_recurring_suggested = function() {

            var $advanced_options = $( '#charitable-campaign-suggested-recurring-donations-metabox-wrap, #charitable-campaign-recurring-default-tab-wrap' );

            if ( 'advanced' == $mode.val() ) {
                $advanced_options.show();
            } else {
                $advanced_options.hide();
            }

        };

        $mode.on( 'change', toggle_recurring_suggested ).change();

    });

})( jQuery );