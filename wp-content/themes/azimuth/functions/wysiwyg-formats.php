<?php

/*
//////////////////////////////////////////////////////////
////  Add Style Select Buttons
//////////////////////////////////////////////////////////
*/

function add_style_select_buttons ( $buttons ) {

  array_unshift( $buttons, 'styleselect' );
  return $buttons;

}

// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'add_style_select_buttons' );

/*
//////////////////////////////////////////////////////////
////  Add Custom Styles
//////////////////////////////////////////////////////////
*/

function my_custom_styles ( $init_array ) {

    if ( true ) {

      $style_formats = [
        [
          'title' => 'Callout',
          'classes' => 'callout',
          'wrapper' => true,
          'inline' => 'span'
        ],
        [
          'title' => 'Intro Para',
          'classes' => 'intro-para',
          'wrapper' => true,
          'inline' => 'span'
        ],
      ];

      // Insert the array, JSON ENCODED, into 'style_formats'
      $init_array['style_formats'] = json_encode( $style_formats );

    }

    return $init_array;

}

// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'my_custom_styles' );

function my_theme_add_editor_styles() {
	add_editor_style( 'assets/editor-styles.css' );
}

add_action( 'admin_init', 'my_theme_add_editor_styles' );
