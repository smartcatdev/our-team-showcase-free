jQuery(document).ready(function ($) {

    // ---------- Tools //
    $( '#ots-import-form' ).submit( function() {
//        alert()
        if( $( '#ots-import-replace-button' ).val() === 'Import & Replace Existing' ) {
         
            var  r
         
            r = confirm( 'This will delete all your team members' )

            if( r == true ) {
                return true
            }else {
                return false
            }

        }
        
        
    })
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