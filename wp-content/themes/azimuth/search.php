<?php
	
/*
*	
*	Filename: search.php
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
 
if ( have_posts() ) {
  
  echo '<section class="section section--intro">';
    echo '<h2>Search Results For: ' . get_search_query() . '</h2>';
  echo '</section>';
  
  while ( have_posts() ) {
    
    // init data
    the_post();
    
    // default data
    $title = $excerpt = false;
    
    // get data
    if ( get_the_title() ) {
      $title = get_the_title();
    }
    if ( get_the_excerpt() ) {
      $excerpt = get_the_excerpt();
    }
    
    if ( $title ) {
      
      echo '<article>'
  
        echo '<h2>' . $title . '</h2>';
        
        if ( $excerpt ) {
          echo '<div class="message rte">';
            echo $excerpt;
          echo '</div>';
        }
  
      echo '</article>'
      
    }
    
  }
  
  echo '<div class="pagination">';
	  echo '<button class="pagination__btn pagination__btn--prev" type="button">';
      echo get_previous_posts_link( 'Newer Entries' );
    echo '</button>';
    echo '<button class="pagination__btn pagination__btn--next" type="button">';
      echo get_next_posts_link( 'Older Entries' );
    echo '</button>';
  echo '</div>';

} 

wp_reset_postdata();

get_footer(); 

?>
