<?php

namespace ots;


function enqueue_reorder_scripts( $hook ) {

    if( strpos( $hook, 'ots-reorder-members' ) ) {
        wp_enqueue_script( 'ots-reorder-js', asset( 'admin/js/reorder.js' ), array( 'jquery', 'jquery-ui-sortable' ), VERSION );
        wp_enqueue_style( 'ots-reorder-css', asset( 'admin/css/reorder.css' ), null, VERSION );
    }

}

add_action( 'admin_enqueue_scripts', 'ots\enqueue_reorder_scripts' );


function save_team_members_order() {

    if( check_admin_referer( 'save_members_order', 'member_order_nonce' ) ) {

        $ids = array();

        // Extract the URL encoded IDs
        parse_str( $_POST['members_order'], $ids );


        for( $ctr = 1; $ctr < count( $ids['member'] ) + 1; $ctr++ ) {
            update_post_meta( $ids['member'][ $ctr - 1 ], 'sc_member_order', $ctr );
        }


        // Redirect back where we came from
        wp_safe_redirect( $_POST['_wp_http_referer'] );

    }

}

add_action( 'admin_post_ots_save_members_order', 'ots\save_team_members_order' );


function do_member_reorder_page() { ?>

    <?php $members = get_members_in_order(); ?>

    <div class="wrap">

        <h2><?php _e( 'Drag & Drop to Re-Order Team Members', 'ots' ); ?></h2>

        <?php if( $members->have_posts() ) : ?>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

                <ul id="ots-team-member-order">

                    <?php foreach( $members->posts as $member ) : ?>

                        <li id="member_<?php esc_attr_e( $member->ID ); ?>" class="ots-member">

                            <a class="edit" href="<?php echo esc_url( get_edit_post_link( $member->ID ) ); ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </a>

                            <div class="inner">

                                <div class="thumbnail" style="background-image: url('<?php echo esc_url( get_member_avatar( $member ) ); ?>');"></div>

                                <div class="member-info">
                                    <h2 class="name"><?php echo esc_html_e( $member->post_title ); ?></h2>
                                    <p class="description job-title"><?php esc_html_e( get_post_meta( $member->ID, 'team_member_title', true ) ); ?></p>
                                </div>

                            </div>

                        </li>

                    <?php endforeach; ?>

                </ul>

                <input type="hidden" name="members_order" />
                <input type="hidden" name="action" value="ots_save_members_order" />

                <?php wp_nonce_field( 'save_members_order', 'member_order_nonce' ); ?>

                <?php submit_button( __( 'Save Order', 'ots' ) ); ?>

            </form>

        <?php else : ?>

            <h3><?php _e( 'You haven\'t added any team members yet. <a href="edit.php?post_type=team_member">Add a new member</a>', 'ots' ); ?></h3>

        <?php endif; ?>

    </div>

<?php }