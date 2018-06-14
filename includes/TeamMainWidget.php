<?php

namespace ots;

class TeamMainWidget extends \WP_Widget {

	public function __construct() {

		$options = array(
			'description' => __( 'Use this widget to display the Our Team anywhere on the site.', 'ots' )
		);

		parent::__construct( 'ots_main_widget', __( 'Our Team Widget', 'ots' ), $options );

	}

	private function single_templates() {

	    $default = array(
	      'disable' => __( 'Disabled', 'ots' )
        );

	    return apply_filters( 'ots_inline_templates', $default );

    }

	private function parse_args( $instance ) {

		$defaults = array(
            'id'                => '',
			'title'             => __( 'Meet Our Team', 'ots' ),
			'group'             => '',
			'limit'             => 'all',
			'columns'           => Defaults::GRID_COLUMNS,
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

	    $id       = $new_instance['id'];
        $title    = $new_instance['title'];
        $group    = $new_instance['group'];
        $limit    = $new_instance['limit'];
        $columns  = $new_instance['columns'];
        $template = $new_instance['template'];
        $single   = $new_instance['single_template'];


        $groups = get_groups( 'slug' );

		$instance['id']    = sanitize_title( $id );
        $instance['title'] = strip_tags( $title );
        $instance['group'] = array_key_exists( $group, $groups ) ? $group : '';

        $group_templates  = get_templates();
        $single_templates = $this->single_templates();

        $instance['columns'] = absint( $columns ) > 0 ? $columns : Defaults::GRID_COLUMNS;

        $instance['template']        = array_key_exists( $template, $group_templates )  ? $template : Defaults::TEMPLATE;
        $instance['single_template'] = array_key_exists( $single,   $single_templates ) ? $single   : Defaults::SINGLE_TEMPLATE;

		if( strtolower( $limit ) === 'all' || $limit > 0 ) {
			$instance['limit'] = $limit;
		} else {
			$instance['limit'] = 'all';
		}

        return $instance;

	}

	public function form( $instance ) {

		$instance = $this->parse_args( $instance ); ?>

		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>">
				<?php _e( 'Title', 'ots' ); ?>
			</label>

			<input class="widefat"
			       id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php esc_attr_e( $instance['title'] ); ?>" />

		</p>
        <p>

            <label for="<?php esc_attr_e( $this->get_field_id( 'id' ) ); ?>">
				<?php _e( 'ID', 'ots' ); ?>
            </label>

            <input class="widefat"
                   id="<?php esc_attr_e( $this->get_field_id( 'id' ) ); ?>"
                   name="<?php esc_attr_e( $this->get_field_name( 'id' ) ); ?>"
                   value="<?php esc_attr_e( $instance['id'] ); ?>" />

        </p>
		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'template' ) ); ?>">
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

            <?php $templates = $this->single_templates(); ?>

            <?php if ( count( $templates ) == 1 ) : ?>

                <input type="hidden"
                       value="disable"
                       name="<?php esc_attr_e( $this->get_field_name( 'single_template' ) ); ?>"/>

                <label>

                    <input type="checkbox"
                           value="standard"
                           name="<?php esc_attr_e( $this->get_field_name( 'single_template' ) ); ?>"

                        <?php checked( 'disable', $instance['single_template'] ); ?>/>

                    <?php _e( 'Enable linking to single members', 'ots' ); ?>

                </label>

            <?php else : ?>


            <label for="<?php esc_attr_e( $this->get_field_id( 'single_template' ) ); ?>">
                <?php _e( 'Single Template', 'ots' ); ?>
            </label>

                <?php

                    $args = array(
                        'name'     => $this->get_field_name( 'single_template' ),
                        'selected' => $instance['single_template'],
                        'options'  => array( '' => __( 'Single Member', 'ots' ) ) + $templates,
                        'attrs'    => array(
                            'class' => 'widefat',
                            'id'    => $this->get_field_id( 'single_template' )
                        )
                    );

                    settings_select_box( $args );

                ?>

            <?php endif; ?>

		</p>
        <p>

            <label for="<?php esc_attr_e( $this->get_field_id( 'group' ) ); ?>">
				<?php _e( 'Group', 'ots' ); ?>
            </label>

			<?php

			$args = array(
				'name'     => $this->get_field_name( 'group' ),
				'selected' => $instance['group'],
				'options'  => array( '' => __( 'All Groups', 'ots' ) ) + get_groups( 'slug' ),
				'attrs'    => array(
					'class' => 'widefat',
					'id'    => $this->get_field_id( 'group' )
				)
			);

			settings_select_box( $args );

			?>

        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'columns' ) ); ?>"
                   class="sc_our_team_widget_columns_label">
                <?php _e( 'Grid Columns', 'ots' ); ?>
            </label>

            <input class="widefat ots-grid-columns"
                   type="number"
                   id="<?php esc_attr_e( $this->get_field_id( 'columns' ) ); ?>"
                   name="<?php esc_attr_e( $this->get_field_name( 'columns' ) ); ?>"
                   value="<?php echo esc_attr_e( absint( $instance['columns'] ) ); ?>" />
        </p>
        <div class="ots-widget-limit">
            <p>
                <label for="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"
                       class="sc_our_team_widget_limit_label">
					<?php _e( 'Limit to Show', 'ots' ); ?>
                </label>

                <input class="widefat ots-limit-number"
                       type="number"
                       id="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"
                       name="<?php esc_attr_e( $this->get_field_name( 'limit' ) ); ?>"
                       value="<?php echo (  strtolower( $instance['limit'] ) !== 'all' ? esc_attr( $instance['limit'] ) : '' ); ?>"

					<?php disabled( 'all', strtolower( $instance['limit'] ) ); ?> />
            </p>
            <p>
				<?php _e( '- or -', 'ots' ); ?>

                <label>
                    <input type="checkbox"
                           class="ots-widget-display-all"
                           name="<?php esc_attr_e( $this->get_field_name( 'limit' ) ); ?>"
						<?php checked( 'all', strtolower( $instance['limit'] ) ); ?> /><?php _e( 'Display All', 'ots' ); ?>
                </label>
            </p>
        </div>

	<?php }

}
