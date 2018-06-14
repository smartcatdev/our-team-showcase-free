jQuery(document).ready(function ($) {


    // ---------- Tools //
    $('#ots-import-replace-existing, #ots-import--button').prop('disabled', true);

    $('[name="ots_file_import"]').change(function (e) {
        $('#ots-import-replace-existing, #ots-import--button').prop('disabled', !$(this).get([0]).files.length);
    });


    $( '#ots-import-form' ).submit( function(e) {

        if( $( '#ots-import-replace-existing' ).prop('checked') === true ) {

            if (confirm( 'This will delete all your team members' )) {
                return true
            } else {
                return false
            }

        }
    });


    // -------------------
    
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