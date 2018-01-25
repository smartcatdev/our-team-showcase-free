<?php

namespace ots;


/**
 * Enqueue scripts for single.php.
 *
 * @since 4.0.0
 */
function enqueue_single_scripts() {

    if( get_post_type() == 'team_member' ) {
        wp_enqueue_style( 'ots-single' );
    }

}

add_action( 'wp_enqueue_scripts', 'ots\enqueue_single_scripts' );


/**
 * Manually override the theme's template.
 *
 * @param $template
 * @return string
 * @since 4.0.0
 */
function include_single_template( $template ) {

    if ( is_single() && get_post_type() == 'team_member' ) {

        // Pull in the template
        if( override_theme_template() ) {
            $template = locate_template( 'single.php' );
        }

    }

    return $template;

}

add_filter( 'template_include', 'ots\include_single_template' );


/**
 * Override the default single-team_member template used by themes to avoid breakage.
 *
 * @return bool
 * @since 4.0.0
 */
function override_theme_template() {

    $template = get_option( Options::SINGLE_TEMPLATE );

    return ( $template === 'standard' || !template_path( $template ) ) &&
        file_exists( get_template_directory() . '/single-team_member.php' );

}


/**
 * Register the team member custom post type.
 *
 * @since 4.0.0
 */
function register_team_member_post_type() {

    $labels = array(
        'name'               => _x( 'Team Members', 'post type general name', 'ots' ),
        'singular_name'      => _x( 'Team Member', 'post type singular name', 'ots' ),
        'menu_name'          => _x( 'Team', 'admin menu', 'ots' ),
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
            'slug'  => get_option( Options::REWRITE_SLUG )
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
 * Add custom columns to the team member posts table.
 *
 * @param $columns
 * @return mixed
 * @since 4.0.0
 */
function add_team_member_custom_columns( $columns ) {

    unset( $columns['date'] );

    $columns['title'] = __( 'Name', 'ots' );
    $columns['team_member_title'] = __( 'Job Title', 'ots' );
    $columns['team_member_group'] = __( 'Groups', 'ots' );
    $columns['team_member_image'] = __( 'Image', 'ots' );

    return $columns;

}

add_filter( 'manage_edit-team_member_columns', 'ots\add_team_member_custom_columns' );


/**
 * Output custom columns in the team member posts table.
 *
 * @param $column
 * @param $post_id
 * @since 4.0.0
 */
function do_team_member_custom_columns( $column, $post_id ) {

    switch( $column ) {

        case 'team_member_title' :
            echo get_post_meta( $post_id, 'team_member_title', true ) ?: '-';
            break;

        case 'team_member_image' :
            echo member_avatar( $post_id, array( 50, 50 ) );
            break;

        case 'team_member_group':

            $groups = get_the_terms( get_post( $post_id ), 'team_member_position' );

            if ( !empty( $groups ) ) {

                $str = '';

                foreach ( $groups as $group ) {
                    $str .= "$group->name - ";
                }

                echo rtrim( $str, ' - ' );

            }

            break;

    }

}

add_action( 'manage_team_member_posts_custom_column', 'ots\do_team_member_custom_columns', 10, 2 );


/**
 * Append articles and social links when using single.php.
 *
 * @param $content
 * @return string
 * @since 4.0.0
 */
function single_member_content( $content ) {

    // Use {} to prevent whitespace leaks after headers have been sent
    if ( is_single() && get_post_type() == 'team_member' &&
        get_option( Options::SINGLE_TEMPLATE ) == 'standard' ) {  ob_start(); ?>

        <div id="sc_our_team">

            <div class="sc_team_member">

                <?php echo $content; ?>

                <?php if ( get_option( Options::SHOW_SINGLE_SOCIAL ) == 'on' ) : ?>

                    <div class="icons"><?php do_member_social_links(); ?></div>

                <?php endif; ?>

                <hr>

                <?php if ( get_post_meta( get_the_ID(), 'team_member_article_bool', true ) === 'on' ) : ?>

                    <div class="sc_team_posts sc_team_post">

                        <h3 class="skills-title"><?php esc_attr_e( get_post_meta( get_the_ID(), 'team_member_article_title', true ) ); ?></h3>

                        <div class="sc-team-member-posts">

                            <?php foreach ( get_member_articles() as $article ) : ?>

                                <div class="sc-team-member-post">

                                    <div class="width25 sc-left">

                                        <a href="<?php the_permalink( $article ); ?>"><?php echo get_the_post_thumbnail( $article, 'medium' ); ?></a>

                                    </div>

                                    <div class="width75 sc-left">

                                        <a href="<?php the_permalink( $article ); ?>"><?php echo get_the_title( $article ); ?></a>

                                    </div>

                                    <div class="clear"></div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                        <div class="clear"></div>

                    </div>

                <?php endif; ?>

            </div>

        </div>

        <?php $content = ob_get_clean();

    }

    return $content;

}

add_filter( 'the_content', 'ots\single_member_content' );


/**
 * Renders the member custom post type metabox fields.
 *
 * @since 4.0.0
 */
function team_member_meta_boxes() {

    if( is_admin() ) {

        $preview = apply_filters( 'ots_enable_pro_preview', true );

        add_meta_box( 'team-member-contact', __( 'Contact Information', 'ots' ), 'ots\do_contact_meta_box' );
        add_meta_box( 'team-member-articles', __( 'Authored / Favorite Articles', 'ots' ), 'ots\do_articles_meta_box' );

        add_meta_box( 'team-member-skills', __( 'Attributes / Skills / Ratings' . ( $preview ? ' - <i class="ots-pro">Pro version only</i>' : '' ), 'ots' ), 'ots\do_skills_meta_box', null, 'advanced', 'default', array( 'preview' => $preview ) );
        add_meta_box( 'team-member-tags', __( 'Interests / Tags / Additional Skills' . ( $preview ? ' - <i class="ots-pro">Pro version only</i>' : '' ), 'ots' ), 'ots\do_tags_meta_box', null, 'advanced', 'default', array( 'preview' => $preview ) );

    }

}

/**
 * Add default meta keys when creating a new team member.
 *
 * @param $post_id
 * @param $post
 * @param $update
 * @since 4.0.0
 */
function set_default_post_meta( $post_id, $post, $update ) {

    if( !$update ) {
        update_post_meta( $post_id, 'sc_member_order', PHP_INT_MAX );
    }

}

add_action( 'save_post_team_member', 'ots\set_default_post_meta', 10, 3 );


/**
 * Sanitize and save the contact meta box fields.
 *
 * @param $post_id
 * @since 4.0.0
 */
function save_contact_meta_box( $post_id ) {

    if( isset( $_POST['articles_mata_box_nonce'] ) &&
        wp_verify_nonce( $_POST['contact_mata_box_nonce'], 'contact_meta_box' ) ) {

        update_post_meta( $post_id, 'team_member_title', sanitize_text_field( $_POST['team_member_title'] ) );
        update_post_meta( $post_id, 'team_member_phone', sanitize_text_field( $_POST['team_member_phone'] ) );
        update_post_meta( $post_id, 'team_member_other', sanitize_text_field( $_POST['team_member_other'] ) );
        update_post_meta( $post_id, 'team_member_other_icon', sanitize_key( $_POST['team_member_other_icon'] ) );
        update_post_meta( $post_id, 'team_member_email', sanitize_email( $_POST['team_member_email'] ) );

        foreach( $_POST['team_member_links'] as $network => $link ) {
            update_post_meta( $post_id, 'team_member_' . sanitize_key( $network ), esc_url_raw( $link ) );
        }

    }

}

add_action( 'save_post_team_member', 'ots\save_contact_meta_box' );


/**
 * Sanitize and save the articles meta box fields.
 *
 * @param $post_id
 * @since 4.0.0
 */
function save_articles_meta_box( $post_id ) {

    if( isset( $_POST['articles_mata_box_nonce'] ) &&
        wp_verify_nonce( $_POST['articles_mata_box_nonce'], 'articles_meta_box' ) ) {

        update_post_meta( $post_id, 'team_member_article_bool', isset( $_POST['team_member_article_bool'] ) ? 'on' : '' );
        update_post_meta( $post_id, 'team_member_article_title', sanitize_text_field( $_POST['team_member_article_title'] ) );

        foreach( $_POST['team_member_articles'] as $index => $article ) {
            update_post_meta( $post_id, 'team_member_article' . $index, intval( $article ) );
        }

    }

}

add_action( 'save_post_team_member', 'ots\save_articles_meta_box' );


/**
 * Output the contact info meta box.
 *
 * @param \WP_Post $post
 * @since 4.0.0
 */
function do_contact_meta_box( \WP_Post $post ) { ?>

    <?php wp_nonce_field( 'contact_meta_box', 'contact_mata_box_nonce' ); ?>

    <?php $member = team_member( $post ); ?>

    <table id="ots-contact-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-member-job-title"><?php _e( 'Job Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-job-title"
                       name="team_member_title"
                       class="regular-text"
                       value="<?php esc_attr_e( $member->title ); ?>" />
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
                       value="<?php esc_attr_e( $member->email ); ?>" />
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
                       placeholder="(123) 456-7890"
                       value="<?php esc_attr_e( $member->phone ); ?>" />
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
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->facebook ); ?>" />
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
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->twitter ); ?>" />
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
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->linkedin ); ?>" />
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
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->gplus ); ?>" />
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
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->instagram ); ?>" />
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
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->pinterest ); ?>" />
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
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->website ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-other"><?php _e( 'Other', 'ots' ); ?></label>
            </th>
            <td>

                <?php $other_icon = $member->other_icon; ?>

                <select id="ots-member-other-icon" name="team_member_other_icon">
                    <option value=""><?php _e( 'Select an Icon', 'ots' ); ?></option>
                    <option value="etsy" <?php selected( $other_icon, 'etsy' ); ?>><?php _e( 'Etsy', 'ots' ); ?></option>
                    <option value="whatsapp" <?php selected( $other_icon, 'whatsapp' ); ?>><?php _e( 'Whatsapp', 'ots' ); ?></option>
                    <option value="skype" <?php selected( $other_icon, 'skype' ); ?>><?php _e( 'Skype', 'ots' ); ?></option>
                    <option value="vimeo" <?php selected( $other_icon, 'vimeo' ); ?>><?php _e( 'Vimeo', 'ots' ); ?></option>
                    <option value="soundcloud" <?php selected( $other_icon, 'soundcloud' ); ?>><?php _e( 'Soundcloud', 'ots' ); ?></option>
                </select>
                <input id="ots-member-other"
                       name="team_member_other"
                       placeholder="http://"
                       value="<?php esc_attr_e( $member->other ); ?>" />
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

    <?php $member = team_member( $post ); ?>

    <table id="ots-articles-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-display-member-articles"><?php _e( 'Display Articles', 'ots' ); ?></label>
            </th>
            <td>
                <label>
                    <input id="ots-display-member-articles"
                           name="team_member_article_bool"
                           type="checkbox" <?php checked( $member->article_bool, 'on' ); ?> />
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-articles-title"><?php _e( 'Articles Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-articles-title"
                       name="team_member_article_title"
                       class="regular-text"
                       value="<?php esc_attr_e( $member->article_title ); ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-article-1"><?php _e( 'Article 1', 'ots' ); ?></label>
            </th>
            <td>
                <?php posts_dropdown( 'team_member_articles[1]', 'ots-member-articles-1', $member->article1 ); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-article-2"><?php _e( 'Article 2', 'ots' ); ?></label>
            </th>
            <td>
                <?php posts_dropdown( 'team_member_articles[2]', 'ots-member-articles-2', $member->article2 ); ?>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-article-3"><?php _e( 'Article 3', 'ots' ); ?></label>
            </th>
            <td>
                <?php posts_dropdown( 'team_member_articles[3]', 'ots-member-articles-3', $member->article3 ); ?>
            </td>
        </tr>
    </table>

<?php }


/**
 * Output the skills meta box. Note all fields are disabled by default and this meta box has no save handler.
 *
 * @param \WP_Post $post
 * @param array $meta_box
 * @since 4.0.0
 */
function do_skills_meta_box( \WP_Post $post, array $meta_box ) { ?>

    <?php wp_nonce_field( 'skills_meta_box', 'skills_meta_box_nonce' ); ?>

    <?php $member = team_member( $post ); ?>

    <table id="ots-skills-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-display-member-skills"><?php _e( 'Display Skills', 'ots' ); ?></label>
            </th>
            <td>
                <label>
                    <input id="ots-display-member-skills"
                           name="team_member_skill_bool"
                           type="checkbox" <?php checked( $member->skill_bool, 'on' ); ?>

                        <?php disabled( true, $meta_box['args']['preview'] ); ?> />
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-skills-title"><?php _e( 'Skills Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-skills-title"
                       name="team_member_skill_title"
                       class="regular-text"
                       value="<?php esc_attr_e( $member->skill_title ); ?>"

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
                       value="<?php esc_attr_e( $member->skill1 ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-1"
                       name="team_member_skill_ratings[]"
                       type="number"
                       min="1"
                       max="10"
                       class="ots-member-skill-rating"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( $member->skill_value1 ); ?>"

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
                       value="<?php esc_attr_e( $member->skill2 ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-2"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       type="number"
                       min="1"
                       max="10"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( $member->skill_value2 ); ?>"

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
                       class="ots-member-skill-title""
                       placeholder="<?php _e( 'Title', 'ots' ); ?>"
                       value="<?php esc_attr_e( $member->skill3 ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-3"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       type="number"
                       min="1"
                       max="10"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( $member->skill_value3 ); ?>"

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
                       value="<?php esc_attr_e( $member->skill4 ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-4"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       type="number"
                       min="1"
                       max="10"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( $member->skill_value4 ); ?>"

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
                       value="<?php esc_attr_e( $member->skill5 ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />

                <input id="ots-member-skill-5"
                       name="team_member_skill_ratings[]"
                       class="ots-member-skill-rating"
                       type="number"
                       min="1"
                       max="10"
                       placeholder="<?php _e( 'Rating 1 - 10', 'ots' ); ?>"
                       value="<?php esc_attr_e( $member->skill_value5 ); ?>"

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

    <?php wp_nonce_field( 'tags_meta_box', 'tags_meta_box_nonce' ); ?>

    <?php $member = team_member( $post ); ?>

    <table id="ots-tags-meta-box" class="ots-meta-box widefat">
        <tr>
            <th>
                <label for="ots-display-member-tags"><?php _e( 'Display Tags', 'ots' ); ?></label>
            </th>
            <td>
                <label>
                    <input id="ots-display-member-tags"
                           name="team_member_tags_bool"
                           type="checkbox" <?php checked( $member->tags_bool, 'on' ); ?>

                        <?php disabled( true, $meta_box['args']['preview'] ); ?> />
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-tags-title"><?php _e( 'Tags Title', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-tags-title"
                       name="team_member_tags_title"
                       class="regular-text"
                       value="<?php esc_attr_e( $member->tags_title ); ?>"

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

                        ><?php esc_html_e( $member->tags ); ?></textarea>
            </td>
        </tr>
        <tr>
            <th>
                <label for="ots-member-personal-quote"><?php _e( 'Personal Quote', 'ots' ); ?></label>
            </th>
            <td>
                <input id="ots-member-personal-quote"
                       name="team_member_quote"
                       class="regular-text"
                       value="<?php esc_attr_e( $member->quote ); ?>"

                    <?php disabled( true, $meta_box['args']['preview'] ); ?> />
            </td>
        </tr>
    </table>

<?php }
