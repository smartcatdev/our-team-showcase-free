jQuery( document ).ready( function( $ ){

    do_resize();

    $( window ).resize( function() {
        do_resize();

    });

    function do_resize() {

        var member_height = $( '.sc_team_member' ).width();

        $( '.sc_team_member' ).each( function( index, el ){
            $( el ).find( '.sc_team_member_inner' ).css( { height: member_height } );
        } );


        $( '.sc_team_member' ).mouseenter( function() {

            $( this ).find( '.sc_team_member_overlay' ).stop( true, false ).fadeIn( 440 );
            $( this ).find( '.wp-post-image' ).addClass( 'zoomIn' );
            $( this ).find( '.sc_team_more' ).addClass( 'show' );

        } ).mouseleave( function() {

            $( this ).find( '.sc_team_member_overlay' ).stop( true, false ).fadeOut( 440 );
            $( this ).find( '.wp-post-image' ).removeClass( 'zoomIn' );
            $( this ).find( '.sc_team_more' ).removeClass( 'show' );

        } );

    }

} );
