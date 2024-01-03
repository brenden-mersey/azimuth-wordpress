<?php
	
/*
*	
*	Filename: tag.php
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
} else {
  
}

wp_reset_postdata();

get_footer();

?>
