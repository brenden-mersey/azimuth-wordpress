<?php

$acf_options = new ACFOptions();
add_action( 'acf/init', [ $acf_options, 'init' ] );
add_filter( 'allowed_block_types_all', [ $acf_options, 'allowed_blocks' ], 10, 2 );

class ACFOptions {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'VP ACF Options';
  private $version = '2.0';

  public $gutenberg_blocks = [
    [ 'title' => 'Community','name' => 'community' ],
    [ 'title' => 'Feature Image', 'name' => 'feature-image' ],
    [ 'title' => 'Hero', 'name' => 'hero' ],
    [ 'title' => 'Instagram Feed', 'name' => 'instagram-feed' ],
    [ 'title' => 'Products', 'name' => 'products' ],
    [ 'title' => 'Stockists', 'name' => 'stockists' ],
    [ 'title' => 'Team', 'name' => 'team' ],
    [ 'title' => 'Text', 'name' => 'text' ],
  ];

  public $theme_settings = [
    'options' => [
      '404',
      'Company Info',
      'Header',
      'Footer',
    ],
    'slug' => 'theme-settings',
  ];

  public $current_block = '';

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  public function setBlock( $value = '' ) {
    $this->current_block = $value;
  }
  public function getBlock() {
    return $this->current_block;
  }

  // ---------------------------------------- Register Block Types
  public function register_block_types() {

    if ( $this->gutenberg_blocks ) {
      foreach( $this->gutenberg_blocks as $block ) {
        acf_register_block_type( $this->block_settings( $block ) );
      }
    }

  }

  // ---------------------------------------- Register Options
  public function register_main_options() {

    acf_add_options_page(
  		array(
  			'page_title' => 'Theme Settings',
  			'menu_title' => 'Theme Settings',
  			'menu_slug' => $this->theme_settings['slug'],
  			'capability' => 'edit_posts',
  			'parent_slug' => '',
  			'position' => '2.1',
  			'icon_url' => false
  		)
  	);

  }

  // ---------------------------------------- Register Sub Options
  public function register_sub_options() {

    $main_slug = $this->theme_settings['slug'];

    foreach ( $this->theme_settings['options'] as $title ) {

      $title_slug = Tools::handleize( $title );

      $args = [
        'capability' => 'edit_posts',
        'menu_slug' => $main_slug . '-' . $title_slug,
  			'menu_title' => $title,
        'page_title' => $title,
  			'parent_slug' => $main_slug,
      ];

      acf_add_options_sub_page( $args );

    }

  }

  // ---------------------------------------- Allowed Gutenberg Blocks
  public function allowed_blocks( $allowed_block_types, $editor_context ) {

    if ( ! empty( $editor_context->post ) ) {

      // Tools::debug_this( $editor_context );

      $allowed_blocks = [];

      if ( $this->gutenberg_blocks ) {
        foreach( $this->gutenberg_blocks as $block ) {
          $allowed_blocks[] = 'acf/' . $block['name'];
        }
      }

      // Disables the core paragraph block in lieu for custom blocks
      // $allowed_blocks[] = 'core/paragraph';

      return $allowed_blocks;

//       if ( 'post' === $editor_context->post->post_type ) {
//
//         return array(
//           'core/paragraph',
//         );
//
//       } else {
//
//         $allowed_blocks = [];
//
//         if ( $this->gutenberg_blocks ) {
//           foreach( $this->gutenberg_blocks as $block ) {
//             $allowed_blocks[] = 'acf/' . $block['name'];
//           }
//         }
//
//         $allowed_blocks[] = 'core/paragraph';
//
//         return $allowed_blocks;
//
//       }

    }

    return $allowed_block_types;

  }

  // ---------------------------------------- Block Settings
  public function block_settings( $block = [] ) {

    // ---------------------------------------- Defaults
    extract(array_merge(
      [
        'category' => 'common',
        'description' => '',
        'icon' => 'admin-comments',
        'keywords' => [],
        'name' => '',
        'post_types' => [ 'page', 'post' ],
        'title' => '',
      ],
      $block
    ));

    return [
      'name'              => $name,
      'title'             => __( $title ),
      'description'       => __( $description ),
      'render_template'   => $this->get_block_render_template( $name ),
      'category'          => $category,
      'icon'              => $icon,
      'keywords'          => $keywords,
      'post_types'        => $post_types,
      'mode'              => 'edit',
      // 'enqueue_assets'    => $this->enqueue_block_styles( $name ), this works, but loads all css files
      'enqueue_style'     => $this->get_block_stylesheet( $name ), // works, but appends custom version at end of file
    ];

  }

  // ---------------------------------------- Enqueue Block Styles
  public function enqueue_block_styles( $name = '' ) {

    $id = 'block-' . $name;
    $file = $id . '.css';
    $file_directory = get_template_directory() . '/assets/' . $file;
    $file_exists = file_exists( $file_directory ) ?: false;

    if ( $file_exists ) {
      $file_directory_uri = get_template_directory_uri() . '/assets/' . $file;
      $file_date_modified = filemtime( $file_directory );
      wp_enqueue_style( $id, $file_directory_uri, [], $file_date_modified, 'all' );
    }

  }

  // ---------------------------------------- Get Block Render Template
  public function get_block_render_template( $name = '' ) {
    return $name ? '/snippets/blocks/' . $name . '.php' : '';
  }

  // ---------------------------------------- Get Block Stylesheet
  public function get_block_stylesheet( $name = '' ) {

    if ( $name ) {

      $id = 'block-' . $name;
      $file = $id . '.css';
      $file_directory = get_template_directory() . '/assets/' . $file;
      $file_exists = file_exists( $file_directory ) ?: false;

      if ( $file_exists ) {
        $file_directory_uri = get_template_directory_uri() . '/assets/' . $file;
        $file_date_modified = filemtime( $file_directory );
        return $file_directory_uri . '?v=' . $file_date_modified;
      }

    }

    return '';

  }

  // ---------------------------------------- Init
  public function init() {

    if ( function_exists('acf_register_block_type') ) {
      $this->register_block_types();
    }

    if ( function_exists('acf_add_options_page') ) {
      $this->register_main_options();
      $this->register_sub_options();
    }

  }

  /*
  //////////////////////////////////////////////////////////
  ////  Constructor
  //////////////////////////////////////////////////////////
  */

  public function __construct() {}

}
