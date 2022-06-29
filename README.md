# WP Custom CSS to Editor

![CI](https://github.com/inc2734/wp-google-fonts/workflows/CI/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/inc2734/wp-google-fonts/v/stable)](https://packagist.org/packages/inc2734/wp-google-fonts)
[![License](https://poser.pugx.org/inc2734/wp-google-fonts/license)](https://packagist.org/packages/inc2734/wp-google-fonts)

When a user with `customize` permission enqueues a font, the font is downloaded from the Google Fonts API. It then references the downloaded font.

## Install
```
$ composer require inc2734/wp-google-fonts
```

## How to use
```
<?php
new \Inc2734\WP_Google_Fonts\Bootstrap();

// Enqueue Noto Sans JP
add_action( 'wp_enqueue_scripts', [ '\Inc2734\WP_Google_Fonts\Helper', 'enqueue_noto_sans_jp' ], 5 );
add_action( 'enqueue_block_editor_assets', [ '\Inc2734\WP_Google_Fonts\Helper', 'enqueue_noto_sans_jp' ] );

// Noto Serif JP
add_action( 'wp_enqueue_scripts', [ '\Inc2734\WP_Google_Fonts\Helper', 'enqueue_noto_serif_jp' ], 5 );
add_action( 'enqueue_block_editor_assets', [ '\Inc2734\WP_Google_Fonts\Helper', 'enqueue_noto_serif_jp' ] );
```

## Filter hooks

### inc2734_wp_google_fonts_font_weight
```
/**
 * @param string $weight 400:700
 * @return string
 */
add_filter(
  'inc2734_wp_google_fonts_font_weight',
  function( $weight ) {
    return $weight;
  }
);
```

### inc2734_wp_google_fonts_base_directory
```
/**
 * @param string $directory WP_CONTENT_DIR
 * @return string
 */
add_filter(
  'inc2734_wp_google_fonts_base_directory',
  function( $directory ) {
    return $directory;
  }
);
```
