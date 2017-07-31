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
			'title'    => __( 'Meet Our Team', 'ots' ),
			'group'    => '',
			'limit'    => 'ALL',
			'template' => 'grid',
			'single'   => 'standard'
		);

		return wp_parse_args( $instance, $defaults );

	}

	public function widget( $args, $instance ) {

		$instance = $this->parse_args( $instance );



	}

	public function update( $new_instance, $old_instance ) {

	    $instance = $old_instance;

        $title    = $new_instance['title'];
        $group    = $new_instance['group'];
        $limit    = $new_instance['limit'];
        $template = $new_instance['template'];
        $single   = $new_instance['single'];

        $instance['title']    = strip_tags( $title );
        $instance['group']    = strip_tags( $group );

        $group_templates  = get_templates();
        $single_templates = get_single_templates();

        $template['template'] = array_key_exists( $template, $group_templates ) ? $template : Defaults::TEMPLATE;
        $template['single']   = array_key_exists( $single, $single_templates )  ? $single   : Defaults::SINGLE_TEMPLATE;

		if( $limit > 1 || strtolower( $limit ) === 'all' ) {
			$instance['limit'] = $limit;
		} else {
			$instance['limit'] = 'ALL';
		}

        return $instance;

	}

	public function form( $instance ) {

		$terms = get_terms( array(
			'taxonomy'   => 'team_member_position',
			'hide_empty' => false
		) );

		$instance = wp_parse_args( $instance, array(
			'title'    => __( 'Meet Our Team', 'ots' ),
			'group'    => '',
			'limit'    => 'ALL',
			'template' => 'grid',
			'single'   => 'standard'
		) );

		?>

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

			<select id="<?php esc_attr_e( $this->get_field_id( 'group' ) ); ?>"
			        name="<?php esc_attr_e( $this->get_field_name( 'group' ) ); ?>"
			        class="widefat">

				<option value="ignore-group"><?php _e( 'All Groups', 'ots' ); ?></option>

				<?php foreach( $terms as $term ) : ?>

					<option value="<?php esc_attr_e( $term->term_id ); ?>" <?php selected( $instance['group'], $term->term_id ); ?>>

						<?php esc_html_e( $term->name ); ?>

					</option>

				<?php endforeach; ?>

			</select>

		</p>
		<p>

			<label for="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"
			       class="sc_our_team_widget_limit_label">
				<?php _e( 'Limit to Show (Number or "ALL")', 'ots' ); ?>
			</label>

			<input class="widefat"
			       id="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"
			       name="<?php esc_attr_e( $this->get_field_name( 'imit' ) ); ?>"
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

			<label for="<?php esc_attr_e( $this->get_field_id( 'single' ) ); ?>"
			       class="sc_our_team_widget_limit_label">
				<?php _e( 'Single Template', 'ots' ); ?>
			</label>

			<?php

				$args = array(
					'name'     => $this->get_field_name( 'single' ),
					'selected' => $instance['single'],
					'options'  => array( '' => __( 'Select a template', 'ots' ) ) + get_single_templates(),
					'attrs'    => array(
						'class' => 'widefat',
						'id'    => $this->get_field_id( 'single' )
					)
				);

				settings_select_box( $args );

			?>

		</p>

	<?php }

}