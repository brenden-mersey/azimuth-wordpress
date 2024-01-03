<?php

$acf_options = new ACFOptions();
add_action( 'acf/init', [ $acf_options, 'init' ] );

class ACFOptions {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'VP ACF Options';
  private $version = '2.0';

  public $main_slug = 'theme-settings';
  public $render_template_base = '/snippets/blocks/';
  public $titles = [ 'Company Info', 'Header', '404' ];

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  // ---------------------------------------- Register Options
  public function register_main_options() {

    acf_add_options_page(
  		array(
  			'page_title' => 'Theme Settings',
  			'menu_title' => 'Theme Settings',
  			'menu_slug' => $this->main_slug,
  			'capability' => 'edit_posts',
  			'parent_slug' => '',
  			'position' => '2.1',
  			'icon_url' => false
  		)
  	);

  }

  // ---------------------------------------- Register Sub Options
  public function register_sub_options() {

    $main_slug = $this->main_slug;

    foreach ( $this->titles as $title ) {

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

  // ---------------------------------------- Block Settings
  public function block_settings( $title = '', $desc = '', $template = '', $cat = 'common', $keywords = [], $post_types = [] ) {

    $name = Tools::handleize( $title );

    return [
      'name'              => $name,
      'title'             => __( $title ),
      'description'       => __( $desc ),
      'render_template'   => $this->render_template_base . $template,
      'category'          => $cat,
      'icon'              => 'admin-comments',
      'keywords'          => $keywords,
      'post_types'        => $post_types,
      'mode'              => 'edit',
      'enqueue_assets' => function(){
        // wp_enqueue_style( 'parent-main', get_template_directory_uri() .'/assets/main.css' );
        // if ( is_child_theme() ) {
        //   wp_enqueue_style( 'child-main', get_stylesheet_directory_uri() .'/assets/main-child.css', [ 'parent-main' ] );
        // }
      },
    ];

  }

  // ---------------------------------------- Register Block Types
  public function register_block_types() {

    $block_types = [
      $this->block_settings( 'Community', '', 'community.php', 'common', [ 'text' ], [ 'post', 'page'] ),
      $this->block_settings( 'Hero', '', 'hero.php', 'common', [ 'text' ], [ 'post', 'page'] ),
      $this->block_settings( 'Image Feature', '', 'image-feature.php', 'common', [ 'text' ], [ 'post', 'page'] ),
      $this->block_settings( 'Instagram Feed', '', 'instagram-feed.php', 'common', [ 'text' ], [ 'post', 'page'] ),
      $this->block_settings( 'Products', '', 'products.php', 'common', [ 'text' ], [ 'post', 'page'] ),
      $this->block_settings( 'Stockists', '', 'stockists.php', 'common', [ 'text' ], [ 'post', 'page'] ),
      $this->block_settings( 'Team', '', 'team.php', 'common', [ 'text' ], [ 'post', 'page'] ),
      $this->block_settings( 'Text', '', 'text.php', 'common', [ 'text' ], [ 'post', 'page'] ),
    ];

    foreach ( $block_types as $block ) {
      acf_register_block_type( $block );
    }

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
