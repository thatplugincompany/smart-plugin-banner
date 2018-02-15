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
		do_action( '@@prefix_uninstall' );
	}

}
