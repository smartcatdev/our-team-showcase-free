jQuery( document ).ready( function ( $ ) {

    var limit = $( '#ots-display-limit-number');

    $( '#ots-display-limit-all' ).change( function () {

        limit.prop( 'disabled', !limit.prop( 'disabled' ) );

    } );


    $( 'input[name="ots-global-team-main-color"]' ).wpColorPicker();

} );