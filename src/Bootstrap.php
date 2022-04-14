<?php
/**
 * @package inc2734/wp-google-fonts
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Inc2734\WP_Google_Fonts;

class Bootstrap {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'clean_url', [ $this, '_clean_url' ] );
		add_filter( 'wp_resource_hints', [ $this, '_wp_resource_hints' ], 10, 2 );
	}

	/**
	 * Filters a string cleaned and escaped for output as a URL.
	 *
	 * @param string $url The cleaned URL to be returned.
	 * @return string
	 */
	public function _clean_url( $url ) {
		if ( false !== strstr( $url, 'fonts.googleapis.com' ) ) {
			$url = str_replace( '&#038;', '&', $url );
		}
		return $url;
	}

	/**
	 * Filters domains and URLs for resource hints of relation type.
	 *
	 * @param array  $urls Array of resources and their attributes, or URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for, e.g. 'preconnect' or 'prerender'.
	 * @return array
	 */
	public function _wp_resource_hints( $urls, $relation_type ) {
		if ( wp_style_is( 'wp-google-fonts' ) ) {
			if ( 'preconnect' === $relation_type ) {
				$urls[] = 'https://fonts.googleapis.com';
				$urls[] = [
					'href'        => 'https://fonts.gstatic.com',
					'crossorigin' => '',
				];
			}
		}
		return $urls;
	}
}
