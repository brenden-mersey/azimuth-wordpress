<?php

  /**
  *
  *	Template Name: Index
  *	Filename: index.php
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
  echo '<div class="' . $template . '" id="' . $template_id . '">';

    if ( have_posts() ) {
	    while ( have_posts() ) {

		    // init post data
		    the_post();

		    // default data
        the_content();

	    }
    }

  echo '</div>';

  // ---------------------------------------- Mount WP Footer
  get_footer();

?>
