<?php

  /**
  *
  *	Filename: single.php
  *
  */

  // ---------------------------------------- Mount WP Header
  get_header();

  // ---------------------------------------- Data
  $THEME = new CustomTheme();
  $id = get_the_ID() ?: 0;

  // ---------------------------------------- Template Data
  $template = 'index';
  $template_id = $THEME->get_unique_id("{$template}--");

  // ---------------------------------------- Template
  echo '<article class="' . $template . '" id="' . $template_id . '">';

    if ( have_posts() ) {
	    while ( have_posts() ) {

		    // init post data
		    the_post();

        echo '<h1>' . get_the_title() ?: 'No Title Set' . '</h1>';

        the_content();

	    }
    }

  echo '</article>';

  // ---------------------------------------- Mount WP Footer
  get_footer();

?>
