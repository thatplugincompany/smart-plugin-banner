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

		$wp_customize->add_section( '@@prefix', [
			'title'       => esc_html__( '@@name', '@@textdomain' ),
			'description' => esc_html__( 'Enter the slug of the plugin to be shown in the banner. It can be found in the URL of the plugin in the WordPress plugin repository. Example: \'login-designer\'', '@@textdomain' ),
			'priority'    => 120,
		] );

		$wp_customize->add_setting( '@@prefix[plugin_slug]', [
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_title',
		] );

		$wp_customize->add_control( '@@prefix_plugin_slug', [
			'label'    => esc_html__( 'Plugin Slug', '@@textdomain' ),
			'section'  => '@@prefix',
			'settings' => '@@prefix[plugin_slug]',
		] );
	}

}
