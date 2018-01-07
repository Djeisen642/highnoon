/*global jQuery, document*/
jQuery(document).ready(function () {
    'use strict';
    jQuery("input[name='_edd_learndash_is_course']").change(function () {
        if (jQuery(this).is(':checked')) {
            jQuery('#edd_learndash_course_wrapper').css('display', 'block');
            jQuery('#_edd_learndash_course_chosen').css('width', '100%');
        } else {
            jQuery('#edd_learndash_course_wrapper').css('display', 'none');
        }
    });
});

