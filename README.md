# WP Custom CSS to Editor

![CI](https://github.com/inc2734/wp-google-fonts/workflows/CI/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/inc2734/wp-google-fonts/v/stable)](https://packagist.org/packages/inc2734/wp-google-fonts)
[![License](https://poser.pugx.org/inc2734/wp-google-fonts/license)](https://packagist.org/packages/inc2734/wp-google-fonts)

When a user with `customize` permission enqueues a font, the font is downloaded from the Google Fonts API. It then references the downloaded font.
Fonts files are stored in `wp-content/uploads/inc2734-wp-google-fonts`.

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

### inc2734_wp_google_fonts_refresh_font
```
/**
 * @param boolean $refresh true if you want to force a font file refresh.
 * @param string  $css_full_path Full path of CSS file.
 * @return boolean
 */
add_filter(
  'inc2734_wp_google_fonts_refresh_font',
  function( $refresh, $css_full_path ) {
    return $refresh;
  },
  10,
  2
);
```

## Third-party resources

### Noto Sans Japanese
* Font License: SIL Open Font License (OFL)
* Font License URI: https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL
* Source: https://fonts.google.com/noto/specimen/Noto+Sans+JP

### Noto Serif Japanese
* Font License: SIL Open Font License (OFL)
* Font License URI: https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL
* Source: https://fonts.google.com/noto/specimen/Noto+Serif+JP

### M PLUS 1p
* Font License: SIL Open Font License (OFL)
* Font License URI: https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL
* Source: https://fonts.google.com/specimen/M+PLUS+1p

### M PLUS Rounded 1c
* Font License: SIL Open Font License (OFL)
* Font License URI: https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL
* Source: https://fonts.google.com/specimen/M+PLUS+Rounded+1c

### BIZ UDPGothic
* Font License: SIL Open Font License (OFL)
* Font License URI: https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL
* Source: https://fonts.google.com/specimen/BIZ+UDPGothic

### BIZ UDPMincho
* Font License: SIL Open Font License (OFL)
* Font License URI: https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL
* Source: https://fonts.google.com/specimen/BIZ+UDPMincho
