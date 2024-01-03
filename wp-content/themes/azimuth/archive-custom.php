<?php

/*
*
*	Template Name: Archive [ Custom ]
*	Filename: archive-custom.php
*
*/

get_header();

// ---------------------------------------- Polite Department
$THEME = new CustomTheme();
$post_id = get_the_ID();

// ---------------------------------------- Template Data
$block_name = 'custom';
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$post_type = get_post_type();

// ---------------------------------------- Template
echo '<div class="' . $block_name . '">';

  if ( $_GET ) {

    //////////////////////////////////////////////////////////
    ////  Default WP Query with URL Params
    //////////////////////////////////////////////////////////

    if ( have_posts() ) {

      echo '<section class="' . $block_name . '__grid all">';
        echo $THEME->render_bs_container( 'open', 'col-12', 'container-fluid' );
          echo '<div class="row row--inner">';
            while ( have_posts() ) {
              the_post();
              echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">';
                echo $THEME->render_article_preview( [ 'post_id' =>  get_the_ID() ] );
              echo '</div>';
            }
          echo '</div>';
        echo $THEME->render_bs_container( 'closed' );
      echo '</section>';

      if ( $wp_query->max_num_pages > 1 ) {

        $button_prev = get_previous_posts_link( 'Prev' );
        $button_next = get_next_posts_link( 'Next',  $wp_query->max_num_pages );
        $big = 999999999;
        $pagination_format = empty( get_option('permalink_structure') ) ? '&page=%#%' : 'page/%#%/';
        $pagination_args = [
          'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format' => $pagination_format,
          'total' => $wp_query->max_num_pages,
          'current' => max( 1, get_query_var('paged') ),
          'aria_current' => false,
          'show_all' => true,
          'end_size' => 1,
          'mid_size' =>2,
          'prev_next' => false,
          'prev_text' => '',
          'next_text' => '',
          'type' => 'array',
          'add_args' => false,
          'add_fragment' => '',
          'before_page_number' => '',
          'after_page_number' => ''
        ];
        $pagination_links = paginate_links( $pagination_args ) ? paginate_links( $pagination_args ) : [];

        echo $THEME->render_pagination([ 'next' => $button_next, 'pages' => $pagination_links, 'prev' => $button_prev, ]);
      }

    } else {
      echo '<section class="error">';
        echo $THEME->render_bs_container( 'open', 'col-12', 'container-fluid' );
          echo '<h2 class="press__title title title--no-press">Nothing here.</h2>';
        echo $THEME->render_bs_container( 'closed' );
      echo '</section>';
    }

  } else {

    //////////////////////////////////////////////////////////
    ////  Custom WP Query
    //////////////////////////////////////////////////////////

    $query_args = [
      'post_type'             => [ 'press' ],
      'post_status'           => [ 'publish' ],
      'order'                 => 'DESC',
      'orderby'               => 'date',
      'nopaging'              => false,
      'paged'                 => $paged,
      'post__not_in'          => [],
    ];

    $press_query = new WP_Query( $query_args );

    if ( $press_query->have_posts() ) {

      echo '<section class="' . $block_name . '__grid all">';
        echo $THEME->render_bs_container( 'open', 'col-12', 'container-fluid' );
          echo '<div class="row row--inner">';

            while ( $press_query->have_posts() ) {

              // init post data
              $press_query->the_post();

              echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">';
                echo $THEME->render_article_preview( [ 'post_id' =>  get_the_ID() ] );
              echo '</div>';

            }

          echo '</div>';
        echo $THEME->render_bs_container( 'closed' );
      echo '</section>';

      if ( $press_query->max_num_pages > 1 ) {
        $button_prev = get_previous_posts_link( 'Prev' );
        $button_next = get_next_posts_link( 'Next', $press_query->max_num_pages );
        $big = 999999999;
        $pagination_format = empty( get_option('permalink_structure') ) ? '&page=%#%' : 'page/%#%/';
        $pagination_args = [
          'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format' => $pagination_format,
          'total' => $wp_query->max_num_pages,
          'current' => max( 1, get_query_var('paged') ),
          'aria_current' => false,
          'show_all' => true,
          'end_size' => 1,
          'mid_size' =>2,
          'prev_next' => false,
          'prev_text' => '',
          'next_text' => '',
          'type' => 'array',
          'add_args' => false,
          'add_fragment' => '',
          'before_page_number' => '',
          'after_page_number' => ''
        ];
        $pagination_links = paginate_links( $pagination_args ) ? paginate_links( $pagination_args ) : [];
        echo $THEME->render_pagination([ 'next' => $button_next, 'pages' => $pagination_links, 'prev' => $button_prev, ]);
      }

      wp_reset_postdata();

    } else {
      echo '<section class="error">';
        echo $THEME->render_bs_container( 'open', 'col-12', 'container-fluid' );
          echo '<h2>Nothing here</h2>';
        echo $THEME->render_bs_container( 'closed' );
      echo '</section>';
    }

  }

echo '</div>';

get_footer();

?>
