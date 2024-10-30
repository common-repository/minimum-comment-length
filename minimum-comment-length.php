<?php
/*
Plugin Name: Minimum comment length
Version: 1.1
Plugin URI: https://yoast.com/wordpress/plugins/minimum-comment-length/
Description: Check the comment for a set minimum length and disapprove it if it's too short.
Author: Team Yoast
Author URI: https://yoast.com/
License: GPL v3

Check the comment for a set minimum length and disapprove it if it's too short.
Copyright (C) 2008-2014, Joost de Valk - support@yoast.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * Minimum comment length plugin. Check the comment for a set minimum length and disapprove it if it's too short.
 * @package Minimum_Comment_Length
 * @author Joost de Valk 
 * @copyright Copyright (C) 2008-2014, Joost de Valk
 */
if ( ! class_exists( 'Minimum_Comment_Length' ) ) {

	/**
	 * Class containing all the plugins functionality.
	 * @package Minimum_Comment_Length
	 */
	class Minimum_Comment_Length {
		
		var $hook 			= 'min-comment-length';
		var $option_name	= 'min_comment_length_option';
		var $options		= array();
		var $absolute_min	= 5;
		
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		function __construct() {
			// Retrieve the plugin options
			$this->options = get_option( $this->option_name );
			if ( ! is_array( $this->options ) ) {
				$this->set_defaults();
			}

			// Process the comment and check it for length
			add_filter( 'preprocess_comment', 	array( $this, 'check_comment_length' ), 10, 1 );
			
			if ( is_admin() ) {
				// Hook into init for registration of the option and the language files
				add_action(	'admin_init', 			array( $this, 'init' ) );

				// Register the settings page
				add_action( 'admin_menu', 			array( $this, 'add_config_page' ) );

				// Register a link to the settings page on the plugins overview page
				add_filter( 'plugin_action_links', 	array( $this, 'filter_plugin_actions' ), 10, 2 );
			}
		}
		
		/**
		 * PHP 4 Compatible Constructor
		 *
		 * @since 1.0
		 */
		function Minimum_Comment_Length() {
			$this->__construct();
		}
		
		/**
		 * Register the text domain and the options array along with the validation function
		 *
		 * @since 1.0
		 */
		function init() {
			// Allow for localization
			load_plugin_textdomain( 'minimum-comment-length', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			// Register our option array
			register_setting( $this->option_name, $this->option_name, array( &$this, 'options_validate' ) );
		}
		
		/**
		 * Validate the input, make sure comment length is an integer and above the minimum value.
		 *
		 * @since 1.0
		 * @param array $input with unvalidated options.
		 * @return array $input with validated options.
		 */
		function options_validate( $input ) {
			$input['mincomlength'] = (int) $input['mincomlength'];

			if ( ( $this->absolute_min + 1 ) > $input['mincomlength'] || empty( $input['mincomlength'] ) ) {
				add_settings_error( $this->option_name, 'min_length_invalid', sprintf( __( 'The minimum length you entered is invalid, please enter a minimum length above %d.', 'minimum-comment-length' ), $this->absolute_min ) );
				$input['mincomlength'] = 15;
			}
			return $input;
		}
		
		/**
		 * Register the config page for all users that have the manage_options capability
		 *
		 * @since 0.5
		 */
		function add_config_page() {
			add_options_page( __( 'Minimum comment length configuration', 'minimum-comment-length' ), __( 'Min comment length', 'minimum-comment-length' ), 'manage_options', $this->hook, array( &$this, 'config_page' ) );
		}

		/**
		 * Register the settings link for the plugins page
		 *
		 * @param array $links
		 * @param string $file
		 *
		 * @return array
		 *
		 * @since 0.6
		 */
		function filter_plugin_actions( $links, $file ){
			//Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}
			
			if ( $file == $this_plugin ){
				$settings_link = '<a href="options-general.php?page='.$this->hook.'">' . __( 'Settings', 'minimum-comment-length' ) . '</a>';
				array_unshift( $links, $settings_link ); // before other links
			}
			return $links;
		}
		
		/**
		 * Set default values for the plugin. If old, as in pre 1.0, settings are there, use them and then delete them.
		 */
		function set_defaults() {
			// Check whether the old, somewhat badly named option is there, if so, use the data and delete it.
			$old_options = get_option( 'MinComLengthOptions' );
			if ( is_array( $old_options ) ) {
				$this->options = $old_options;
				delete_option( 'MinComLengthOptions' );
			} else {
				// Set some defaults if no settings are set yet
				$this->options['mincomlength'] 		= 15;
				$this->options['mincomlengtherror'] = __("Error: Your comment is too short. Please try to say something useful.", 'minimum-comment-length' );
			}
			
			update_option( $this->option_name, $this->options );
		}
		
		/**
		 * Output the config page
		 *
		 * @since 0.5
		 */
		function config_page() {

			// Show the content of the options array when debug is enabled
			if ( WP_DEBUG ) {
				echo '<pre style="background-color: white; border: 1px solid #aaa; padding: 20px;">Options:<br/><br/>' . print_r( $this->options, 1 ) . '</pre>';
			}
			?>
			<div class="wrap">
				<h2><?php _e( 'Minimum comment length configuration', 'minimum-comment-length' ); ?></h2>
				<form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
					<?php settings_fields( $this->option_name ); ?>
					<table class="form-table">
						<tr valign="top">
							<th scrope="row">
								<label for="mincomlength"><?php _e( 'Minimum comment length', 'minimum-comment-length' ); ?>:</label>
							</th>
							<td>
								<input type="number" class="small-text" min="5" max="255" value="<?php echo $this->options['mincomlength']; ?>" name="<?php echo $this->option_name; ?>[mincomlength]" id="mincomlength" />
							</td>
						</tr>
						<tr valign="top">
							<th scrope="row">
								<label for="mincomlengtherror"><?php _e( 'Error message', 'minimum-comment-length' ); ?>:</label>
							</th>
							<td>
								<textarea rows="5" cols="50" name="<?php echo $this->option_name; ?>[mincomlengtherror]" id="mincomlengtherror"><?php echo esc_html( $this->options['mincomlengtherror'] ); ?></textarea>
							</td>
						</tr>

					</table>
					<p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Settings &raquo;', 'minimum-comment-length' ); ?>" /></p>
				</form>
			</div>
<?php		}	
	
		/**
		 * Check the length of the comment and if it's too short: die.
		 *
		 * @since 0.5
		 *
		 * @param array $comment_data all the data for the comment.
		 *
		 * @return array $comment_data all the data for the comment (only returned when match was made).
		 */
		function check_comment_length( $comment_data ) {
			// Bail early for power editors and admins.
			if ( current_user_can('edit_posts') ) {
				return $comment_data;
			}

			// Check for comment length and die if to short.
			if ( strlen( trim( $comment_data['comment_content'] ) ) < $this->options['mincomlength'] ) {
				wp_die( $this->options['mincomlengtherror'] );
			}

			return $comment_data;
		}	
	}
}

$minimum_comment_length = new Minimum_Comment_Length();