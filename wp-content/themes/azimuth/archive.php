<?php

  /*
  *
  *	Filename: archive.php
  *
  */

  // ---------------------------------------- Mount WP Header
  get_header();

  // ---------------------------------------- Data
  $THEME = new CustomTheme();

  if ( have_posts() ) {
    while ( have_posts() ) {

      the_post();

      $post_id = get_the_ID() ?: 0;
      $title = get_the_title() ?: 'Title Not Set';
      $permalink = get_permalink() ?: 'Permalink Not Set';

      echo '<article>';
        echo '<h1><a href="' . $permalink . '" target="_self" title="' . $title . '">' . $title . '</a></h1>';
      echo '</article>';

    }
  }

  // ---------------------------------------- Mount WP Footer
  get_footer();

?>

