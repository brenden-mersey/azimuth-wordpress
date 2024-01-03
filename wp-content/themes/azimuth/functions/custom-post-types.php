<?php

$custom_post_types = new CustomPostTypes();
add_action( 'init', [ $custom_post_types, 'init' ] );

class CustomPostTypes {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'Custom Post Types';
  private $version = '2.0';

  public $enable = true;

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  public function post_type_settings( $name = '', $singular_name = '', $slug = '', $icon = '', $has_archive = true ) {

    return [
      'name' => $name,
      'singular_name' => $singular_name ,
      'slug' => $slug,
      'menu_icon' => $icon,
      'has_archive' => $has_archive,
    ];

  }

  public function init() {

    if ( $this->enable ) {

      $post_types = [
        $this->post_type_settings( 'Products', 'Product', 'product', 'dashicons-products', 'products' ),
      ];

      foreach ( $post_types as $i => $type ) {

        $icon = $type['menu_icon'];
        $name = $type['name'];
        $singular = $type['singular_name'];
        $slug = $type['slug'];
        $has_archive = $type['has_archive'];

        $labels = [
          'name'                => _x( $name, 'Post Type General Name', 'text_domain' ),
          'singular_name'       => _x( $singular, 'Post Type Singular Name', 'text_domain' ),
          'menu_name'           => __( $name, 'text_domain' ),
          'name_admin_bar'      => __( $singular, 'text_domain' ),
          'parent_item_colon'   => __( 'Parent ' . $singular . ':', 'text_domain' ),
          'all_items'           => __( 'All ' . $name, 'text_domain' ),
          'add_new_item'        => __( 'Add New ' . $singular, 'text_domain' ),
          'add_new'             => __( 'Add New', 'text_domain' ),
          'new_item'            => __( 'New ' . $singular, 'text_domain' ),
          'edit_item'           => __( 'Edit ' . $singular, 'text_domain' ),
          'update_item'         => __( 'Update ' . $singular, 'text_domain' ),
          'view_item'           => __( 'View ' . $name, 'text_domain' ),
          'search_items'        => __( 'Search ' . $name, 'text_domain' ),
          'not_found'           => __( $name . ' Not found', 'text_domain' ),
          'not_found_in_trash'  => __( $name . ' Not found in Trash', 'text_domain' ),
        ];

        $args = [
          'label'               => __( $singular, 'text_domain' ),
          'description'         => __( 'Posts for ' . $singular, 'text_domain' ),
          'labels'              => $labels,
          'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
          'taxonomies'          => array( 'category', 'post_tag' ),
          'hierarchical'        => true,
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'menu_position'       => 5,
          'show_in_admin_bar'   => true,
          'show_in_nav_menus'   => true,
          'can_export'          => true,
          'has_archive'         => $has_archive,
          'show_in_rest' 		    => true,
          'exclude_from_search' => false,
          'publicly_queryable'  => true,
          'capability_type'     => 'page',
          'delete_with_user'    => false,
          'menu_icon'           => $icon,
          'rewrite'             => array( 'slug' => $slug )
        ];

        register_post_type( $slug, $args );

      }

    }

  }

  /*
  //////////////////////////////////////////////////////////
  ////  Constructor
  //////////////////////////////////////////////////////////
  */

  public function __construct() {}

}
