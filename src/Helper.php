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
		$weight = static::_font_weight();

		wp_enqueue_style(
			'wp-google-fonts',
			'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@' . $weight . '&display=swap',
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
		$weight = static::_font_weight();

		wp_enqueue_style(
			'wp-google-fonts',
			'https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@' . $weight . '&display=swap',
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
		$weight = static::_font_weight();

		wp_enqueue_style(
			'wp-google-fonts',
			'https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@' . $weight . '&display=swap',
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
		$weight = static::_font_weight();

		wp_enqueue_style(
			'wp-google-fonts',
			'https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@' . $weight . '&display=swap',
			[],
			1
		);
	}

	/**
	 * Enqueue BIZ UDPGothic
	 *
	 * @return void
	 */
	public static function enqueue_biz_udpgothic() {
		$weight = static::_font_weight();

		wp_enqueue_style(
			'wp-google-fonts',
			'https://fonts.googleapis.com/css2?family=BIZ+UDPGothic:wght@' . $weight . '&display=swap',
			[],
			1
		);
	}

	/**
	 * Enqueue BIZ UDPMincho
	 *
	 * @return void
	 */
	public static function enqueue_biz_udpmincho() {
		$weight = static::_font_weight();

		wp_enqueue_style(
			'wp-google-fonts',
			'https://fonts.googleapis.com/css2?family=BIZ+UDPMincho:wght@' . $weight . '&display=swap',
			[],
			1
		);
	}

	/**
	 * Return font weight string
	 *
	 * @return string
	 */
	protected static function _font_weight() {
		$weight = '400;700';
		return apply_filters( 'inc2734_wp_google_fonts_font_weight', $weight );
	}
}
