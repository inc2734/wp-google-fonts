<?php
/**
 * Regression tests for local Google Fonts assets.
 *
 * @package inc2734/wp-google-fonts
 */

use Inc2734\WP_Google_Fonts\Filesystem;
use Inc2734\WP_Google_Fonts\Helper;
use Inc2734\WP_Google_Fonts\Requester;

class Google_Fonts_Test extends WP_UnitTestCase {

	protected $directory;
	protected $responses;
	protected $requests;
	protected $user_agent;
	protected $styles;

	public function set_up() {
		parent::set_up();

		$uploads          = wp_upload_dir();
		$this->directory  = $uploads['basedir'] . '/google-fonts-test-' . wp_generate_uuid4();
		$this->responses  = [];
		$this->requests   = [];
		$this->user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$this->styles = isset( $GLOBALS['wp_styles'] ) ? $GLOBALS['wp_styles'] : null;
		$GLOBALS['wp_styles']      = new WP_Styles();
		$_SERVER['HTTP_USER_AGENT'] = 'Google-Fonts-Regression-Test';

		wp_mkdir_p( $this->directory );
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'administrator' ] ) );
		add_filter( 'inc2734_wp_google_fonts_base_directory', [ $this, 'base_directory' ] );
		add_filter( 'pre_http_request', [ $this, 'http_response' ], 10, 3 );
	}

	public function tear_down() {
		remove_filter( 'pre_http_request', [ $this, 'http_response' ], 10 );
		remove_filter( 'inc2734_wp_google_fonts_base_directory', [ $this, 'base_directory' ] );
		remove_filter( 'inc2734_wp_google_fonts_font_weight', [ $this, 'font_weight' ] );
		remove_filter( 'inc2734_wp_google_fonts_refresh_font', '__return_true' );

		if ( is_dir( $this->directory ) ) {
			Filesystem::rmdir( $this->directory );
		}
		if ( null === $this->user_agent ) {
			unset( $_SERVER['HTTP_USER_AGENT'] );
		} else {
			$_SERVER['HTTP_USER_AGENT'] = $this->user_agent;
		}
		$GLOBALS['wp_styles'] = $this->styles;

		parent::tear_down();
	}

	public function base_directory() {
		return $this->directory;
	}

	public function font_weight() {
		return '300;500';
	}

	public function http_response( $response, $args, $url ) {
		$this->requests[] = [ 'url' => $url, 'args' => $args ];
		if ( ! isset( $this->responses[ $url ] ) ) {
			return new WP_Error( 'unexpected_request', $url );
		}
		return $this->responses[ $url ];
	}

	protected function response( $body, $status = 200 ) {
		return [
			'response' => [ 'code' => $status ],
			'body'     => $body,
		];
	}

	protected function mock_font( $family = 'Noto+Sans+JP', $weight = '400;700' ) {
		$url = 'https://fonts.googleapis.com/css2?family=' . $family . ':wght@' . $weight . '&display=swap';
		$this->responses[ $url ] = $this->response(
			'@font-face { font-family: Test; src: url(https://fonts.gstatic.com/s/test/v1/font.woff2) format("woff2"); }'
		);
		$this->responses['https://fonts.gstatic.com/s/test/v1/font.woff2'] = $this->response( 'test-font-data' );
		return $url;
	}

	public static function font_provider() {
		return [
			[ 'enqueue_noto_sans_jp', 'noto-sans-jp', 'Noto+Sans+JP' ],
			[ 'enqueue_noto_serif_jp', 'noto-serif-jp', 'Noto+Serif+JP' ],
			[ 'enqueue_m_plus_1p', 'm-plus-1p', 'M+PLUS+1p' ],
			[ 'enqueue_m_plus_rounded_1c', 'm-plus-rounded-1c', 'M+PLUS+Rounded+1c' ],
			[ 'enqueue_biz_udpgothic', 'biz-udpgothic', 'BIZ+UDPGothic' ],
			[ 'enqueue_biz_udpmincho', 'biz-udpmincho', 'BIZ+UDPMincho' ],
		];
	}

	/**
	 * @dataProvider font_provider
	 */
	public function test_downloads_fonts_and_enqueues_local_css( $method, $slug, $family ) {
		$url = $this->mock_font( $family );
		call_user_func( [ Helper::class, $method ] );

		$css_path = $this->directory . '/inc2734-wp-google-fonts/' . $slug . '-400700.css';
		$css_url  = str_replace( trailingslashit( ABSPATH ), trailingslashit( site_url() ), $css_path );
		$this->assertFileExists( $css_path );
		$this->assertStringNotContainsString( 'fonts.gstatic.com', file_get_contents( $css_path ) );
		$this->assertStringContainsString( '/inc2734-wp-google-fonts/test/v1/font.woff2', file_get_contents( $css_path ) );
		$this->assertSame( 'test-font-data', file_get_contents( dirname( $css_path ) . '/test/v1/font.woff2' ) );
		$this->assertTrue( wp_style_is( 'wp-google-fonts', 'enqueued' ) );
		$this->assertSame( $css_url, wp_styles()->registered['wp-google-fonts']->src );
		$this->assertCount( 2, $this->requests );
		$this->assertSame( $url, $this->requests[0]['url'] );
		$this->assertSame( 'Google-Fonts-Regression-Test', $this->requests[0]['args']['user-agent'] );

		ob_start();
		wp_print_styles( 'wp-google-fonts' );
		$output = ob_get_clean();
		$this->assertStringContainsString( esc_url( $css_url ), $output );
		$this->assertStringNotContainsString( 'fonts.googleapis.com', $output );
	}

	public function test_reuses_cached_fonts_for_visitors_without_downloading() {
		$this->mock_font();
		Helper::enqueue_noto_sans_jp();
		$css_url = wp_styles()->registered['wp-google-fonts']->src;
		$this->requests = [];
		wp_set_current_user( 0 );
		wp_dequeue_style( 'wp-google-fonts' );
		wp_deregister_style( 'wp-google-fonts' );

		Helper::enqueue_noto_sans_jp();

		$this->assertSame( [], $this->requests );
		$this->assertTrue( wp_style_is( 'wp-google-fonts', 'enqueued' ) );
		$this->assertSame( $css_url, wp_styles()->registered['wp-google-fonts']->src );
	}

	public function test_does_not_download_fonts_without_customize_permission() {
		$this->mock_font();
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'subscriber' ] ) );

		Helper::enqueue_noto_sans_jp();

		$this->assertSame( [], $this->requests );
		$this->assertFalse( wp_style_is( 'wp-google-fonts', 'enqueued' ) );
		$this->assertDirectoryDoesNotExist( $this->directory . '/inc2734-wp-google-fonts' );
	}

	public function test_applies_the_font_weight_filter() {
		add_filter( 'inc2734_wp_google_fonts_font_weight', [ $this, 'font_weight' ] );
		$url = $this->mock_font( 'Noto+Sans+JP', '300;500' );

		Helper::enqueue_noto_sans_jp();

		$this->assertSame( $url, $this->requests[0]['url'] );
		$this->assertFileExists( $this->directory . '/inc2734-wp-google-fonts/noto-sans-jp-300500.css' );
	}

	public function test_refreshes_css_and_reuses_the_existing_font_file() {
		$this->mock_font();
		Helper::enqueue_noto_sans_jp();
		$this->requests = [];
		add_filter( 'inc2734_wp_google_fonts_refresh_font', '__return_true' );

		Helper::enqueue_noto_sans_jp();

		$this->assertCount( 1, $this->requests );
		$this->assertStringContainsString( 'fonts.googleapis.com', $this->requests[0]['url'] );
	}

	public function test_does_not_download_without_a_user_agent() {
		$this->mock_font();
		unset( $_SERVER['HTTP_USER_AGENT'] );

		Helper::enqueue_noto_sans_jp();

		$this->assertSame( [], $this->requests );
		$this->assertFalse( wp_style_is( 'wp-google-fonts', 'enqueued' ) );
	}

	public function test_does_not_enqueue_css_after_an_http_failure() {
		$url = $this->mock_font();
		$this->responses[ $url ] = $this->response( 'Unavailable', 503 );

		Helper::enqueue_noto_sans_jp();

		$this->assertCount( 1, $this->requests );
		$this->assertFalse( wp_style_is( 'wp-google-fonts', 'enqueued' ) );
		$this->assertFileDoesNotExist( $this->directory . '/inc2734-wp-google-fonts/noto-sans-jp-400700.css' );
	}

	public function test_requester_returns_the_response_body_and_handles_errors() {
		$url = 'https://fonts.googleapis.com/test';
		$requester = new Requester( [ 'timeout' => 5 ] );
		$this->responses[ $url ] = $this->response( 'response-body' );

		$this->assertSame( 'response-body', $requester->get( $url ) );
		$this->assertSame( 5, $this->requests[0]['args']['timeout'] );
		$this->assertSame( '', $this->requests[0]['args']['headers']['Accept-Encoding'] );

		$this->responses[ $url ] = $this->response( 'Not found', 404 );
		$this->assertFalse( $requester->get( $url ) );
		$this->responses[ $url ] = new WP_Error( 'http_request_failed', 'Connection failed' );
		$this->assertFalse( $requester->get( $url ) );
	}

	public function test_filesystem_restores_the_previous_wordpress_filesystem() {
		global $wp_filesystem;
		$previous = new stdClass();
		$original = $wp_filesystem;
		$wp_filesystem = $previous;
		$path = $this->directory . '/nested/file.txt';

		try {
			$this->assertTrue( Filesystem::mkdir( dirname( $path ) ) );
			$this->assertTrue( Filesystem::put_contents( $path, 'stored-content' ) );
			$this->assertSame( 'stored-content', Filesystem::get_contents( $path ) );
			$this->assertSame( $previous, $wp_filesystem );
			$this->assertTrue( Filesystem::rmdir( dirname( $path ) ) );
			$this->assertFalse( Filesystem::get_contents( $path ) );
		} finally {
			$wp_filesystem = $original;
		}
	}
}
