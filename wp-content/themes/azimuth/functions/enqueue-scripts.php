<?php

$theme_scripts = new ThemeScripts();
add_action( 'wp_enqueue_scripts', [ $theme_scripts, 'init' ] );
add_action( 'script_loader_tag', [ $theme_scripts, 'add_async_defer_attrs' ], 10, 2 );

class ThemeScripts {

  private $name = 'Theme Scripts';
  private $version = '2.0';

  public $enable = true;
  public $scripts = [
    'main' => [
      'external' => false,
      'deps' => [],
      'in_footer' => true,
      'load' => 'defer',
      'path' => '/assets/main.min.js',
    ]
  ];
  public $unused_scripts = [ 'jquery', 'comment-reply', 'wp-embed' ];

  public function dequeue_scripts( $scripts = [] ) {
    if ( !empty( $scripts ) ) {
      foreach( $scripts as $script ) {
        wp_dequeue_script( $script );
      }
    }
  }

  public function deregister_scripts( $scripts = [] ) {
    if ( !empty( $scripts ) ) {
      foreach( $scripts as $script ) {
        wp_deregister_script( $script );
      }
    }
  }

  public function enqueue_scripts( $scripts = [] ) {
    if ( !empty( $scripts ) ) {
      foreach( $scripts as $id => $script ) {

        $handle = $id . '-' . $script['load'];

        wp_enqueue_script( $handle );

        if ( 'main' === $id ) {
          wp_localize_script( $handle, 'wp_data', [
            'root' => esc_url_raw(get_bloginfo('url')),
            'rootapiurl' => esc_url_raw(rest_url())
          ]);
        }

      }
    }
  }

  public function register_scripts( $scripts = [] ) {
    if ( !empty( $scripts ) ) {
      foreach( $scripts as $id => $script ) {

        $handle = $id . '-' . $script['load'];
        $path = $script['external'] ? $script['path'] : get_template_directory_uri() . $script['path'];
        $deps = $script['deps'] ?? [];
        $ver = $script['external'] ? false : filemtime( get_stylesheet_directory() . $script['path'] );
        $in_footer = $script['in_footer'] ?? false;

        wp_register_script( $handle, $path, $deps, $ver, $in_footer );

      }
    }
  }

  // ---------------------------------------- Initialize
  public function add_async_defer_attrs( $tag, $handle ) {

    // if the unique handle/name of the registered script has 'async' in it
    if ( strpos($handle, 'async') !== false ) {
      // return the tag with the async attribute
      return str_replace( '<script ', '<script async ', $tag );
    }
    // if the unique handle/name of the registered script has 'defer' in it
    else if ( strpos($handle, 'defer') !== false ) {
      // return the tag with the defer attribute
      return str_replace( '<script ', '<script defer ', $tag );
    }
    // otherwise skip
    else {
      return $tag;
    }

  }

  // ---------------------------------------- Initialize
  public function init() {
    if ( $this->enable && ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) ) {
      $this->dequeue_scripts( $this->unused_scripts );
      $this->deregister_scripts( $this->unused_scripts );
      $this->register_scripts( $this->scripts );
		  $this->enqueue_scripts( $this->scripts );
    }
  }

}
