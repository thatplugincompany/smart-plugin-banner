<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */

namespace ThatPluginCompany\PluginNamespace;

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */
class Admin {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Get time string used for WordPress uploads folder.
	 *
	 * @return string
	 */
	private function get_time_string() {
		$time = current_time( 'mysql' );
		$y    = substr( $time, 0, 4 );
		$m    = substr( $time, 5, 2 );

		return "/$y/$m";
	}

	/**
	 * Set a custom upload folder.
	 *
	 * @param array $uploads Upload directory data with keys of 'path', 'url',
	 *     'subdir, 'basedir', and 'error'.
	 */
	public function set_upload_folder( $uploads ) {
		$dir = '/@@prefix';
		$time = $this->get_time_string();

		$uploads['path'] = str_replace( $time, '', $uploads['path'] ) . $dir;
		$uploads['url']  = str_replace( $time, '', $uploads['url'] ) . $dir;

		return $uploads;
	}

	/**
	 * Filter uploads and change the directory for uploads related to @@name.
	 *
	 * @param array $file Info about file being uploaded.
	 * @return array
	 */
	public function filter_uploads( $file ) {
		// if ( strpos( $file['name'], '@@prefix' ) !== false ) {
			add_filter( 'upload_dir', [ $this, 'set_upload_folder' ] );
		// }

		return $file;
	}

}
