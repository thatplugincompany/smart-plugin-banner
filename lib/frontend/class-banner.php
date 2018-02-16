<?php
/**
 * Render the banner.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */

namespace ThatPluginCompany\PluginNamespace\Frontend;

/**
 * Render the banner.
 *
 * @since   1.0.0
 * @package @@name
 * @author  @@author <@@author_email>
 */
class Banner {

	/**
	 * Check if banner should be shown.
	 *
	 * @return boolean
	 * @access private
	 */
	private function show_banner() {
		return apply_filters( '__prefix_show_banner', true );
	}

	/**
	 * Convert rating number into stars.
	 *
	 * @param int $rating Rating number from the API (1-100 scale).
	 * @return string
	 * @access private
	 */
	private function get_rating( $rating ) {
		if ( empty( $rating ) ) {
			return '';
		}

		$stars = '';

		/**
		 * Ratings from the API use a 1-100 scale. We add 5 points to the rating
		 * for full stars (for rounding purposes) and add a full star for every
		 * 20 points.
		 *
		 * We add a half star if the remainder is between 5 and 14. If the
		 * remainder is 15 or bigger, it means that the rounding of the full
		 * stars will have added the extra star already.
		 */
		$full = (int) ( ( $rating + 5 ) / 20 );
		$half = (int) $full < 5
			? ( $rating % 20 ) >= 5 && ( $rating % 20 ) < 15
			: 0;

		// Add full stars.
		$stars .= str_repeat( '<span class="smart-plugin-banner__star smart-plugin-banner__star--full"></span>', $full );

		// Add a half star if necessary.
		$stars .= ! empty( $half )
			? '<span class="smart-plugin-banner__star smart-plugin-banner__star--half"></span>'
			: '';

		// If there's less than 5 stars (full and half), add empty stars.
		$stars .= str_repeat( '<span class="smart-plugin-banner__star smart-plugin-banner__star--empty"></span>', 5 - ( $full + $half ) );

		return $stars;
	}

	/**
	 * Add body class when banner is visible.
	 *
	 * @param array $classes Default body classes.
	 * @return array
	 */
	public function add_body_class( $classes ) {
		if ( $this->show_banner() ) {
			$classes[] = 'has-smart-plugin-banner';

			// Add class if we're viewing the Customizer to always show the banner.
			if ( is_customize_preview() ) {
				$classes[] = 'has-smart-plugin-banner--customizer';
			}
		}

		return $classes;
	}

	/**
	 * Render banner HTML.
	 */
	public function render() {
		if ( ! $this->show_banner() ) {
			return false;
		}

		$options = get_option( '__prefix_plugin_options' );

		if ( empty( $options ) ) {
			return false;
		}

		// Set variables.
		$name   = ! empty( $options['name'] ) ? $options['name'] : '';
		$rating = ! empty( $options['rating'] ) ? $options['rating'] : '';
		$author = ! empty( $options['author'] ) ? $options['author'] : '';
		$url    = ! empty( $options['slug'] )
			? 'https://wordpress.org/plugins/' . $options['slug'] . '/'
			: '';

		// Set up thumbnail HTML.
		if ( ! empty( $options['thumbnail'] ) && ! empty( $options['name'] ) ) {
			$thumbnail = sprintf(
				'<img src="%1$s" alt="%2$s">',
				esc_url( $options['thumbnail'] ),
				esc_html( $options['name'] )
			);
		}

		// Build output HTML.
		$html = '<div id="smart-plugin-banner" class="smart-plugin-banner">';

		$html .= '<div class="smart-plugin-banner__inner">';

		if ( ! empty( $thumbnail ) ) {
			$html .= '<div class="smart-plugin-banner__thumbnail">';
			$html .= $thumbnail;
			$html .= '</div>';
		}

		$html .= '<div class="smart-plugin-banner__info">';

		$html .= '<span class="smart-plugin-banner__name">' . $name . '</span>';
		// Remove links from author name.
		$html .= '<span class="smart-plugin-banner__author">' . preg_replace( '#<a.*?>([^>]*)</a>#i', '$1', $author ) . '</span>';
		$html .= '<span class="smart-plugin-banner__rating">' . $this->get_rating( $rating ) . '</span>';

		$html .= '</div>';

		$html .= '<div class="smart-plugin-banner__actions">';
		$html .= '<a href="' . $url . '" class="smart-plugin-banner__button">' . __( 'View', '@@textdomain' ) . '</a>';
		$html .= '<button class="smart-plugin-banner__close" aria-label"' . __( 'Close', '@@textdomain' ) . '">&times;</button>';
		$html .= '</div>';

		$html .= '</div>';
		$html .= '</div>';

		// Output HTML.
		do_action( '__prefix_before_render' );

		echo wp_kses_post( $html );

		do_action( '__prefix_after_render' );
	}

}
