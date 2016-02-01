<?php
/**
 * Plugin Name: Easy Populate Posts
 * Description: The is a <strong>helper plugin</strong> that allows developers to populate the sites with <strong>random content</strong> (posts with random tags, random images, date in the past or in the future, sticky flag, etc.), but with specific meta values and taxonomy terms associated, if the case.
 * Author: Iulia Cazan
 * Version: 1.0
 * Author URI: https://profiles.wordpress.org/iulia-cazan
 * License: GPL2
 *
 * Copyright (C) 2015 Iulia Cazan
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class SISANU_Popupate_Posts
{
	private static $instance;
	public static $max_random = 30;
	var $exclude_post_type = array();
	var $exclude_tax_type = array();
	var $images_url = '';
	var $images_path = '';
	var $plugin_url = '';
	var $init_images = '';

	/** Get active object instance */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new SISANU_Popupate_Posts();
		}
		return self::$instance;
	}

	/** Class constructor */
	public function __construct() {
		$this->init();
	}

	/** Run action and filter hooks */
	private function init() {
		if ( is_admin() ) {
			$this->init_images = 'https://scontent-ams2-1.cdninstagram.com/hphotos-xfa1/t51.2885-15/e35/11887196_170301359972612_287185108_n.jpg' 
			. chr(13) .  
			'https://igcdn-photos-a-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-15/e35/11917868_519529781543664_714639156_n.jpg'
			. chr(13) . 
			'https://scontent-ams2-1.cdninstagram.com/hphotos-xaf1/t51.2885-15/e35/11850216_1634208286862392_1171422049_n.jpg'
			. chr(13) . 
			'https://scontent-vie1-1.cdninstagram.com/hphotos-xaf1/t51.2885-15/e35/11917852_167757570230345_1616497199_n.jpg'
			. chr(13) . 
			'https://scontent-ams2-1.cdninstagram.com/hphotos-xaf1/t51.2885-15/e35/11934714_1624717791122068_1732995429_n.jpg'
			;

			$this->plugin_url = admin_url( 'tools.php?page=populate-posts-settings' );
			$this->exclude_post_type = array( 
				'nav_menu_item', 
				'revision', 
				'attachment', 
			);
			$this->exclude_tax_type = array( 
				'nav_menu', 
				'link_category', 
				'post_format', 
				'post_tag', 
			);
			
			$upload_dir = wp_upload_dir();
			$this->images_path = $upload_dir['basedir'] . '/spp_tmp/';
			$this->images_url = $upload_dir['baseurl'] . '/spp_tmp/';

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		}
	}

	/** Returns an array of all the post types registered in the application */
	function get_all_post_types_plugin() {
		$post_types = get_post_types( array(), 'objects' );
		if ( ! empty( $post_types ) && ! empty( $this->exclude_post_type ) ) {
			foreach ( $this->exclude_post_type as $k ) {
				unset( $post_types[$k] );
			}
		}
		return $post_types;
	}

	/** Returns an array of all the taxonomies registered in the application */
	function get_all_tax_types_plugin() {
		$tax = get_taxonomies( array() );
		if ( ! empty( $tax ) && ! empty( $this->exclude_tax_type ) ) {
			foreach ( $this->exclude_tax_type as $k ) {
				unset( $tax[$k] );
			}
		}
		return $tax;
	}

	/** The actions to be executed when the plugin is activated */
	function activate_plugin() {
		if ( ! file_exists( $this->images_path ) ) {
			@mkdir( $this->images_path );
		}

		$default_settings = array(
			'post_type'    => 'post',
			'content_type' => 0,
			'date_type'    => 1,
			'has_sticky'   => 0,
			'max_number'   => 10,
			'content_p'    => 0,
			'tags_list'    => 'Star Wars, Rebel, Force, Obi-Wan, Jedi, Senate, Alderaan, Luke',
			'meta_key'     => '',
			'meta_value'   => '',
			'meta_key2'    => '',
			'meta_value2'  => '',
			'taxonomy'     => '',
			'term_id'      => '',
			'images_list'  => '',
			'images_path'  => '',
			'title_prefix' => '',
		);

		/** This is for loading images on activation
		$this->set_local_images_from_options( $default_settings['images_list'] );
		$local_images = $this->get_local_images();
		$default_settings['images_path'] = ( ! empty( $local_images ) ) ? implode( chr( 13 ), $local_images ) : '';
		$default_settings['images_list'] = '';
		*/
		update_option( 'spp_settings', $default_settings );
	}

	/** The actions to be executed when the plugin is deactivated */
	function deactivate_plugin() {
		delete_option( 'spp_settings' );
		if ( file_exists( $this->images_path ) && is_dir( $this->images_path ) ) {
			$dir = opendir( $this->images_path );
			while ( ( false !== ( $file = readdir( $dir ) ) ) ) {
				if ( $file != "." and $file != ".." ) {
					@unlink( $this->images_path . $file );
				}
			}
			closedir( $dir );
			@rmdir( $this->images_path );
		}
	}

	/** Add the new menu in general options section that allows to configure the plugin settings */
	function admin_menu() {
		add_submenu_page(
			'tools.php',
			'<div class="dashicons dashicons-admin-generic"></div> ' . __( 'Easy Populate Posts', 'spp' ),
			'<div class="dashicons dashicons-admin-generic"></div> ' . __( 'Easy Populate Posts', 'spp' ),
			'manage_options',
			'populate-posts-settings',
			array( $this, 'populate_posts_settings' )
		);
	}

	/** Create the plugin images sources */
	function set_local_images_from_options( $images_list ) {
		if ( ! empty( $images_list ) ) {
			$all_local = array();
			if ( ! file_exists( $this->images_path ) ) {
				@mkdir( $this->images_path );
			}
			$photos = explode( chr( 13 ), $images_list );
			foreach ( $photos as $p ) {
				$p = trim( $p );
				$ext = explode( '.', $p );
				$photo_name = md5( $p ) . '.' . $ext[count( $ext ) - 1];
				$dest = $this->images_path . $photo_name;
				if ( ! file_exists( $dest ) ) {
					if ( @fclose( @fopen( $p, 'r' ) ) ) {
						@copy( $p, $dest );
						array_push( $all_local, $photo_name );
					}
				} else {
					array_push( $all_local, $photo_name );
				}
			}
			return $all_local;
		}
	}

	/** Read the plugin images sources and return the array of images */
	function get_local_images() {
		if ( file_exists( $this->images_path ) ) {
			$files = array();
			$dir = opendir( $this->images_path );
			while ( ( false !== ( $file = readdir( $dir ) ) ) ) {
				if ( $file != "." and $file != ".." ) {
					array_push( $files, $file );
				}
			}
			closedir( $dir );
			return $files;
		}
	}

	/** The plugin settings and trigger for the populate of posts */
	function populate_posts_settings() {

		if ( ! empty( $_GET['spp_del'] ) ) {
			$settings = get_option( 'spp_settings' );
			if ( ! empty( $settings['images_path'] ) ) {
				$i = (int) $_GET['spp_del'] - 1;
				$p = explode( chr( 13 ), $settings['images_path'] );
				@unlink( $this->images_path . $p[$i] );
				unset( $p[$i] );
				$settings['images_path'] = implode( chr( 13 ), $p );
				update_option( 'spp_settings', $settings );
			}
			echo '<script>window.location=\'' . $this->plugin_url . '\';</script>';
			wp_die();
		}

		/** Verify user capabilities in order to deny the access if the user does not have the capabilities */
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Action not allowed.', 'spp' ) );
		} else {

			/** User can save the options */
			if ( ! empty( $_POST ) ) {
				if ( ! isset( $_POST['spp_settings_nonce'] ) || ! wp_verify_nonce( $_POST['spp_settings_nonce'], 'spp_settings_save' ) ) {
					wp_die( __( 'Action not allowed.', 'spp' ), __( 'Security Breach', 'spp' ) );
				}
				
				$default_settings = get_option( 'spp_settings' );
				$default_settings['title_prefix'] = sanitize_text_field( $_POST['spp']['title_prefix'] );
				$default_settings['post_type'] = sanitize_text_field( $_POST['spp']['post_type'] );
				$default_settings['tags_list'] = sanitize_text_field( $_POST['spp']['tags_list'] );
				$default_settings['meta_key'] = sanitize_text_field( $_POST['spp']['meta_key'] );
				$default_settings['meta_value'] = sanitize_text_field( $_POST['spp']['meta_value'] );
				$default_settings['meta_key2'] = sanitize_text_field( $_POST['spp']['meta_key2'] );
				$default_settings['meta_value2'] = sanitize_text_field( $_POST['spp']['meta_value2'] );
				$default_settings['taxonomy'] = sanitize_text_field( $_POST['spp']['taxonomy'] );
				$default_settings['term_id'] = sanitize_text_field( $_POST['spp']['term_id'] );
				$default_settings['content_type'] = (int) $_POST['spp']['content_type'];
				$default_settings['content_p'] = (int) $_POST['spp']['content_p'];
				$default_settings['date_type'] = (int) $_POST['spp']['date_type'];
				$default_settings['has_sticky'] = (int) $_POST['spp']['has_sticky'];
				$default_settings['max_number'] = abs( (int) $_POST['spp']['max_number'] );
				$default_settings['max_number'] = ( $default_settings['max_number'] > self::$max_random ) ? self::$max_random : $default_settings['max_number'];
				$default_settings['images_list'] = implode( chr( 13 ), array_map( 'sanitize_text_field', explode( chr( 13 ), $_POST['spp']['images_list'] ) ) );

				$this->set_local_images_from_options( $default_settings['images_list'] );
				$local_images = $this->get_local_images();
				$default_settings['images_path'] = ( ! empty( $local_images ) ) ? implode( chr( 13 ), $local_images ) : '';

				update_option( 'spp_settings', $default_settings );
			}
		}

		$settings = get_option( 'spp_settings' );
		if ( empty( $settings ) ) {
			$settings = $default_settings;
		}
		?>
		<style>
			.spp_figure {
				display: inline-block;
				vertical-align: middle;
				position: relative;
				width: 100px;
				height: 100px;
				overflow: hidden;
				border-radius: 3px;
				border: 3px solid #FFF;
				margin-right: 3px;
				margin-bottom: 3px;
			}

			.spp_figure img {
				width: auto;
				height: auto;
				max-width: 120px;
				max-height: 120px;
				position: absolute;
				left: -5px;
				top: -5px;
			}

			.spp_figure a {
				display: inline-block;
				width: 35px;
				height: 22px;
				border-radius: 4px;
				overflow: hidden;
				font-size: 22px;
				line-height: 22px;
				position: absolute;
				left: 78px;
				top: 2px;
				z-index: 999;
				background-color: #000;
				color: #FFF;
				text-decoration: none;
			}
			.spp_figure a:hover {
				left: 74px;
				color: #FFF;
				background-color: #C00;
			}

			.spp_figure a span {
				margin: 0px;
				padding: 0px;
				font-size: 22px;
				line-height: 22px;
			}
			#spp_initial_images{display:none;}
		</style>
		<h1><?php _e( 'Easy Populate Posts Settings', 'spp' ); ?></h1>

		<p><?php _e( 'The is a helper plugin that allows developers to populate the sites with <strong>random content</strong> (posts with random tags, images, date in the past or in the future, sticky flag), but with specific meta values and taxonomy terms associated if the case.', 'spp' ); ?></p>

		<form action="" method="post">
			<?php wp_nonce_field( 'spp_settings_save', 'spp_settings_nonce' ); ?>
			
			<table class="wp-list-table widefat striped posts">
				<tr>
					<td>
						<?php _e( 'Content Text', 'spp' ); ?><hr/>
						<select name="spp[content_type]" id="spp_content_type">
							<option value="0"<?php selected( 0, $settings['content_type'] ); ?>>Random</option>
							<option value="1"<?php selected( 1, $settings['content_type'] ); ?>>Star Wars</option>
							<option value="2"<?php selected( 2, $settings['content_type'] ); ?>>Lorem Ipsum</option>
						</select>
					</td>

					<td>
						<?php _e( 'Post Type', 'spp' ); ?><hr/>
						<select name="spp[post_type]" id="spp_post_type">
							<?php
							$all_post_types = $this->get_all_post_types_plugin();
							foreach ( $all_post_types as $k => $v ) :
								?>
								<option value="<?php echo esc_attr( $k ); ?>"<?php selected( $k, $settings['post_type'] ); ?>><?php echo esc_attr( $k ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>

					<td>
						<?php _e( 'Sticky', 'spp' ); ?><hr/>
						<select name="spp[has_sticky]" id="spp_has_sticky">
							<option value="0"<?php selected( 0, $settings['has_sticky'] ); ?>>Random</option>
							<option value="1"<?php selected( 1, $settings['has_sticky'] ); ?>>Yes</option>
							<option value="2"<?php selected( 2, $settings['has_sticky'] ); ?>>No</option>
						</select>
					</td>

					<td>
						<?php _e( 'Taxonomy', 'spp' ); ?><hr/>
						<select name="spp[taxonomy]" id="spp_taxonomy">
							<?php
							$all_tax = $this->get_all_tax_types_plugin();
							foreach ( $all_tax as $k => $v ) : ?>
								<option value="<?php echo esc_attr( $k ); ?>"<?php selected( $k, $settings['taxonomy'] ); ?>><?php echo esc_attr( $k ); ?></option>
							<?php endforeach; ?>
						</select>

						<input type="text" name="spp[term_id]" id="spp_term_id" value="<?php echo esc_attr( $settings['term_id'] ); ?>" size="10" placeholder="<?php echo esc_attr( __( 'term_id', 'spp' ) ); ?>"/>
					</td>

					<td rowspan="2">
						
						<?php _e( 'Images Sources', 'spp' ); ?><hr/>
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td style="padding:0px;">
									<textarea name="spp[images_list]" id="spp_images_list" cols="20" rows="6"><?php echo esc_attr( $settings['images_list'] ); ?></textarea>
								</td>
								<td>
									<p>
									<?php _e( 'Click here to ', 'spp' ); ?>
									<br /><a onclick="jQuery('#spp_images_list').val( jQuery('#spp_initial_images').html() );">
										<?php _e( 'use the plugin', 'spp' ); ?>
										<br /><?php _e( 'sample images.', 'spp' ); ?>
									</a>
									<span id="spp_initial_images"><?php echo $this->init_images; ?></span>
									<br /><br /><?php _e( 'You can add your own', 'spp' ); ?>
									<br /><?php _e( 'images URLs and click the', 'spp' ); ?>
									<br /><?php _e( '"save settings" button', 'spp' ); ?>
									</p>
								</td>
							</tr>
						</table>
						
						
						<br/><?php _e( 'add URLs of images, separated by new line', 'spp' ); ?>
					</td>
				</tr>

				<tr>
					<td>
						<?php _e( 'Paragraphs', 'spp' ); ?><hr/>
						<select name="spp[content_p]" id="spp_content_p">
							<option value="0"<?php selected( 0, $settings['content_p'] ); ?>><?php _e( 'Random', 'spp' ); ?></option>
							<option value="1"<?php selected( 1, $settings['content_p'] ); ?>>1</option>
							<option value="2"<?php selected( 2, $settings['content_p'] ); ?>>2</option>
							<option value="3"<?php selected( 3, $settings['content_p'] ); ?>>3</option>
							<option value="4"<?php selected( 4, $settings['content_p'] ); ?>>4</option>
							<option value="5"<?php selected( 5, $settings['content_p'] ); ?>>5</option>
						</select>
					</td>

					<td>
						<?php _e( 'Maximum', 'spp' ); ?><hr/>
						<input type="text" name="spp[max_number]" id="spp_max_number" value="<?php echo esc_attr( $settings['max_number'] ); ?>" size="3"/>
					</td>

					<td colspan="2">
						<?php _e( 'Random Tags', 'spp' ); ?><hr/>
						<input type="text" name="spp[tags_list]" id="spp_tags_list" value="<?php echo esc_attr( $settings['tags_list'] ); ?>" size="100"/>
						<br/><?php _e( 'separated by comma', 'spp' ); ?>
					</td>
				</tr>

				<tr>
					<td>
						<?php _e( 'Title Prefix', 'spp' ); ?><hr/>
						<input type="text" name="spp[title_prefix]" id="spp_title_prefix" value="<?php echo esc_attr( $settings['title_prefix'] ); ?>" size="15"/>
					</td>

					<td>
						<?php _e( 'Date', 'spp' ); ?><hr/>
						<select name="spp[date_type]" id="spp_date_type">
							<option value="0"<?php selected( 0, $settings['date_type'] ); ?>><?php _e( 'Random', 'spp' ); ?></option>
							<option value="1"<?php selected( 1, $settings['date_type'] ); ?>><?php _e( 'In the past', 'spp' ); ?></option>
							<option value="2"<?php selected( 2, $settings['date_type'] ); ?>><?php _e( 'In the future', 'spp' ); ?></option>
						</select>
					</td>

					<td>
						<?php _e( 'Post Meta', 'spp' ); ?><hr/>
						<input type="text" name="spp[meta_key]" id="spp_meta_key" value="<?php echo esc_attr( $settings['meta_key'] ); ?>" size="20" placeholder="<?php echo esc_attr( __( 'meta_key', 'spp' ) ); ?>" />
						<input type="text" name="spp[meta_value]" id="spp_meta_value" value="<?php echo esc_attr( $settings['meta_value'] ); ?>" size="10" placeholder="<?php echo esc_attr( __( 'meta_value', 'spp' ) ); ?>"/>
					</td>

					<td>
						<?php _e( 'Post Meta 2', 'spp' ); ?><hr/>
						<input type="text" name="spp[meta_key2]" id="spp_meta_key2" value="<?php echo esc_attr( $settings['meta_key2'] ); ?>" size="20" placeholder="<?php echo esc_attr( __( 'meta_key2', 'spp' ) ); ?>" />
						<input type="text" name="spp[meta_value2]" id="spp_meta_value2" value="<?php echo esc_attr( $settings['meta_value2'] ); ?>" size="10" placeholder="<?php echo esc_attr( __( 'meta_value2', 'spp' ) ); ?>"/>
					</td>

					<td>
						<input type="submit" name="spp[save]" id="spp_save" value="<?php _e( 'Save the settings', 'spp' ); ?>" class="button"/> 
						<input type="submit" name="spp[execute]" id="spp_execute" value="<?php _e( 'Execute Posts Add', 'spp' ); ?>" class="button button-primary"/>
					</td>
				</tr>

			<?php
			if (! empty( $settings['images_path'] )) {
				?>
				<tr>
					<td colspan="5">
					<?php
					$p = explode( chr( 13 ), $settings['images_path'] );
					if ( count( $p ) != 0 ) :
						_e( 'These are the images that will be set randomly as posts featured image to the posts that will be added when you click the "execute add" button.', 'spp' );
						echo '<hr />';
						foreach ($p as $k => $ph) : ?>
							<span class="spp_figure"><a href="<?php echo $this->plugin_url . '&spp_del=' . ( $k + 1 ); ?>"><span class="dashicons dashicons-no"></span></a><img src="<?php echo $this->images_url . $ph; ?>"/></span>
						<?php
						endforeach;
					endif;
					?>
					</td>
				</tr>
				<?php
			}
			?>
			</table>

			<?php
			if ( ! empty( $_POST['spp']['execute'] ) ) : ?>
				<hr />
				<h2><?php _e( 'This is the added content', 'spp' ); ?></h2>
				<table class="wp-list-table widefat striped posts">
					<tr>
						<td><?php $this->execute_add_random_posts(); ?></td>
					</tr>
				</table>
			<?php endif; ?>
		</form>

		<hr />
		<table cellspacing="0">
			<tr>
				<td class="vtopAlign">
					<div class="floatright textright">
						<span class="dashicons dashicons-smiley"></span> <b><?php _e( 'I don\'t mind if you donate !', 'spp' ); ?></b>
						<br/><?php _e( 'It means you apreciate the time I spent to develop the plugin for your benefit.', 'spp' ); ?>
						<br/><b><?php _e( 'Thank you !', 'spp' ); ?></b>
					</div>
				</td>
				<td class="vtopAlign">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="JJA37EHZXWUTJ"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form>
				</td>
			</tr>
		</table>
	<?php
	}

	/** Create a random post title */
	function get_random_title( $text_elements, $min_w = 4 ) {
		$nn = $text_elements[mt_rand( 0, count( $text_elements ) - 1 )];
		$nn = preg_replace( '[\!\?]', '.', $nn );
		$n = explode( '.', $nn );
		shuffle( $n );
		$name = $n[0];
		$words = explode( ' ', $name );
		$max_w = count( $words ) - 1;
		if ( $max_w <= $min_w ) {
			$min_w = $max_w;
		}
		$name = trim( implode( ' ', array_slice( $words, 0, mt_rand( $min_w, $max_w ) ) ) );
		$name = ucfirst( $name );
		return $name;
	}

	/** Create a random post content */
	function get_random_description( $text_elements, $max_blocks = 1 ) {
		$texts = array_slice( $text_elements, 0, $max_blocks );
		$text = '<p>' . implode( '</p><p>', $texts ) . '</p>';
		return $text;
	}

	/** Execute the content populate */
	function execute_add_random_posts() {
		$settings = get_option( 'spp_settings' );
		$this->text_elements = array(
			0 => array(
				"I don't know what you're talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan Partially, but it also obeys your commands. Oh God, my uncle. How am I ever gonna explain this? What!? A tremor in the Force. The last time I felt it was in the presence of my old master.",
				"Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. I need your help, Luke. She needs your help. I'm getting too old for this sort of thing. I need your help, Luke. She needs your help. I'm getting too old for this sort of thing. Look, I ain't in this for your revolution, and I'm not in it for you, Princess. I expect to be well paid. I'm in it for the money.",
				"Red Five standing by. The plans you refer to will soon be back in our hands. Red Five standing by. I'm surprised you had the courage to take the responsibility yourself. She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There'll be no one to stop us this time! No! Alderaan is peaceful. We have no weapons. You can't possibly",
				"What?! Don't be too proud of this technological terror you've constructed. The ability to destroy a planet is insignificant next to the power of the Force. I have traced the Rebel spies to her. Now she is my only link to finding their secret base.",
				"Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Red Five standing by. Red Five standing by.",
				"The plans you refer to will soon be back in our hands. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. A tremor in the Force. The last time I felt it was in the presence of my old master. The plans you refer to will soon be back in our hands. You're all clear, kid. Let's blow this thing and go home!",
				"Obi-Wan is here. The Force is with him. The plans you refer to will soon be back in our hands. What good is a reward if you ain't around to use it? Besides, attacking that battle station ain't my idea of courage. It's more like... suicide. I have traced the Rebel spies to her. Now she is my only link to finding their secret base.",
				"Oh God, my uncle. How am I ever gonna explain this? I don't know what you're talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan No! Alderaan is peaceful. We have no weapons. You can't possibly...",
				"She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There'll be no one to stop us this time! I care. So, what do you think of her, Han? You are a part of the Rebel Alliance and a traitor! Take her away! Hokey religions and ancient weapons are no match for a good blaster at your side, kid.",
				"He is here. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. Red Five standing by. Partially, but it also obeys your commands. The more you tighten your grip, Tarkin, the more star systems will slip through your fingers. I'm surprised you had the courage to take the responsibility yourself.",
				"I call it luck. As you wish. Ye-ha! I can't get involved! I've got work to do! It's not that I like the Empire, I hate it, but there's nothing I can do about it right now. It's such a long way from here. I don't know what you're talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan",
				"Escape is not his plan. I must face him, alone. She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There'll be no one to stop us this time! What!? Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. In my experience, there is no such thing as luck.",
				"Still, she's got a lot of spirit. I don't know, what do you think? I want to come with you to Alderaan. There's nothing for me here now. I want to learn the ways of the Force and be a Jedi, like my father before me. I need your help, Luke. She needs your help. I'm getting too old for this sort of thing. Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. As you wish. Red Five standing by.",
				"I have traced the Rebel spies to her. Now she is my only link to finding their secret base. She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There'll be no one to stop us this time! I need your help, Luke. She needs your help. I'm getting too old for this sort of thing. Obi-Wan is here. The Force is with him. No! Alderaan is peaceful. We have no weapons. You can't possibly...",
				"Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. I don't know what you're talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan Partially, but it also obeys your commands. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. A tremor in the Force. The last time I felt it was in the presence of my old master.",
				"I have traced the Rebel spies to her. Now she is my only link to finding their secret base. You're all clear, kid. Let's blow this thing and go home! Dantooine. They're on Dantooine.",
				"I want to come with you to Alderaan. There's nothing for me here now. I want to learn the ways of the Force and be a Jedi, like my father before me. I'm trying not to, kid. Look, I can take you as far as Anchorhead. You can get a transport there to Mos Eisley or wherever you're going. I don't know what you're talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan Leave that to me. Send a distress signal, and inform the Senate that all on board were killed.",
				"I have traced the Rebel spies to her. Now she is my only link to finding their secret base. Oh God, my uncle. How am I ever gonna explain this? You mean it controls your actions? I find your lack of faith disturbing.",
				"I don't know what you're talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan What?! Hey, Luke! May the Force be with you.",
				"Don't act so surprised, Your Highness. You weren't on any mercy mission this time. Several transmissions were beamed to this ship by Rebel spies. I want to know what happened to the plans they sent you. All right. Well, take care of yourself, Han. I guess that's what you're best at, ain't it? I don't know what you're talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan",
			),
			1 => array(
				"Eaque ipsa quae ab illo inventore veritatis et quasi. Sed ut perspiciatis unde omnis iste natus error sit voluptatem. Totam rem aperiam. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam.",
				"Architecto beatae vitae dicta sunt explicabo. Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Corrupti quos dolores et quas molestias excepturi sint occaecati.",
				"Architecto beatae vitae dicta sunt explicabo. Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit. Sed ut perspiciatis unde omnis iste natus error sit voluptatem. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae.",
				"Et harum quidem rerum facilis est et expedita distinctio. Quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Do eiusmod tempor incididunt ut labore et dolore magna aliqua. Do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
				"Eaque ipsa quae ab illo inventore veritatis et quasi. Eaque ipsa quae ab illo inventore veritatis et quasi. Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Fugiat quo voluptas nulla pariatur? Corrupti quos dolores et quas molestias excepturi sint occaecati.",
				"Esse cillum dolore eu fugiat nulla pariatur. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit. Itaque earum rerum hic tenetur a sapiente delectus. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam. Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. Esse cillum dolore eu fugiat nulla pariatur.",
				"Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo. Fugiat quo voluptas nulla pariatur?",
				"Laboris nisi ut aliquip ex ea commodo consequat. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.",
				"At vero eos et accusamus. At vero eos et accusamus. Itaque earum rerum hic tenetur a sapiente delectus. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.",
				"Itaque earum rerum hic tenetur a sapiente delectus. Et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque. Esse cillum dolore eu fugiat nulla pariatur.",
				"Fugiat quo voluptas nulla pariatur? Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Et harum quidem rerum facilis est et expedita distinctio.",
				"Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Totam rem aperiam. Excepteur sint occaecat cupidatat non proident, sunt in culpa. Do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
				"Eaque ipsa quae ab illo inventore veritatis et quasi. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo. Architecto beatae vitae dicta sunt explicabo. Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.",
				"Itaque earum rerum hic tenetur a sapiente delectus. Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo.",
				"Qui officia deserunt mollit anim id est laborum. Laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipisicing elit. At vero eos et accusamus. Inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.",
				"Ut enim ad minim veniam, quis nostrud exercitation ullamco. Animi, id est laborum et dolorum fuga. Fugiat quo voluptas nulla pariatur? Sed ut perspiciatis unde omnis iste natus error sit voluptatem. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam.",
				"Do eiusmod tempor incididunt ut labore et dolore magna aliqua. Itaque earum rerum hic tenetur a sapiente delectus. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.",
				"Nihil molestiae consequatur, vel illum qui dolorem eum. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam. Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. Ut enim ad minim veniam, quis nostrud exercitation ullamco. Duis aute irure dolor in reprehenderit in voluptate velit.",
				"Totam rem aperiam. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo. Eaque ipsa quae ab illo inventore veritatis et quasi. At vero eos et accusamus.",
				"Itaque earum rerum hic tenetur a sapiente delectus. Itaque earum rerum hic tenetur a sapiente delectus. Animi, id est laborum et dolorum fuga. Excepteur sint occaecat cupidatat non proident, sunt in culpa. Fugiat quo voluptas nulla pariatur? Architecto beatae vitae dicta sunt explicabo.",
			)
		);

		if ( $settings['content_type'] == 0 ) {
			$text_elements = array_merge( $this->text_elements[0], $this->text_elements[1] );
		} else {
			$text_elements = $this->text_elements[$settings['content_type'] - 1];
		}

		$photos = array();
		if ( ! empty( $settings['images_path'] ) ) {
			$photos = explode( chr( 13 ), $settings['images_path'] );
			$all_local = array();
			foreach ( $photos as $p ) {
				array_push( $all_local, $this->images_path . $p );
			}
			$photos = $all_local;
			shuffle( $photos );
		}

		for ( $i = 0; $i < $settings['max_number']; $i ++ ) {
			shuffle( $text_elements );
			$name = $this->get_random_title( $text_elements );

			if ( ! empty( $name ) ) {
				$max_blocks = ( $settings['content_p'] == 0 ) ? mt_rand( 1, 6 ) : $settings['content_p'];
				$description = $this->get_random_description( $text_elements, $max_blocks );

				$tags = array();
				if ( ! empty( $settings['tags_list'] ) ) {
					$t = explode( ',', $settings['tags_list'] );
					shuffle( $t );
					$tags = array_slice( $t, 0, mt_rand( 0, count( $t ) - 1 ) );
				}

				$now = current_time( 'timestamp' );
				$now_pref = - 1;
				if ( $settings['date_type'] == 2 ) {
					$now_pref = 1;
				} elseif ( $settings['date_type'] == 0 ) {
					$now_pref = mt_rand( - 1, 0 );
					$now_pref = ( $now_pref == 0 ) ? 1 : - 1;
				}
				$date = $now + $now_pref * mt_rand( 1, 60 ) * DAY_IN_SECONDS;
				$status = ( $date > $now ) ? 'future' : 'publish';

				$name = ( ! empty( $settings['title_prefix'] ) ) ? $settings['title_prefix'] . ' ' . lcfirst( $name ) : $name;
				$post = array(
					'post_content'  => $description,
					'post_name'     => $name,
					'post_title'    => $name,
					'post_status'   => $status,
					'post_type'     => $settings['post_type'],
					'post_date'     => date( 'Y-m-d H:i:s', $date ),
					'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $date ),
					'tags_input'    => $tags,
				);
				$post_id = wp_insert_post( $post );

				if ( ! empty( $post_id ) ) {
					if ( $settings['has_sticky'] == 0 ) {
						if ( mt_rand( 0, 1 ) == 1 ) {
							stick_post( $post_id );
						}
					} elseif ( $settings['has_sticky'] == 1 ) {
						stick_post( $post_id );
					}

					if ( ! empty( $settings['meta_key'] ) ) {
						update_post_meta( $post_id, $settings['meta_key'], $settings['meta_value'] );
					}

					if ( ! empty( $settings['meta_key2'] ) ) {
						update_post_meta( $post_id, $settings['meta_key2'], $settings['meta_value2'] );
					}

					if ( ! empty( $settings['taxonomy'] ) ) {
						wp_set_object_terms( $post_id, array( (int) $settings['term_id'] ), $settings['taxonomy'] );
					}
					
					$photo_src = '';
					if ( ! empty( $photos ) ) {
						$photo = trim( $photos[mt_rand( 0, count( $photos ) - 1 )] );
						if ( ! empty( $photo ) ) {
							$photo_src = self::fetch_media( $photo, $post_id, date( 'Y/m/', $date ),
								sanitize_title_with_dashes( str_replace( '_', '-', $name ) ),
								true, 0
							);
						}
					}

					$image_embed = ( ! empty( $photo_src ) ) ? '<img src="' . esc_url( $photo_src ) . '" width="80" style="float: left; margin-right: 10px;">' : ''; 

					echo '
					<li>
						' . $image_embed . '<h2>' . $name . '</h2> 
						<a href="' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . '">' . __( 'Edit post', 'spp' ) . '</a> | ' . $status . ', ' . date( 'Y-m-d H:i:s', $date ) . ' ';
						if ( count( $tags ) != 0 ) {
							echo '| Tags : <em>' . implode( ', ', $tags ) . '</em> ';
						}
						echo $description . '
						<div class="clear"></div>
					</li>
					';
				}
			}
		}
	}

	/** Copy a file from a url and create the wp media post attached to a post_id */
	public static function fetch_media( $file_url, $post_id, $final_folder = 'spp_tmp/', $prefix = '', $is_featured = true, $menu_order = 0 ) {
		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		global $wpdb;

		if ( ! $post_id ) {
			return false;
		}

		$artDir = 'wp-content/uploads/' . $final_folder;
		if ( ! file_exists( ABSPATH . $artDir ) ) {
			@mkdir( ABSPATH . $artDir, true );
		}

		$explode = explode( '.', $file_url );
		$ext = array_pop( $explode );
		$new_filename = $prefix . $post_id . "." . $ext;
		if ( @fclose( @fopen( $file_url, "r" ) ) ) { //make sure the file actually exists
			if( @copy( $file_url, ABSPATH . $artDir . $new_filename ) ) {
				$siteurl = get_option( 'siteurl' );
				$file_info = getimagesize( ABSPATH . $artDir . $new_filename );
				$artdata = array(
					'post_author'       => 1,
					'post_date'         => current_time( 'mysql' ),
					'post_date_gmt'     => current_time( 'mysql' ),
					'post_title'        => $new_filename,
					'post_status'       => 'inherit',
					'comment_status'    => 'closed',
					'ping_status'       => 'closed',
					'post_name'         => sanitize_title_with_dashes( str_replace( "_", "-", $new_filename ) ),
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql' ),
					'post_parent'       => $post_id,
					'post_type'         => 'attachment',
					'guid'              => $siteurl . '/' . $artDir . $new_filename,
					'post_mime_type'    => $file_info['mime'],
					'post_excerpt'      => '',
					'post_content'      => '',
					'menu_order'        => $menu_order,
				);

				$uploads = wp_upload_dir();
				$save_path = $uploads['basedir'] . '/' . $final_folder . $new_filename;
				$attach_id = wp_insert_attachment( $artdata, $save_path, $post_id );
				if ( $attach_data = wp_generate_attachment_metadata( $attach_id, $save_path ) ) {
					wp_update_attachment_metadata( $attach_id, $attach_data );
				}

				if ( $is_featured ) {
					//optional make it the featured image of the post it's attached to
					$rows_affected = $wpdb->insert(
						$wpdb->prefix . 'postmeta',
						array(
							'post_id'    => $post_id,
							'meta_key'   => '_thumbnail_id',
							'meta_value' => $attach_id
						)
					);
				}
				return $artdata['guid'];
			} else {
				return false;
			}
		} else {
			return false;
		}

		return true;
	}

	function plugin_action_links( $links ) {
		$all = array();
		$all[] = '<a href="'. esc_url( $this->plugin_url ) .'">Settings</a>';
		$all[] = '<a href="http://iuliacazan.ro/easy-populate-posts">Plugin URL</a>';
		$all = array_merge( $all, $links );
		return $all;
	}
}

$SISANU_Popupate_Posts = SISANU_Popupate_Posts::get_instance();

register_activation_hook( __FILE__, array( $SISANU_Popupate_Posts, 'activate_plugin' ) );
register_deactivation_hook( __FILE__, array( $SISANU_Popupate_Posts, 'deactivate_plugin' ) );
