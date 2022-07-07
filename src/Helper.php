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
	 */
	public static function enqueue_noto_sans_jp() {
		$weight  = static::_font_weight();
		$css_url = static::_download(
			[
				'slug'        => 'noto-sans-jp-' . $weight,
				'request_uri' => sprintf(
					'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@%1$s&display=swap',
					$weight
				),
			]
		);

		if ( $css_url ) {
			wp_enqueue_style(
				'wp-google-fonts',
				$css_url,
				[],
				1
			);
		}
	}

	/**
	 * Enqueue Noto Serif JP
	 */
	public static function enqueue_noto_serif_jp() {
		$weight  = static::_font_weight();
		$css_url = static::_download(
			[
				'slug'        => 'noto-serif-jp-' . $weight,
				'request_uri' => sprintf(
					'https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@%1$s&display=swap',
					$weight
				),
			]
		);

		if ( $css_url ) {
			wp_enqueue_style(
				'wp-google-fonts',
				$css_url,
				[],
				1
			);
		}
	}

	/**
	 * Enqueue M PLUS 1P
	 */
	public static function enqueue_m_plus_1p() {
		$weight  = static::_font_weight();
		$css_url = static::_download(
			[
				'slug'        => 'm-plus-1p-' . $weight,
				'request_uri' => sprintf(
					'https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@%1$s&display=swap',
					$weight
				),
			]
		);

		if ( $css_url ) {
			wp_enqueue_style(
				'wp-google-fonts',
				$css_url,
				[],
				1
			);
		}
	}

	/**
	 * Enqueue M PLUS Rounded 1c
	 */
	public static function enqueue_m_plus_rounded_1c() {
		$weight  = static::_font_weight();
		$css_url = static::_download(
			[
				'slug'        => 'm-plus-rounded-1c-' . $weight,
				'request_uri' => sprintf(
					'https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@%1$s&display=swap',
					$weight
				),
			]
		);

		if ( $css_url ) {
			wp_enqueue_style(
				'wp-google-fonts',
				$css_url,
				[],
				1
			);
		}
	}

	/**
	 * Enqueue BIZ UDPGothic
	 */
	public static function enqueue_biz_udpgothic() {
		$weight  = static::_font_weight();
		$css_url = static::_download(
			[
				'slug'        => 'biz-udpgothic-' . $weight,
				'request_uri' => sprintf(
					'https://fonts.googleapis.com/css2?family=BIZ+UDPGothic:wght@%1$s&display=swap',
					$weight
				),
			]
		);

		if ( $css_url ) {
			wp_enqueue_style(
				'wp-google-fonts',
				$css_url,
				[],
				1
			);
		}
	}

	/**
	 * Enqueue BIZ UDPMincho
	 */
	public static function enqueue_biz_udpmincho() {
		$weight  = static::_font_weight();
		$css_url = static::_download(
			[
				'slug'        => 'biz-udpmincho-' . $weight,
				'request_uri' => sprintf(
					'https://fonts.googleapis.com/css2?family=BIZ+UDPMincho:wght@%1$s&display=swap',
					$weight
				),
			]
		);

		if ( $css_url ) {
			wp_enqueue_style(
				'wp-google-fonts',
				$css_url,
				[],
				1
			);
		}
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

	/**
	 * Download fron Google Fonts API.
	 *
	 * @param array $args Array.
	 * @return false|string
	 */
	protected static function _download( $args = [] ) {
		$args = shortcode_atts(
			[
				'slug'        => null,
				'request_uri' => null,
			],
			$args
		);

		if ( ! $args['slug'] || ! $args['request_uri'] ) {
			return false;
		}

		$wp_upload_dir = wp_upload_dir();

		$base_dir = apply_filters( 'inc2734_wp_google_fonts_base_directory', $wp_upload_dir['basedir'] );
		if ( ! is_writable( $base_dir ) ) {
			return false;
		}

		$base_dir      = path_join( $base_dir, 'inc2734-wp-google-fonts' );
		$css_filename  = sanitize_file_name( $args['slug'] . '.css' );
		$css_full_path = path_join( $base_dir, $css_filename );
		$css_url       = str_replace( trailingslashit( ABSPATH ), trailingslashit( site_url() ), $css_full_path );

		if ( ! current_user_can( 'customize' ) ) {
			return false;
		}

		if ( file_exists( $css_full_path ) ) {
			$refresh_font = apply_filters( 'inc2734_wp_google_fonts_refresh_font', false, $css_full_path );
			if ( ! $refresh_font ) {
				return $css_url;
			}
		}

		$user_agent = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : false;
		if ( ! $user_agent ) {
			return false;
		}

		$requester = new Requester(
			[
				'user-agent' => $user_agent,
			]
		);

		$data = $requester->get( $args['request_uri'] );
		if ( ! $data ) {
			return false;
		}

		$is_created = Filesystem::mkdir( $base_dir );
		if ( ! $is_created ) {
			return false;
		}

		$own_directory = ltrim( str_replace( home_url(), '', site_url() ), '/' );
		preg_match( '|^https?://[^/]+?(/.*)$|', home_url(), $match );
		$sub_directory = $match ? ltrim( $match[1], '/' ) : '';
		$abspath       = str_replace(
			untrailingslashit( ABSPATH ),
			'',
			$base_dir
		);
		$abspath       = path_join( $own_directory, ltrim( $abspath, '/' ) );
		$abspath       = path_join( $sub_directory, ltrim( $abspath, '/' ) );
		$abspath       = path_join( '/', $abspath );
		$new_data      = str_replace(
			'https://fonts.gstatic.com/s',
			$abspath,
			$data
		);
		if ( $new_data === $data ) {
			return false;
		}

		$is_created = Filesystem::put_contents( $css_full_path, $new_data );
		if ( ! $is_created ) {
			return false;
		}

		preg_match_all( '|src: url\(([^)]+?)\)|ms', $data, $match, PREG_SET_ORDER );
		foreach ( $match as $m ) {
			if ( empty( $m[1] ) ) {
				continue;
			}

			$font_uri           = $m[1];
			$font_relative_path = str_replace( 'https://fonts.gstatic.com/s/', '', $font_uri );
			$directory          = path_join( $base_dir, dirname( $font_relative_path ) );
			$font_full_path     = path_join( $directory, basename( $font_relative_path ) );

			if ( file_exists( $font_full_path ) ) {
				continue;
			}

			$is_created = Filesystem::mkdir( $directory );
			if ( ! $is_created ) {
				continue;
			}

			$data       = $requester->get( $font_uri );
			$is_created = Filesystem::put_contents( $font_full_path, $data );
			if ( ! $is_created ) {
				continue;
			}
		}

		return $css_url;
	}
}
