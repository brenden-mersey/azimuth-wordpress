<?php

/*
*
*	Template Name: Category
*	Filename: category.php
*
*/

get_header();

//////////////////////////////////////////////////////////
////  Theme Vars
//////////////////////////////////////////////////////////

$THEME = new CustomTheme();
$home = $THEME->get_theme_directory('home');
$assets_dir = $THEME->get_theme_directory('assets');
$theme_dir = $THEME->get_theme_directory();

echo '<div class="blog blog--category category">';

  $ids_to_skip = [];
  $this_cat_id = false;

  if ( isset($_GET["post_ids"]) && !empty($_GET["post_ids"]) ) {
    $ids_to_skip = $_GET["post_ids"];
  }

  $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

  $query_args = [
    'post_type'             => 'post',
    'post_status'           => 'publish',
    'order'                 => 'DESC',
    'orderby'               => 'date',
    'nopaging'              => false,
    'paged'                 => $paged,
    'posts_per_page'        => '9',
  ];

  if ( !empty( $ids_to_skip ) ) {
    $query_args['post__not_in'] = $ids_to_skip;
  }

  if (  get_queried_object() ) {
    $queried_object = get_queried_object();
    $this_cat_id = $queried_object->cat_ID;
    $query_args['category__in'] = [ $this_cat_id ];
  }

  $the_query = new WP_Query( $query_args );

  if ( $the_query->have_posts() ) {

    echo '<div class="blog__articles">';
      echo '<div class="container"><div class="row"><div class="col-12">';

        echo '<div class="row row--inner">';

          while ( $the_query->have_posts() ) {

            // init data
            $the_query->the_post();

            // get data
            if ( get_the_ID() ) {
              $id = get_the_ID();
              echo '<div class="col-12 col-md-4">';
                echo $THEME->render( 'article.preview', [ 'id' => $id ] );
              echo '</div>';
            }

          } // endwhile

        echo '</div>';

        if ( $the_query->max_num_pages > 1 )  {

          $pagination_links = [];
          $max_pages = $the_query->max_num_pages;
          $pagination_format = empty( get_option('permalink_structure') ) ? '&page=%#%' : 'page/%#%/';
          $pagination_args = [
            'base' => get_pagenum_link(1) . '%_%',
            'format' => $pagination_format,
            'total' => $max_pages,
            'current' => $paged,
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

          $pagination_links = paginate_links( $pagination_args );

          echo '<div class="blog__pagination pagination">';

            echo '<div class="pagination__button pagination__button--prev">';
              $button_label = '<span class="pagination__arrow prev">←</span>Prev';
              if ( get_previous_posts_link( $button_label ) ) {
                echo get_previous_posts_link( $button_label );
              } else {
                echo '<span class="pagination__no-link">' . $button_label . '</span>';
              }
            echo '</div>';

            if ( $pagination_links && !empty( $pagination_links ) ) {
              echo '<ul class="pagination__links">';
              foreach( $pagination_links as $i => $link ) {
                echo '<li class="pagination__links-item">' . $link . '</li>';
              }
              echo '</ul>';
            }

            echo '<div class="pagination__button pagination__button--next">';
              $button_label = 'Next<span class="pagination__arrow next">→</span>';
              if ( get_next_posts_link( $button_label, $max_pages ) ) {
                echo get_next_posts_link( $button_label, $max_pages );
              } else {
                echo '<span class="pagination__no-link">' . $button_label . '</span>';
              }
            echo '</div>';

          echo '</div>';

        }

      echo '</div></div></div>';
    echo '</div>';

    // reset original post data
    wp_reset_postdata();

  }

echo '</div>';

get_footer();

?>
