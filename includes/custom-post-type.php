<?php

namespace ots;

function enqueue_editor_scripts() {

    if( get_post_type() == 'team_member' ) {
        wp_enqueue_style( 'ots-metabox-css', asset( 'admin/css/metabox.css' ), null, VERSION );
    }

}

add_action( 'admin_enqueue_scripts', 'ots\enqueue_editor_scripts' );

/**
 * Register the team member custom post type.
 *
 * @since 4.0.0
 */
function register_team_member_post_type() {

    $labels = array(
        'name'               => _x( 'Team Members', 'post type general name', 'ots' ),
        'singular_name'      => _x( 'Team Member', 'post type singular name', 'ots' ),
        'menu_name'          => _x( 'Team Members', 'admin menu', 'ots' ),
        'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'ots' ),
        'add_new'            => _x( 'Add New', 'team_member', 'ots' ),
        'add_new_item'       => __( 'Add New Member', 'ots' ),
        'new_item'           => __( 'New Member', 'ots' ),
        'edit_item'          => __( 'Edit Member', 'ots' ),
        'view_item'          => __( 'View Member', 'ots' ),
        'all_items'          => __( 'All Members', 'ots' ),
        'search_items'       => __( 'Search Members', 'ots' ),
        'parent_item_colon'  => __( 'Parent Members:', 'ots' ),
        'not_found'          => __( 'No members found.', 'ots' ),
        'not_found_in_trash' => __( 'No members found in Trash.', 'ots' ),
        'archives'           => __( 'Member Archives', 'ots' ),
        'attributes'         => __( 'Member Attributes', 'ots' ),

    );

    $args = array(
        'menu_icon'            => 'dashicons-admin-users',
        'labels'               => $labels,
        'capability_type'      => 'post',
        'register_meta_box_cb' => 'ots\team_member_meta_boxes',
        'supports'             => array( 'title', 'editor', 'thumbnail' ),
        'public'               => true,
        'rewrite'              => array(
            'slug'  => get_option( Options::REWRITE_SLUG, Defaults::REWRITE_SLUG )
        )
    );

    register_post_type( 'team_member', $args );

}

add_action( 'init', 'ots\register_team_member_post_type' );


/**
 * Register the member grouping taxonomy.
 *
 * @since 4.0.0
 */
function register_team_member_position_taxonomy() {

    $labels = array(
        'name'              => _x( 'Groups', 'taxonomy general name', 'ots' ),
        'singular_name'     => _x( 'Group', 'taxonomy singular name', 'ots' ),
        'search_items'      => __( 'Search Groups', 'ots' ),
        'all_items'         => __( 'All Groups', 'ots' ),
        'parent_item'       => __( 'Parent Group', 'ots' ),
        'parent_item_colon' => __( 'Parent Group:', 'ots' ),
        'edit_item'         => __( 'Edit Group', 'ots' ),
        'update_item'       => __( 'Update Group', 'ots' ),
        'add_new_item'      => __( 'Add New Group', 'ots' ),
        'new_item_name'     => __( 'New Group', 'ots' ),
        'menu_name'         => __( 'Groups', 'ots' ),
    );

    $args = array(
        'labels'       => $labels,
        'hierarchical' => true,
    );

    register_taxonomy( 'team_member_position', 'team_member', $args );
}

add_action( 'init', 'ots\register_team_member_position_taxonomy' );


/**
 * Renders the member custom post type metabox fields.
 *
 * @since 4.0.0
 */
function team_member_meta_boxes() {

    if( is_admin() ) {

        $preview = apply_filters( 'ots_show_pro_fields_preview', true );

        add_meta_box( 'team-member-contact', __( 'Contact Information', 'ots' ), 'ots\do_contact_meta_box' );
        add_meta_box( 'team-member-articles', __( 'Authored / Favorite Articles', 'ots' ), 'ots\do_articles_meta_box' );

        add_meta_box( 'team-member-skills', __( 'Attributes / Skills / Ratings' . ( $preview ? ' - Pro version only' : '' ), 'ots' ), 'ots\do_skills_meta_box', null, 'advanced', 'default', array( 'preview' => $preview ) );
        add_meta_box( 'team-member-tags', __( 'Interests / Tags / Additional Skills' . ( $preview ? ' - Pro version only' : '' ), 'ots' ), 'ots\do_tags_meta_box', null, 'advanced', 'default', array( 'preview' => $preview ) );

    }

}

/**
 * Sanitize and save the contact metabox fields.
 *
 * @param $post_id
 * @param \WP_Post $post
 * @since 4.0.0
 */
function save_contact_meta_box( $post_id, \WP_Post $post ) {

    if( $post->post_type == 'team_member' &&
        check_admin_referer( 'contact_meta_box', 'contact_mata_box_nonce' ) ) {

        update_post_meta( $post_id, 'team_member_title', sanitize_text_field( $_POST['team_member_title'] ) );
        update_post_meta( $post_id, 'team_member_phone', sanitize_text_field( $_POST['team_member_phone'] ) );
        update_post_meta( $post_id, 'team_member_email', sanitize_email( $_POST['team_member_email'] ) );

        foreach( $_POST['team_member_links'] as $network => $link ) {
            update_post_meta( $post_id, 'team_member_' . sanitize_key( $network ), esc_url_raw( $link ) );
        }

    }

}

add_action( 'save_post', 'ots\save_contact_meta_box', 10, 3 );


/**
 * Sanitize and save the articles metabox fields.
 *
 * @param $post_id
 * @param \WP_Post $post
 * @since 4.0.0
 */
function save_articles_meta_box( $post_id, \WP_Post $post ) {

    if( $post->post_type == 'team_member' &&
        check_admin_referer( 'articles_meta_box', 'articles_mata_box_nonce' ) ) {

        update_post_meta( $post_id, 'team_member_article_bool', sanitize_checkbox( $_POST['team_member_article_bool'] ) );
        update_post_meta( $post_id, 'team_member_article_title', sanitize_text_field( $_POST['team_member_article_title'] ) );

        foreach( $_POST['team_member_articles'] as $index => $article ) {
            update_post_meta( $post_id, 'team_member_article' . $index, intval( $article ) );
        }

    }

}

add_action( 'save_post', 'ots\save_articles_meta_box', 10, 3 );


/**
 * Output the contact info metabox.
 *
 * @param \WP_Post $post
 * @since 4.0.0
 */
function do_contact_meta_box( \WP_Post $post ) { ?>

    <?php wp_nonce_field( 'contact_meta_box', 'contact_mata_box_nonce' ); ?>

    <table id="ots-contact-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-member-job-title"><?php _e( 'Job Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-job-title"
                       name="team_member_title"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_title', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-email"><?php _e( 'Email Address', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-email"
                       name="team_member_email"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_email', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-phone"><?php _e( 'Phone Number', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-phone"
                       name="team_member_phone"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_phone', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-facebook"><?php _e( 'Facebook', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-facebook"
                       name="team_member_links[facebook]"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_facebook', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-twitter"><?php _e( 'Twitter', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-twitter"
                       name="team_member_links[twitter]"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_twitter', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-linkedin"><?php _e( 'Linkedin', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-linkedin"
                       name="team_member_links[linkedin]"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_linkedin', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-gplus"><?php _e( 'Google Plus', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-gplus"
                       name="team_member_links[gplus]"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_gplus', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-instagram"><?php _e( 'Instagram', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-instagram"
                       name="team_member_links[instagram]"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_instagram', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-pinterest"><?php _e( 'Pinterest', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-pinterest"
                       name="team_member_links[pinterest]"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_pinterest', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-website"><?php _e( 'Website', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-website"
                       name="team_member_links[website]"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_website', true ) ); ?>" />
            </td>
        </tr>
    </table>

<?php }

/**
 * Output the articles metabox.
 *
 * @param \WP_Post $post
 * @since 4.0.0
 */
function do_articles_meta_box( \WP_Post $post ) { ?>

    <?php wp_nonce_field( 'articles_meta_box', 'articles_mata_box_nonce' ); ?>

    <table id="ots-articles-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-display-member-articles"><?php _e( 'Display Articles', 'ots' ); ?></label>
            </th>
            <td>
                <label>
                    <input id="ots-display-member-articles"
                           name="team_member_article_bool"
                           type="checkbox" <?php checked( get_post_meta( $post->ID, 'team_member_article_bool', true ), 'on' ); ?> />
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-articles-title"><?php _e( 'Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-articles-title"
                       name="team_member_article_title"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_article_title', true ) ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-article-1"><?php _e( 'Article 1', 'ots' ); ?></label>
            </th>
            <td>
                <?php posts_dropdown( 'team_member_articles[1]', 'ots-member-articles-1', get_post_meta( $post->ID, 'team_member_article1', true ) ); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-article-2"><?php _e( 'Article 2', 'ots' ); ?></label>
            </th>
            <td>
                <?php posts_dropdown( 'team_member_articles[2]', 'ots-member-articles-2', get_post_meta( $post->ID, 'team_member_article2', true ) ); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-article-3"><?php _e( 'Article 3', 'ots' ); ?></label>
            </th>
            <td>
                <?php posts_dropdown( 'team_member_articles[3]', 'ots-member-articles-3', get_post_meta( $post->ID, 'team_member_article3', true ) ); ?>
            </td>
        </tr>
    </table>

<?php }

/**
 * Output the skills metabox. Note all fields are disabled by default and this metabox has no save handler.
 *
 * @param \WP_Post $post
 * @param array $meta_box
 * @since 4.0.0
 */
function do_skills_meta_box( \WP_Post $post, array $meta_box ) { ?>

    <?php wp_nonce_field( 'skills_meta_box', 'skills_mata_box_nonce' ); ?>

    <table id="ots-skills-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-display-member-skills"><?php _e( 'Display Skills', 'ots' ); ?></label>
            </th>
            <td>
                <label>
                    <input id="ots-display-member-skills"
                           name="team_member_skill_bool"
                           type="checkbox" <?php checked( get_post_meta( $post->ID, 'team_member_skill_bool', true ), 'on' ); ?>

                        <?php disabled( true, $meta_box['args']['preview'] ); ?> />
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-skills-title"><?php _e( 'Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-skills-title"
                       name="team_member_skill_title"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill_title', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-skill-1"><?php _e( 'Attribute / Skill 1', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-skill-1"
                       name="team_member_skill_titles[]"
                       class="ots-member-skill-title"
                       placeholder="<?php _e( 'Title', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill1', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-1"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill_value1', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-skill-2"><?php _e( 'Attribute / Skill 2', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-skill-2"
                       name="team_member_skill_titles[]"
                       class="ots-member-skill-title"
                       placeholder="<?php _e( 'Title', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill2', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-2"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill_value2', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-skill-3"><?php _e( 'Attribute / Skill 3', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-skill-3"
                       name="team_member_skill_titles[]"
                       class="ots-member-skill-title"
                       placeholder="<?php _e( 'Title', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill3', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-3"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill_value3', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-skill-4"><?php _e( 'Attribute / Skill 4', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-skill-4"
                       name="team_member_skill_titles[]"
                       class="ots-member-skill-title"
                       placeholder="<?php _e( 'Title', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill4', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-4"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill_value4', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-skill-5"><?php _e( 'Attribute / Skill 5', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-skill-5"
                       name="team_member_skill_titles[]"
                       class="ots-member-skill-title"
                       placeholder="<?php _e( 'Title', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill5', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-5"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_skill_value5', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
    </table>

<?php }


/**
 * Output the tags metabox. Note all fields are disabled by default and this metabox has no save handler.
 *
 * @param \WP_Post $post
 * @param array $meta_box
 * @since 4.0.0
 */
function do_tags_meta_box( \WP_Post $post, array $meta_box ) { ?>

    <?php wp_nonce_field( 'tags_meta_box', 'tags_mata_box_nonce' ); ?>

    <table id="ots-tags-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-display-member-tags"><?php _e( 'Display Tags', 'ots' ); ?></label>
            </th>
            <td>
                <label>
                    <input id="ots-display-member-tags"
                           name="team_member_tags_bool"
                           type="checkbox" <?php checked( get_post_meta( $post->ID, 'team_member_tags_bool', true ), 'on' ); ?>

                        <?php disabled( true, $meta_box['args']['preview'] ); ?> />
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-tags-title"><?php _e( 'Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-tags-title"
                       name="team_member_tags_title"
                       class="regular-text"
                       value="<?php esc_attr_e( get_post_meta( $post->ID, 'team_member_tags_title', true ) ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-tags"><?php _e( 'Attributes', 'ots' ); ?></label>
            </th>
            <td>
                <textarea id="ots-member-tags"
                       name="team_member_tags"
                       class="regular-text"
                       placeholder="<?php _e( 'Enter attributes, comma separated', 'ots' ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?>

                        ><?php esc_html_e( get_post_meta( $post->ID, 'team_member_tags', true ) ); ?></textarea>
            </td>
        </tr>
    </table>

<?php }
