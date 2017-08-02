(function ($) {

    $(document).on('change', '.ots-widget-display-all', function() {

        var limit = $(this).parents('.ots-widget-limit').find('.ots-limit-number');

        console.log(limit)

        limit.prop('disabled', !limit.prop('disabled'));

        if (!$(this).prop('checked')) {

            limit.focus().val(1);

        } else {

            limit.val('');

        }

    });

})(jQuery);