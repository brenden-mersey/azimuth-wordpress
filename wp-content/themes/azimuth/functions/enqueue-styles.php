<?php

$theme_styles = new ThemeStyles();
add_action( 'wp_enqueue_scripts', [ $theme_styles, 'init' ] );

class ThemeStyles {

  private $name = 'Theme Styles';
  private $version = '2.0';

  public $enable = true;
  public $styles = [
    'main' => [
      'deps' => [],
      'external' => false,
      'media' => 'all',
      'path' => '/style.css',
    ]
  ];
  public $unused_styles = [
    'wp-block-library',
    'wp-block-library-theme',
    'wc-block-style',
    'storefront-gutenberg-blocks'
  ];

  public function dequeue_styles( $styles = [] ) {
    if ( !empty( $styles ) ) {
      foreach( $styles as $style ) {
        wp_dequeue_style( $style );
      }
    }
  }

  public function deregister_styles( $styles = [] ) {
    if ( !empty( $styles ) ) {
      foreach( $styles as $style ) {
        wp_deregister_style( $style );
      }
    }
  }

  public function enqueue_styles( $styles = [] ) {
    if ( !empty( $styles ) ) {
      foreach ( $styles as $id => $style ) {
        switch ( $id ) {
          case 'longevity-club-fonts': {
            if ( is_singular([ 'post' ]) || is_page_template( 'page-templates/page--longevity-club.php' ) ) {
              wp_enqueue_style( $id );
            }
            break;
          }
          default: {
            wp_enqueue_style( $id );
            break;
          }
        }
      }
    }
  }

  public function register_styles( $styles = [] ) {
    if ( !empty( $styles ) ) {
      foreach( $styles as $id => $style ) {

        $handle = $id;
        $path = $style['external'] ? $style['path'] : get_template_directory_uri() . $style['path'];
        $deps = $style['deps'] ?? [];
        $ver = $style['external'] ? false : filemtime( get_stylesheet_directory() . $style['path'] );
        $media = $style['media'] ?? 'all';

        wp_register_style( $handle, $path, $deps, $ver, $media );

      }
    }
  }

  // ---------------------------------------- Initialize
  public function init() {
    if ( $this->enable && ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) ) {
      $this->dequeue_styles( $this->unused_styles );
      $this->deregister_styles( $this->unused_styles );
      $this->register_styles( $this->styles );
		  $this->enqueue_styles( $this->styles );
    }
  }

}
