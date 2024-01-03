<?php

  /**
  *
  *	Filename: page.php
  *
  */

  // ---------------------------------------- Mount WP Header
  get_header();

  // ---------------------------------------- Data
  $THEME = new CustomTheme();
  $id = get_the_ID() ?: 0;

  // ---------------------------------------- Template Data
  $template = 'default-page';
  $template_id = $THEME->get_unique_id("{$template}--");
  $featured_image = $THEME->get_featured_image_by_post_id( $id );
  $products = [
    [
      'title' => 'Sample Product Alpha',
      'image' => $featured_image,
    ],
    [
      'title' => 'Sample Product Beta',
      'image' => $featured_image,
    ],
    [
      'title' => 'Sample Product Charlie',
      'image' => $featured_image,
    ],
    [
      'title' => 'Sample Product Delta',
      'image' => $featured_image,
    ],
    [
      'title' => 'Sample Product Echo',
      'image' => $featured_image,
    ],
    [
      'title' => 'Sample Product Foxtrot',
      'image' => $featured_image,
    ],
    [
      'title' => 'Sample Product Golf',
      'image' => $featured_image,
    ],
  ];

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
