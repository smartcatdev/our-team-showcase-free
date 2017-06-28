<?php

namespace ots;

class TeamWidget extends \WP_Widget {

    public function __construct() {

        $options = array(
            'description' => __( 'Use this widget to display the Our Team anywhere on the site.', 'ots' )
        );

        parent::__construct( 'smatcat_team_widget', __( 'Our Team Sidebar Widget', 'ots' ), $options );

    }

    public function widget( $args, $instance ) {
        //  TODO write front end
    }

    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

        return $instance;

    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>

        <p>

            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ', 'ots' ); ?></label>

            <input type="text"
                   class="widefat"
                   id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>"
                   value="<?php echo esc_attr( $title ); ?>" />

        </p>

    <?php }

}