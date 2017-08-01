<?php

namespace ots;


class TeamMainWidget extends \WP_Widget {

	public function __construct() {

		$options = array(
			'description' => __( 'Use this widget to display the Our Team anywhere on the site.', 'ots' )
		);

		parent::__construct( 'ots_main_widget', __( 'Our Team Widget', 'ots' ), $options );

	}

	private function parse_args( $instance ) {

		$defaults = array(
			'title'             => __( 'Meet Our Team', 'ots' ),
			'group'             => '',
			'limit'             => 'ALL',
			'template'          => Defaults::TEMPLATE,
			'single_template'   => Defaults::SINGLE_TEMPLATE
		);

		return wp_parse_args( $instance, $defaults );

	}

	public function widget( $args, $instance ) {

	    enqueue_team_view_scripts();

		$instance = $this->parse_args( $instance );

		echo $args[ 'before_widget' ];
		echo $args[ 'before_title' ] . esc_html( $instance['title'] ) . $args[ 'after_title' ];

		echo do_team_view_output( $instance );

	}

	public function update( $new_instance, $old_instance ) {

	    $instance = $old_instance;

        $title    = $new_instance['title'];
        $group    = $new_instance['group'];
        $limit    = $new_instance['limit'];
        $template = $new_instance['template'];
        $single   = $new_instance['single_template'];


        $groups = get_groups();

        $instance['title'] = strip_tags( $title );
        $instance['group'] = array_key_exists( $group, $groups ) ? $group : '';

        $group_templates  = get_templates();
        $single_templates = get_single_templates();

        $instance['template']        = array_key_exists( $template, $group_templates ) ? $template : Defaults::TEMPLATE;
        $instance['single_template'] = array_key_exists( $single, $single_templates )  ? $single   : Defaults::SINGLE_TEMPLATE;

		if( $limit > 1 || strtolower( $limit ) === 'all' ) {
			$instance['limit'] = $limit;
		} else {
			$instance['limit'] = 'ALL';
		}

        return $instance;

	}

	public function form( $instance ) {

		$instance = $this->parse_args( $instance ); ?>

		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"
			       class="sc_our_team_widget_title_label">
				<?php _e( 'Title: ', 'ots' ); ?>
			</label>

			<input class="widefat"
			       id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php esc_attr_e( $instance['title'] ); ?>" />

		</p>
		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'group' ) ); ?>"
			       class="sc_our_team_widget_group_label">
				<?php _e( 'Group', 'ots' ); ?>
			</label>

			<?php

                $args = array(
                    'name'     => $this->get_field_name( 'group' ),
                    'selected' => $instance['group'],
                    'options'  => array( '' => __( 'All Groups', 'ots' ) ) + get_groups(),
                    'attrs'    => array(
                        'class' => 'widefat',
                        'id'    => $this->get_field_id( 'group' )
                    )
                );

                settings_select_box( $args );

			?>

		</p>
		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"
			       class="sc_our_team_widget_limit_label">
				<?php _e( 'Limit to Show (Number or "ALL")', 'ots' ); ?>
			</label>

			<input class="widefat"
			       id="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"
			       name="<?php esc_attr_e( $this->get_field_name( 'limit' ) ); ?>"
			       value="<?php esc_attr_e( $instance['limit'] ); ?>" />

		</p>
		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'template' ) ); ?>"
			       class="sc_our_team_widget_limit_label">
				<?php _e( 'Template', 'ots' ); ?>
			</label>

			<?php

				$args = array(
					'name'     => $this->get_field_name( 'template' ),
					'selected' => $instance['template'],
					'options'  => array( '' => __( 'Select a template', 'ots' ) ) + get_templates(),
					'attrs'    => array(
						'class' => 'widefat',
						'id'    => $this->get_field_id( 'template' )
					)
			    );

				settings_select_box( $args );

			?>

		</p>
		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'single_template' ) ); ?>"
			       class="sc_our_team_widget_limit_label">
				<?php _e( 'Single Template', 'ots' ); ?>
			</label>

			<?php

				$args = array(
					'name'     => $this->get_field_name( 'single_template' ),
					'selected' => $instance['single_template'],
					'options'  => array( '' => __( 'Select a template', 'ots' ) ) + get_single_templates(),
					'attrs'    => array(
						'class' => 'widefat',
						'id'    => $this->get_field_id( 'single_template' )
					)
				);

				settings_select_box( $args );

			?>

		</p>

	<?php }

}
