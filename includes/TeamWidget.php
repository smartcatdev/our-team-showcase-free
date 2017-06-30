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
class TeamWidget extends \WP_Widget {


    public function __construct() {

        $options = array(
            'description' => __( 'Use this widget to display the Our Team anywhere on the site.', 'ots' )
        );

        parent::__construct( 'smatcat_team_widget', __( 'Our Team Sidebar Widget', 'ots' ), $options );

    }

    public function widget( $args, $instance ) {

        echo $args[ 'before_widget' ];
        echo $args[ 'before_title' ] . $instance['title'] . $args[ 'after_title' ];

        $members = get_members_in_order();

        ?>

        <?php if ( $members->have_posts() ) : ?>

            <div id="sc_our_team" class="widget">

                <?php while ( $members->have_posts() ) : $members->the_post(); ?>

                    <div itemscope itemtype="http://schema.org/Person" class="sc_sidebar_team_member">

                        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php member_avatar(); ?></a>

                        <div class="sc_team_member_overlay">

                            <div itemprop="name" class="sc_team_member_name"><?php the_title() ?></div>
                            <div itemprop="jobtitle" class="sc_team_member_jobtitle">
                                <?php esc_html_e( get_post_meta( get_the_ID(), 'team_member_title', true ) ); ?>
                            </div>

<!--                            <div class='icons'>--><?php //do_member_social_links(); ?><!--</div>-->

                        </div>

                    </div>

                    <?php wp_reset_postdata(); ?>

                <?php endwhile; ?>

            </div>

        <?php endif; ?>

    <?php }

    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

        return $instance;

    }

    public function form( $instance ) { ?>

        <p>

            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ', 'ots' ); ?></label>

            <input type="text"
                   class="widefat"
                   id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>"
                   value="<?php echo esc_attr( !empty( $instance['title'] ) ? $instance['title'] : __( 'Meet Our Team', 'ots' ) ); ?>" />

        </p>

    <?php }

}