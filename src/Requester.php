<?php
/**
 * @package inc2734/wp-google-fonts
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Inc2734\WP_Google_Fonts;

class Requester {

	/**
	 * @var array
	 */
	protected $args = [];

	/**
	 * Constructor.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_remote_get/
	 *
	 * @param array $args Request arguments.
	 */
	public function __construct( $args = [] ) {
		global $wp_version;

		$user_agent   = 'WordPress/' . $wp_version;
		$default_args = [
			'user-agent' => $user_agent,
			'timeout'    => 30,
			'headers'    => [
				'Accept-Encoding' => '',
			],
		];

		$this->args = array_merge( $default_args, $args );
	}

	/**
	 * Request and get.
	 *
	 * @param string $uri  URL to retrieve.
	 * @return mixed
	 */
	public function get( $uri ) {
		$response = wp_remote_get(
			$uri,
			$this->args
		);

		if ( ! is_wp_error( $response ) ) {
			$response_code = wp_remote_retrieve_response_code( $response );
			if ( 200 === $response_code ) {
				return wp_remote_retrieve_body( $response );
			}
		}

		return false;
	}
}
