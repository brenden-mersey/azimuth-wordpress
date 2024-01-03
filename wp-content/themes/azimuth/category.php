<?php

/*
*
*	Template Name: Category
*	Filename: category.php
*
*/

get_header();

// ---------------------------------------- Polite Department
$THEME = new CustomTheme();
$post_id = get_the_ID();

if ( have_posts() ) {
  while ( have_posts() ) {
    the_post();
    echo get_the_title();
  }
}

get_footer();

?>

