<?php
/**
 * Update the plugin info using Cron.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */

namespace ThatPluginCompany\PluginNamespace\Frontend;

use ThatPluginCompany\PluginNamespace\Admin\API as API;

/**
 * Update the plugin info using Cron.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */
class Cron {

	/**
	 * Execute Cron.
	 */
	public function execute() {
		$api = new API();
		$api->set_plugin_options();
	}

}
