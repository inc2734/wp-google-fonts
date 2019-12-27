<?php
/**
 * @package inc2734/wp-google-fonts
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Inc2734\WP_Google_Fonts;

class Helper {

	/**
	 * Enqueue Noto Sans JP
	 *
	 * @return void
	 */
	public static function enqueue_noto_sans_jp() {
		wp_enqueue_style(
			'noto-sans-jp',
			'https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap&subset=japanese',
			[],
			1
		);
	}

	/**
	 * Enqueue Noto Serif JP
	 *
	 * @return void
	 */
	public static function enqueue_noto_serif_jp() {
		wp_enqueue_style(
			'noto-serif-jp',
			'https://fonts.googleapis.com/css?family=Noto+Serif+JP&display=swap&subset=japanese',
			[],
			1
		);
	}
}
