<?php
/**
 * Fired during plugin uninstall.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */

namespace ThatPluginCompany\PluginNamespace;

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's
 * uninstall.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */
class Uninstaller {

	/**
	 * Uninstall.
	 *
	 * @since 1.0.0
	 */
	public static function uninstall() {
		$uploads_dir   = wp_upload_dir();
		$thumbnail_dir = trailingslashit( $uploads_dir['basedir'] ) . '__prefix';

		if ( file_exists( $thumbnail_dir ) ) {
			array_map( 'wp_delete_file', glob( $thumbnail_dir . '/*' ) );
		}

		// Uninstall action.
		do_action( '__prefix_uninstall' );
	}

}
