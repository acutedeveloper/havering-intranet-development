<?php
/**
 * Widget API: HI_Nav_Menu_Widget class
 *
 * @package Havering Intranet Plugins
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Class used to implement the Custom Menu widget for Havering Intranet.
 *
 * @since 3.0.0
 *
 * @see WP_Widget
 */
 class HI_Nav_Menu_Widget extends WP_Widget {

	/**
	 * Sets up a new Custom Menu widget instance.
	 *
	 * @since 3.0.0
	 * @access public
	 */
	public function __construct() {
    $options = array(
			'description' => 'Add a custom menu to your sidebar.',
			'name' 	=> 'Intranet Custom Nav Menu',
		);

		parent::__construct('HI_Nav_Menu_Widget','',$options);
	}

	/**
	 * Outputs the content for the current Custom Menu widget instance.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Custom Menu widget instance.
	 */
	public function widget( $args, $instance ) {
		// Get menu

		$nav_menu = ! empty( $instance['hi_nav_menu'] ) ? wp_get_nav_menu_object( $instance['hi_nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

    $before_title_class = str_replace('gradient-dkblue', $instance['hi_titlecolor'].' ',$args['before_title']);

		if ( !empty($instance['title']) )
			echo $before_title_class . $instance['title'] . $args['after_title'];

		$nav_menu_args = array(
			'fallback_cb' => '',
			'menu'        => $nav_menu,
      'container_class' => 'b-container',
      'menu_class' => 'sidebar-links'
		);

		/**
		 * Filter the arguments for the Custom Menu widget.
		 *
		 * @since 4.2.0
		 * @since 4.4.0 Added the `$instance` parameter.
		 *
		 * @param array    $nav_menu_args {
		 *     An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
		 *
		 *     @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
		 *     @type mixed         $menu        Menu ID, slug, or name.
		 * }
		 * @param stdClass $nav_menu      Nav menu object for the current menu.
		 * @param array    $args          Display arguments for the current widget.
		 * @param array    $instance      Array of settings for the current widget.
		 */
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );

		echo $args['after_widget'];

	}

	/**
	 * Handles updating settings for the current Custom Menu widget instance.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( stripslashes( $new_instance['title'] ) );
		}
		if ( ! empty( $new_instance['hi_nav_menu'] ) ) {
			$instance['hi_nav_menu'] = (int) $new_instance['hi_nav_menu'];
		}
    if ( ! empty( $new_instance['hi_titlecolor'] ) ) {
			$instance['hi_titlecolor'] = $new_instance['hi_titlecolor'];
		}
		return $instance;
	}

	/**
	 * Outputs the settings form for the Custom Menu widget.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$title = isset( $instance['title'] ) ? $instance['title'] : '';
    $nav_menu = isset( $instance['hi_nav_menu'] ) ? $instance['hi_nav_menu'] : '';
    $titlecolor = isset( $instance['hi_titlecolor'] ) ? $instance['hi_titlecolor'] : '';

		// Get menus
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		?>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( isset( $GLOBALS['wp_customize'] ) && $GLOBALS['wp_customize'] instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'hi_nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'hi_nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'hi_nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>

    <p>
      <label for="<?php echo $this->get_field_id( 'titlecolor' ); ?>"><?php _e( 'Title color:' ); ?></label>
      <?php

        $style_options[] = array( 'style_class' => 'gradient-dkblue', 'style_name' => 'Dark Blue' );
        $style_options[] = array( 'style_class' => 'gradient-lime', 'style_name' => 'Lime' );
        $style_options[] = array( 'style_class' => 'gradient-pink', 'style_name' => 'Pink' );
        $style_options[] = array( 'style_class' => 'gradient-orange', 'style_name' => 'Orange' );

      ?>
      <select name="<?php echo $this->get_field_name( 'hi_titlecolor' ); ?>" id="hi_titlecolor <?php echo $this->get_field_id( 'hi_titlecolor' ); ?>">
      <?php foreach( $style_options as $style ):?>
        <option <?php echo (isset($titlecolor) && $style['style_class'] == $titlecolor ? "selected" : NULL); ?> value="<?php echo $style['style_class']; ?>"><?php echo $style['style_name']; ?></option>
      <?php endforeach; ?>
      </select>
    </p>

		<?php
	}
}
