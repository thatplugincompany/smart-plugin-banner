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
	 * Plugin options fetched from the API.
	 *
	 * @var array
	 */
	private $options = [];

	/**
	 * Plugin info.
	 *
	 * @var array
	 */
	private $info = [];

	/**
	 * Plugin thumbnail.
	 *
	 * @var string
	 */
	private $thumbnail = '';

	/**
	 * Set options.
	 *
	 * @param bool|string $slug Plugin slug.
	 */
	public function set_options( $slug ) {
		if ( ! isset( $slug ) ) {
			return false;
		}

		$info      = $this->set_info( $slug );
		$thumbnail = $this->set_thumbnail( $slug );

		if ( ! $info || ! $thumbnail ) {
			return false;
		}

		$options = array_merge(
			$this->get_info(),
			[
				'thumbnail' => $this->get_thumbnail(),
			]
		);

		$this->options = $options;
	}

	/**
	 * Get options.
	 *
	 * @return bool|string
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Set info.
	 *
	 * @param string $slug Plugin slug.
	 */
	public function set_info( $slug ) {
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

		$this->info = $info;

		return true;
	}

	/**
	 * Get info.
	 *
	 * @return array
	 */
	public function get_info() {
		return $this->info;
	}

	/**
	 * Set thumbnail.
	 *
	 * @param string $slug Plugin slug.
	 */
	public function set_thumbnail( $slug ) {
		if ( ! isset( $slug ) ) {
			return false;
		}

		$success = false;

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

		// Delete previous thumbnails if they exist.
		$this->delete_thumbnails();

		// Needed to use download_url function.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		// Loop through all potential thumbnails and save the first one found.
		foreach ( $image_options as $image ) {
			$size            = $image['size'];
			$type            = $image['type'];
			$url             = "https://plugins.svn.wordpress.org/${slug}/assets/icon-${size}.${type}";
			$timeout_seconds = 5;

			// Download file to temp dir.
			$temp_file = download_url( $url, $timeout_seconds );

			if ( is_wp_error( $temp_file ) ) {
				wp_delete_file( $temp_file );
				continue;
			}

			$file = [
				'name'     => "__prefix_thumbnail.${type}",
				'type'     => "image/${type}",
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize( $temp_file ),
			];

			$overrides = [
				'test_form' => false,
			];

			// Upload new thumbnail.
			$results = wp_handle_sideload( $file, $overrides );

			if ( ! empty( $results['error'] ) ) {
				continue;
			} else {
				$this->thumbnail = $results['url'];

				$success = true;

				break;
			}
		}

		return $success;
	}

	/**
	 * Get thumbnail.
	 *
	 * @return string
	 */
	public function get_thumbnail() {
		return $this->thumbnail;
	}

	/**
	 * Delete thumbnails.
	 */
	public function delete_thumbnails() {
		$uploads_dir   = wp_upload_dir();
		$thumbnail_dir = trailingslashit( $uploads_dir['basedir'] ) . '__prefix';

		if ( file_exists( $thumbnail_dir ) ) {
			array_map( 'wp_delete_file', glob( $thumbnail_dir . '/*' ) );
		}
	}

}
