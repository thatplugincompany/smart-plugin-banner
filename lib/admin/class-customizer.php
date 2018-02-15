<?php
/**
 * Registers the customizer panels and handles save callback.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */

namespace ThatPluginCompany\PluginNamespace\Admin;

/**
 * Registers the customizer panels and handles save callback.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */
class Customizer {

	/**
	 * Validate the slug supplied by the user.
	 *
	 * @param WP_Error $validity Whether or not the slug is valid.
	 * @param string   $value Slug supplied by the user.
	 * @return mixed
	 */
	public function validate_slug( $validity, $value ) {
		if ( empty( $value ) ) {
			$validity->add( 'required', __( 'You must supply a valid slug.', '@@textdomain' ) );

			return $validity;
		}

		$api = new API();
		$api->set_options( $value );

		$options = $api->get_options();

		if ( empty( $options ) ) {
			$validity->add( 'plugin_not_found', __( 'Plugin not found in the WordPress.org plugin repository. Please make sure the slug supplied is correct.', '@@textdomain' ) );

			return $validity;
		}

		update_option( '__prefix_plugin_options', $options );

		return $validity;
	}

	/**
	 * Add Customizer options.
	 *
	 * @since 1.0.0
	 * @param WP_Customize_Manager $wp_customize Instance of the WordPress
	 * Customizer Manager class.
	 */
	public function add_customizer_options( $wp_customize ) {
		/**
		 * Add the main panel and options.
		 */

		$wp_customize->add_section( '__prefix', [
			'title'       => esc_html__( '@@name', '@@textdomain' ),
			'description' => esc_html__( 'Enter the slug of the plugin to be shown in the banner. It can be found in the URL of the plugin in the WordPress plugin repository. Example: \'login-designer\'', '@@textdomain' ),
			'priority'    => 120,
		] );

		$wp_customize->add_setting( '__prefix[plugin_slug]', [
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_title',
			'validate_callback' => [ $this, 'validate_slug' ],
		] );

		$wp_customize->add_control( '__prefix_plugin_slug', [
			'label'    => esc_html__( 'Plugin Slug', '@@textdomain' ),
			'section'  => '__prefix',
			'settings' => '__prefix[plugin_slug]',
		] );
	}

}
