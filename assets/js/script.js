jQuery( document ).ready(function($){

    do_resize();

    $( window ).resize( function() {

        do_resize();

    } );

    function do_resize() {

        var member_height = $('.ots-team-member' ).width();

        $( '.ots-team-member' ).each( function( index, el ) {

            $( el ).find( '.ots-inner' ).css( { height: member_height } );

        });

    }
    
    $( '#ots .ots-team-member' ).mouseenter( function() {

        $( this ).find( '.ots-overlay' ).stop( true, false ).fadeIn( 440 );
        $( this ).find( '.ots-image' ).addClass( 'zoomIn' );
        $( this ).find( '.ots-more' ).addClass( 'show' );
        
    } ).mouseleave( function() {

        $( this ).find( '.ots-overlay' ).stop( true, false ).fadeOut( 440 );
        $( this ).find( '.ots-image' ).removeClass( 'zoomIn' );
        $( this ).find( '.ots-more' ).removeClass( 'show' );
       
    } );

});
