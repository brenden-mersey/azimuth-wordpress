<?php

/*
//////////////////////////////////////////////////////////
////  Query String Filter
//////////////////////////////////////////////////////////
*/

$GLOBALS['query_string_keys'] = [
  'location',
	'property-type',
  'category',
];

function projects_pre_get_posts( $query ) {

  $tax_query = [];
  $order = 'DESC';

  // bail early if is in admin
  // OR if not main query
	if ( !is_admin() && $query->is_main_query() ) {

    if ( $query->get('post_type') === 'project' ) {

      // Tools::debug_this( 'projects_pre_get_posts running' );

	    foreach( $GLOBALS['query_string_keys'] as $filter ) {

		    // continue if not found in url
		    if ( empty($_GET[$filter]) ) {
			    continue;
		    }

		    $filter_values = explode( ',', $_GET[$filter] );

        array_push( $tax_query, [
          'taxonomy' => $filter,
          'field'    => 'slug',    // term_id to use ids
          'terms'    => $filter_values,
          'operator' => 'IN',
        ]);

	    }

      if ( count($tax_query) > 1 ) {
        $tax_query['relation'] = 'AND';
      }

      if ( !empty($_GET['order']) ) {
			  $order = match ( $_GET['order'] ) {
          'oldest-first' => 'ASC',
          'newest-first' => 'DESC',
        };
		  }

      $query->set( 'order', $order );
      $query->set( 'orderby', 'date' );
      $query->set( 'posts_per_page', 9 );

      if ( $tax_query ) {
        $query->set( 'tax_query', $tax_query );
        // $query->set( 'posts_per_page', 50 );
      }

    }

    if ( $query->is_category() || $query->is_tax() ) {
      $query->set( 'post_type', [ 'project' ] );
      $query->set( 'order', $order );
      $query->set( 'orderby', 'date' );
      $query->set( 'posts_per_page', 9 );
    }

  }

}

add_action( 'pre_get_posts', 'projects_pre_get_posts' );
