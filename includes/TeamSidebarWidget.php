<?php

namespace ots;

/**
 * Widget to display a list of team members.
 *
 * @package ots
 * @since 4.0.0
 */
/**
 * Class TeamWidget
 * @package ots
 */
class TeamSidebarWidget extends \WP_Widget {


    public function __construct() {

        $options = array(
            'description' => __( 'Use this widget to display the Our Team anywhere on the site.', 'ots' )
        );

        parent::__construct( 'smatcat_team_widget', __( 'Our Team Sidebar Widget', 'ots' ), $options );

    }

    private function parse_args( $instance ) {

	    $defaults = array(
		    'sc_our_team_widget_title' => __( 'Meet Our Team', 'ots' ),
		    'sc_our_team_widget_limit' => 'all',
		    'sc_our_team_widget_group' => ''
	    );

	    return wp_parse_args( $instance, $defaults );

    }

	/**
     * Render the widget.
     *
	 * @param array $args
	 * @param array $instance
     * @since 4.0.0
	 */
    public function widget( $args, $instance ) {

        $instance = $this->parse_args( $instance );

        echo $args[ 'before_widget' ];
        echo $args[ 'before_title' ] . esc_html( $instance['sc_our_team_widget_title'] ) . $args[ 'after_title' ];

        $limit = $instance['sc_our_team_widget_limit'];
        $group = $instance['sc_our_team_widget_group'] !== 'ignore-group' ? $instance['sc_our_team_widget_group'] : '';

        $members = get_members_in_order( $limit, $group );

        ?>

        <?php if ( $members->have_posts() ) : ?>

            <div id="sc_our_team" class="widget">

                <?php while ( $members->have_posts() ) : $members->the_post(); ?>

                    <div itemscope itemtype="http://schema.org/Person" class="sc_sidebar_team_member">

                        <?php if( get_option( Options::SINGLE_TEMPLATE ) !== 'disabled' ) : ?>

                            <a href="<?php the_permalink(); ?>"
                               title="<?php the_title_attribute(); ?>"
                               rel="bookmark"><?php member_avatar(); ?></a>

                        <?php else : ?>

                            <?php member_avatar(); ?>

                        <?php endif; ?>

                        <div class="sc_team_member_overlay">

                            <div itemprop="name" class="sc_team_member_name"><?php the_title() ?></div>
                            <div itemprop="jobtitle" class="sc_team_member_jobtitle">
                                <?php esc_html_e( get_post_meta( get_the_ID(), 'team_member_title', true ) ); ?>
                            </div>

                        </div>

                    </div>

                    <?php wp_reset_postdata(); ?>

                <?php endwhile; ?>

            </div>

        <?php endif; ?>

    <?php }


	/**
     * Save updated widget settings.
     *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
     * @since 4.0.0
	 */
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $title = $new_instance['sc_our_team_widget_title'];
        $group = $new_instance['sc_our_team_widget_group'];
        $limit = $new_instance['sc_our_team_widget_limit'];

        $instance['sc_our_team_widget_title'] = strip_tags( $title );
        $instance['sc_our_team_widget_group'] = strip_tags( $group );

        if( strtolower( $limit ) === 'all' || $limit > 0 ) {
            $instance['sc_our_team_widget_limit'] = $limit;
        } else {
            $instance['sc_our_team_widget_limit'] = 'all';
        }

        return $instance;

    }

	/**
     * Output the widget settings form.
     *
	 * @param array $instance
     * @since 4.0.0
     * @return void
	 */
    public function form( $instance ) {

        $terms = get_terms( array(
            'taxonomy'   => 'team_member_position',
            'hide_empty' => false
        ) );

        $instance = $this->parse_args( $instance );

        $title = $instance['sc_our_team_widget_title'];
        $group = $instance['sc_our_team_widget_group'];
        $limit = $instance['sc_our_team_widget_limit'];

        ?>

        <p>

            <label for="<?php esc_attr_e( $this->get_field_id( 'sc_our_team_widget_title' ) ); ?>"
                   class="sc_our_team_widget_title_label">
                <?php _e( 'Title: ', 'ots' ); ?>
            </label>

            <input class="widefat"
                   id="<?php esc_attr_e( $this->get_field_id( 'sc_our_team_widget_title' ) ); ?>"
                   name="<?php esc_attr_e( $this->get_field_name( 'sc_our_team_widget_title' ) ); ?>"
                   value="<?php esc_attr_e( $title ); ?>" />

        </p>
        <p>

            <label for="<?php esc_attr_e( $this->get_field_id( 'sc_our_team_widget_group' ) ); ?>"
                   class="sc_our_team_widget_group_label">
                <?php _e( 'Group', 'ots' ); ?>
            </label>

            <select id="<?php esc_attr_e( $this->get_field_id( 'sc_our_team_widget_group' ) ); ?>"
                    name="<?php esc_attr_e( $this->get_field_name( 'sc_our_team_widget_group' ) ); ?>"
                    class="widefat">

                <option value="ignore-group"><?php _e( 'All Groups', 'ots' ); ?></option>

                <?php foreach( $terms as $term ) : ?>

                    <option value="<?php esc_attr_e( $term->slug ); ?>"
                        <?php selected( $group, $term->slug ); ?>><?php esc_html_e( $term->name ); ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </p>
        <div class="ots-widget-limit">
            <p>
                <label for="<?php esc_attr_e( $this->get_field_id( 'sc_our_team_widget_limit' ) ); ?>"
                       class="sc_our_team_widget_limit_label">
                    <?php _e( 'Limit to Show', 'ots' ); ?>
                </label>

                <input class="widefat ots-limit-number"
                       type="number"
                       min="1"
                       id="<?php esc_attr_e( $this->get_field_id( 'sc_our_team_widget_limit' ) ); ?>"
                       name="<?php esc_attr_e( $this->get_field_name( 'sc_our_team_widget_limit' ) ); ?>"
                       value="<?php echo $limit !== 'all' ? esc_attr_e( $limit ) : ''; ?>"

                    <?php disabled( 'all', strtolower( $limit ) ); ?> />

            </p>
            <p>
                <?php _e( '- or -', 'ots' ); ?>

                <label>
                    <input type="checkbox"
                           class="ots-widget-display-all"
                           name="<?php esc_attr_e( $this->get_field_name( 'sc_our_team_widget_limit' ) ); ?>"

                        <?php checked( 'all', strtolower( $limit ) ); ?>
                        <?php disabled( true, is_numeric( $limit ) ); ?>/><?php _e( 'Display All', 'ots' ); ?>

                </label>
            </p>
        </div>

    <?php }

}
