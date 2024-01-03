<?php

$image_sizes = new ImageSizes();
add_action( 'after_setup_theme', [ $image_sizes, 'init' ] );

class ImageSizes {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'Image Sizes';
  private $version = '1.0';

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  public function init() {

    // required
	  add_theme_support( 'post-thumbnails' );

	  // initialize instance of PoliteDepartment
	  $THEME = new CustomTheme();

	  // get custom sizes
	  $custom_sizes = $THEME->custom_image_sizes;

	  foreach ( $custom_sizes as $index => $size ) {
  	  $size_title = $THEME->custom_image_title;
  	  $size_title .= '-' . $size;
  	  add_image_size( $size_title, $size, 9999 );
	  }

  }

  /*
  //////////////////////////////////////////////////////////
  ////  Constructor
  //////////////////////////////////////////////////////////
  */

  public function __construct() {}

} // ImageSizes()
