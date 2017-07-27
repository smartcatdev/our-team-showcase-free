jQuery(document).ready(function ($) {

    var limit = $('#ots-display-limit-number');


    $('#ots-display-limit-all').change(function (e) {

        limit.prop('disabled', !limit.prop('disabled'));

        if (!$(e.target).prop('checked')) {

            limit.focus().val(1);

        } else {

            limit.val('');

        }

    });


    $('.wp-color-picker').wpColorPicker();

});