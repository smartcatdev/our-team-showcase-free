<?php

namespace ots;

function add_addons_pages() {

	add_submenu_page( 'edit.php?post_type=team_member', __( 'Add-ons', 'ots' ), __( 'Add-ons', 'ots' ), 'manage_options', 'ots-add-ons', 'ots\do_addons_page' );

}

add_action( 'admin_menu', 'ots\add_addons_pages' );


function do_addons_page() {

}
