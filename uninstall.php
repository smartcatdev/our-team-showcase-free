<?php

namespace ots;

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/**
 * Uninstall script. Deletes all options, terms and trashes posts.
 *
 * @since 4.0.0
 */

include_once dirname( __FILE__ ) . '/constants.php';
include_once dirname( __FILE__ ) . '/includes/team-member.php';



if ( get_option( Options::NUKE ) == 'on' ) {

	// Trash all team member posts
	$posts = get_posts( array(
		'post_type'      => 'team_member',
		'posts_per_page' => -1
	) );

	foreach( $posts as $post ) {
		wp_trash_post( $post->ID );
	}


	// Delete all terms from the taxonomy
	register_team_member_position_taxonomy();

	$terms = get_terms( array(
		'taxonomy'   => 'team_member_position',
		'hide_empty' => false,
	) );

	foreach( $terms as $term ) {
		wp_delete_term( $term->term_id, 'team_member_position' );
	}


	// Delete all plugin settings
	$options = new \ReflectionClass( '\ots\Options' );

	foreach( $options->getConstants() as $option ) {
		delete_option( $option );
	}

}
