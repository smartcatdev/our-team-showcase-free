;(function ($) {

    $(document).on('change', '.ots-widget-display-all', function() {
        const checked = $(this).is(':checked'),
              $limit  = $(this)
                .parents('.ots-widget-limit')
                .find('.ots-limit-number');

        $limit.prop('disabled', checked);

        if (!$(this).prop('checked')) {
            $limit.focus().val(1);
        } else {
            $limit.val('');
        }

    });

})(jQuery);