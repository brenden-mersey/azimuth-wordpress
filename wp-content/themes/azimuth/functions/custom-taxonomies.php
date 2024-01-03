<?php

$custom_taxonomies = new CustomTaxonomies();
add_action( 'init', [ $custom_taxonomies, 'init' ] );

class CustomTaxonomies {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'Custom Taxonomies';
  private $version = '1.0';

  public $enable = false;

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  public function settings( $name = '', $singular_name = '', $slug = '', $post_types = [], $rewrite = '' ) {

    return [
      'name' => $name,
      'singular_name' => $singular_name ,
      'slug' => $slug,
      'post_types' => $post_types,
      'rewrite' => $rewrite,
    ];

  }

  public function init() {

    if ( $this->enable ) {

      $taxonomies = [
        $this->settings( 'Location', 'Location', 'location', [ 'project' ] ),
        $this->settings( 'Property Types', 'Property Type', 'property-type', [ 'project' ] ),
      ];

      foreach ( $taxonomies as $i => $type ) {

        $name = $type['name'];
        $singular = $type['singular_name'];
        $slug = $type['slug'];
        $post_types = $type['post_types'];
        $rewrite = $type['rewrite'];

        $labels = [
		      'name'                       => _x( $name, 'Taxonomy General Name', 'text_domain' ),
		      'singular_name'              => _x( $singular, 'Taxonomy Singular Name', 'text_domain' ),
		      'menu_name'                  => __( $singular, 'text_domain' ),
		      'all_items'                  => __( 'All ' . $name, 'text_domain' ),
		      'parent_item'                => __( 'Parent ' . $singular, 'text_domain' ),
		      'parent_item_colon'          => __( 'Parent ' . $singular . ':', 'text_domain' ),
		      'new_item_name'              => __( 'New ' . $singular . ' Name', 'text_domain' ),
		      'add_new_item'               => __( 'Add New ' . $singular, 'text_domain' ),
		      'edit_item'                  => __( 'Edit ' . $singular, 'text_domain' ),
		      'update_item'                => __( 'Update ' . $singular, 'text_domain' ),
		      'view_item'                  => __( 'View Item', 'text_domain' ),
		      'separate_items_with_commas' => __( 'Separate ' . $name . ' with commas', 'text_domain' ),
		      'add_or_remove_items'        => __( 'Add or remove ' . $name, 'text_domain' ),
		      'choose_from_most_used'      => __( 'Choose from the most used ' . $name, 'text_domain' ),
		      'popular_items'              => __( 'Popular Items', 'text_domain' ),
		      'search_items'               => __( 'Search ' . $name, 'text_domain' ),
		      'not_found'                  => __( 'Not Found', 'text_domain' ),
		      'no_terms'                   => __( 'No items', 'text_domain' ),
		      'items_list'                 => __( 'Items list', 'text_domain' ),
		      'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	      ];

        $args = [
		      'labels'                     => $labels,
		      'hierarchical'               => true,
		      'public'                     => true,
		      'show_ui'                    => true,
		      'show_admin_column'          => true,
		      'show_in_nav_menus'          => true,
		      'show_tagcloud'              => true,
		      'rewrite'                    => [ 'slug' => $slug, 'with_front' => true, 'hierarchical' => false, ],
		      'show_in_rest'               => true,
	      ];

        if ( $rewrite ) {
          $args['rewrite'] = $rewrite;
        }

        register_taxonomy( $slug, $post_types, $args );

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
