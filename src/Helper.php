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
		$weight = get_theme_mod('load-font-weight') ?: '400,700';
		wp_enqueue_style(
			'noto-sans-jp',
			'https://fonts.googleapis.com/css?family=Noto+Sans+JP:'.$weight.'&display=swap&subset=japanese',
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
		$weight = get_theme_mod('load-font-weight') ?: '400,700';
		wp_enqueue_style(
			'noto-serif-jp',
			'https://fonts.googleapis.com/css?family=Noto+Serif+JP:'.$weight.'&display=swap&subset=japanese',
			[],
			1
		);
	}

	/**
	 * Enqueue M PLUS 1P
	 *
	 * @return void
	 */
	public static function enqueue_m_plus_1p() {
		$weight = get_theme_mod('load-font-weight') ?: '400,700';
		wp_enqueue_style(
			'm-plus-1p',
			'https://fonts.googleapis.com/css?family=M+PLUS+1p:'.$weight.'&display=swap&subset=japanese',
			[],
			1
		);
	}

	/**
	 * Enqueue M PLUS Rounded 1c
	 *
	 * @return void
	 */
	public static function enqueue_m_plus_rounded_1c() {
		$weight = get_theme_mod('load-font-weight') ?: '400,700';
		wp_enqueue_style(
			'm-plus-rounded-1c',
			'https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c:'.$weight.'&display=swap&subset=japanese',
			[],
			1
		);
	}
}
