<?php

/*
//////////////////////////////////////////////////////////
//// Get Blog Posts
//////////////////////////////////////////////////////////
*/

function print_selected_posts ( $order = 'DESC', $number_of_posts = -1, $cats = false, $cats_relation = 'AND', $post_ids_to_ignore = false, $paginate = true ) {

	/*
	*	$order: the order in which you'd like the posts
	*	$number_of_posts: number of posts to query, -1 will query every published post available
	*	$cats: array of category slugs to include in search paramter
	*	$cats_relation: the relationship between the category slugs. either 'AND' or 'OR'
	*	$post_ids_to_ignore: array of post ids to ignore from query
	*/

	$latest_articles = false;

	if ( $cats != false && is_array( $cats ) && !empty( $cats ) ) {

		$tax_query = array();
		$tax_query_condition = false;

		switch ( $cats_relation ) {
			case 'AND':
				foreach ( $cats as $cat ) {
					$tax_query_condition = array( 'taxonomy' => 'category', 'terms' => array ( $cat ), 'field' => 'slug' );
					array_push( $tax_query, $tax_query_condition );
				}
				break;
			case 'OR':
				$tax_query_condition = array( 'taxonomy' => 'category', 'terms' => $cats, 'field' => 'slug' );
				array_push( $tax_query, $tax_query_condition );
				break;
		}

		//debug_this( $tax_query );

	}

	// WP_Query arguments
	$args = array(
		'post_type'              	=> array( 'post' ),
		'post_status'            	=> array( 'publish' ),
		'order'                  	=> $order,
		'orderby'                	=> 'date',
		'posts_per_page' 			=> $number_of_posts
	);

	if ( !empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	if ( !empty( $post_ids_to_ignore ) ){
		$args['post__not_in'] = $post_ids_to_ignore;
	}

	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

	if ( $paginate ) {
		$args['paged'] = $paged;
	}

	//debug_this( $args );

	// The Query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			// do something
			echo '<h2>' . get_the_title() . '</h2>';
		}

		if ( $paginate ) {

			$total_pages = $query->max_num_pages;

		    if ( $total_pages > 1 ) {

		        $current_page = max(1, get_query_var('paged'));

		        echo paginate_links(array(
		            'base' => get_pagenum_link(1) . '%_%',
		            'format' => '/page/%#%',
		            'current' => $current_page,
		            'total' => $total_pages,
		            'prev_text'    => __('< Prev'),
		            'next_text'    => __('Next >'),
		        ));

		    }

		}

	} else {
		// no posts found
	}

	// Restore original Post Data
	wp_reset_postdata();

} // get_some_posts()

/*
//////////////////////////////////////////////////////////
//// Get Events
//////////////////////////////////////////////////////////
*/

function get_events( $order = 'ASC', $number_of_posts = 200, $cats = false, $cats_relation = 'AND', $post_ids_to_ignore = false, $meta_query = false, $order_by = false ) {

	/*
	*	$order: the order in which you'd like the posts
	*	$number_of_posts: number of posts to query, -1 will query every published post available
	*	$cats: array of category slugs to include in search paramter
	*	$cats_relation: the relationship between the category slugs. either 'AND' or 'OR'
	*	$post_ids_to_ignore: array of post ids to ignore from query
	*/

	date_default_timezone_set('America/Los_Angeles');

	$upcoming_events = array();

	$today = array(
		'date' => date( 'Ymd' ),
		'time' => date( 'H:i:s' ),
		'full_date' => date( 'Ymd H:i:s' )
	);

	$args = array(
		'post_type'             => 	array( 'event' ),
		'post_status'           => 	array( 'publish' ),
		'order'                 => 	$order,
		'posts_per_page' 		=> 	$number_of_posts
	);

	if ( !empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	if ( !empty( $post_ids_to_ignore ) ){
		$args['post__not_in'] = $post_ids_to_ignore;
	}

	if ( $meta_query && $order_by ) {
		$args['meta_query'] = $meta_query;
		$args['orderby'] = $order_by;
	}

	// The Query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {

			$query->the_post();

			// Default Vars
			$event = array();
			$event['location'] = array();
			//$event['organizer'] = array();
			$event['date_time'] = array(
				'has_passed' => true,
				'is_today' => false
			);
			//$event['categories'] = array();

			// Post Vars
			$event['title'] = get_the_title();
			$event['handle'] = clean_string( get_the_title() );
			$event['link'] = get_permalink();
			$event['thumbnail'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );

			// Date/Time Vars
			// Default Vars
			if ( have_rows( 'event_info' ) ) {
				while ( have_rows( 'event_info' ) ) {

					the_row();

					// Location Vars
					if ( get_sub_field( 'name' ) ) {
						$event['location']['name'] = get_sub_field( 'name' );
					}
					if ( get_sub_field( 'address_1' ) ) {
						$event['location']['address_1'] = get_sub_field( 'address_1' );
					}
					if ( get_sub_field( 'address_2' ) ) {
						$event['location']['address_2'] = get_sub_field( 'address_2' );
					}
					if ( get_sub_field( 'city' ) ) {
						$event['location']['city'] = get_sub_field( 'city' );
					}
					if ( get_sub_field( 'region' ) ) {
						$event['location']['region'] = get_sub_field( 'region' );
					}
					if ( get_sub_field( 'postal' ) ) {
						$event['location']['postal'] = get_sub_field( 'postal' );
					}
					if ( get_sub_field( 'country' ) ) {
						$event['location']['country'] = get_sub_field( 'country' );
					}
					// Date / Time Vars
					// Default Vars
					$date = $start_time = $end_time = $time_difference = $date_difference = false;
					if ( get_sub_field( 'date' ) ) {
						$date =  get_sub_field( 'date' );
						$event['date_time']['date'] = $date;
						$event['date_time']['date_formatted'] = date ( 'l, F jS, Y', strtotime( $date ) );
					}
					if ( get_sub_field( 'start_time' ) ) {
						$start_time = get_sub_field( 'start_time' );
						$event['date_time']['start_time'] = $start_time;
						$event['date_time']['start_time_formatted'] = date ( 'h:m a', strtotime( $start_time ) );
					}
					if ( get_sub_field( 'end_time' ) ) {
						$end_time = get_sub_field( 'end_time' );
						$event['date_time']['end_time'] = $end_time;
						$event['date_time']['end_time_formatted'] = date ( 'h:m a', strtotime( $end_time ) );
					}
					if ( $today && $start_time && $date ) {
						$time_difference = ( strtotime( $date . ' ' . $start_time ) - strtotime( $today['full_date'] ) );
						$date_difference = ( strtotime( $today['date'] ) - strtotime( $date ) );
						if ( $time_difference > 0 ) {
							$event['date_time']['has_passed'] = false;
						}
						if ( $date_difference === 0 ) {
							$event['date_time']['is_today'] = true;
						}
					}

				}
			}

			// Add Event to Array of Upcoming Events
			array_push( $upcoming_events, $event );

		} // end 'while have posts'

	} // end if 'have posts'

	// Restore original Post Data
	wp_reset_postdata();

	return $upcoming_events;

}
