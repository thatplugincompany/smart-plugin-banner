<?php
/**
 * Handles all functionality related to the WordPress plugin repository API.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */

namespace ThatPluginCompany\PluginNamespace\Admin;

/**
 * Handles all functionality related to the WordPress plugin repository API.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */
class API {

	/**
	 * Options wrapper.
	 *
	 * @param  string $option The option in question.
	 * @return string|false
	 * @access private
	 */
	private function get_customizer_option( $option ) {
		$options = get_option( '@@prefix' );

		// Check if options exist.
		if ( ! $options ) {
			return false;
		}

		// Return option if it exists.
		return isset( $options[ $option ] ) ? $options[ $option ] : false;
	}

	/**
	 * Fetch plugin info from the WordPress plugin API.
	 *
	 * @param  string $slug Slug of the plugin to retrieve.
	 * @return array|false
	 * @access private
	 */
	private function get_plugin_info( $slug ) {
		if ( ! isset( $slug ) ) {
			return false;
		}

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$info = plugins_api( 'plugin_information', [ 'slug' => $slug ] );

		if ( is_wp_error( $info ) ) {
			return false;
		}

		$info = [
			'name'   => $info->name,
			'slug'   => $info->slug,
			'author' => $info->author,
			'rating' => $info->rating,
		];

		return $info;
	}

	/**
	 * Fetch plugin thumbnail from WordPress SVN.
	 *
	 * @param  string $slug Slug of the plugin to retrieve.
	 * @return string|false
	 * @access private
	 */
	private function get_plugin_thumbnail( $slug ) {
		if ( ! isset( $slug ) ) {
			return false;
		}

		$image_options = [
			[
				'size' => '256x256',
				'type' => 'png',
			],
			[
				'size' => '128x128',
				'type' => 'png',
			],
			[
				'size' => '256x256',
				'type' => 'jpg',
			],
			[
				'size' => '128x128',
				'type' => 'jpg',
			],
		];

		foreach ( $image_options as $image ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';

			$size            = $image['size'];
			$type            = $image['type'];
			$url             = "https://plugins.svn.wordpress.org/${slug}/assets/icon-${size}.${type}";
			$timeout_seconds = 5;

			// Download file to temp dir.
			$temp_file = download_url( $url, $timeout_seconds );

			if ( is_wp_error( $temp_file ) ) {
				continue;
			}

			$file = [
				'name'     => "@@prefix_thumbnail.${type}",
				'type'     => "image/${type}",
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize( $temp_file ),
			];

			$overrides = [
				'test_form' => false,
			];

			$results = wp_handle_sideload( $file, $overrides );

			if ( ! empty( $results['error'] ) ) {
				continue;
			} else {
				return $results['url'];
			}
		}

		return false;
	}

	/**
	 * Set plugin options based on slug.
	 *
	 * @since 1.0.0
	 * @return void|false
	 */
	public function set_plugin_options() {
		$slug = $this->get_customizer_option( 'plugin_slug' );

		if ( ! isset( $slug ) ) {
			return false;
		}

		$info      = $this->get_plugin_info( $slug );
		$thumbnail = $this->get_plugin_thumbnail( $slug );

		if ( ! isset( $info ) || ! isset( $thumbnail ) ) {
			return false;
		}

		$options = array_merge(
			$info,
			[
				'thumbnail' => $thumbnail,
			]
		);

		update_option( '@@prefix_plugin_options', $options );
	}

}
