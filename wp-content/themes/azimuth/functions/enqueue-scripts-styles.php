<?php

/*
//////////////////////////////////////////////////////////
////  Async & Defer Loading
//////////////////////////////////////////////////////////
*/

function add_asyncdefer_attribute( $tag, $handle ) {

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

add_filter( 'script_loader_tag', 'add_asyncdefer_attribute', 10, 2 );

/*
//////////////////////////////////////////////////////////
////  Enqueue Script & Style
//////////////////////////////////////////////////////////
*/

function dequeue_styles( $styles = [] ) {
  if ( $styles ) {
    foreach( $styles as $style ) {
      wp_dequeue_style( $style );
    }
  }
}

function deregister_scripts( $scripts = [] ) {
  if ( $scripts ) {
    foreach( $scripts as $script ) {
      wp_deregister_script( $script );
    }
  }
}

function register_scripts( $scripts = [] ) {
  if ( $scripts ) {
    foreach( $scripts as $id => $script ) {

      $handle = $id . '-' . ( $script['load'] ?? '' );
      $src = isset($script['src']) && !empty($script['src']) ? get_template_directory_uri() . '/assets/' . $script['src'] : '';
      $dependency = $script['dependency'] ?? [];
      $version = isset($script['src']) && !empty($script['src']) ? filemtime( get_template_directory() . '/assets/' . $script['src'] ) : '';
      $in_footer = $script['dependency'] ?? false;

      if ( $handle && $src ) {
        wp_register_script( $handle, $src, $dependency, $version, $in_footer );
      }

    }
  }
}

function equeue_scripts( $scripts = [] ) {
  if ( $scripts ) {
    foreach( $scripts as $id => $script ) {
      $handle = $id . '-' . ( $script['load'] ?? '' );
      if ( $handle ) {
        wp_enqueue_script( $handle );
      }
    }
  }
}

function scripts_styles_tidying() {

  // NOTE: 'in_footer' will be overruled if WordPress, the theme or a plugin needs file in header

  $scripts = [
    'main' => [
      'load' => 'defer',
      'dependency' => false,
      'in_footer' => false,
      'src' => 'main.min.js',
    ]
  ];

  if ( $GLOBALS['pagenow'] != 'wp-login.php' && !is_admin() ) {
    dequeue_styles( [ 'wp-block-library', 'wp-block-library-theme', 'wc-block-style', 'storefront-gutenberg-blocks' ] );
    deregister_scripts( [ 'jquery', 'comment-reply', 'wp-embed' ] );
    if ( $scripts ) {
      register_scripts( $scripts );
		  equeue_scripts( $scripts );
    }
  }

}

add_action( 'wp_enqueue_scripts', 'scripts_styles_tidying' );

/*
//////////////////////////////////////////////////////////
////  Conditionally Enqueue Script & Style
//////////////////////////////////////////////////////////
*/

function conditional_scripts_styles_tidying() {

  $scripts = [];

  if ( $scripts ) {
    register_scripts( $scripts );
    equeue_scripts( $scripts );
  }

}

add_action( 'init', 'conditional_scripts_styles_tidying' );
