<?php

/*
//////////////////////////////////////////////////////////
////  Block Frames ( Stops Clickjacking )
//////////////////////////////////////////////////////////
*/

function block_frames() {
  header( 'X-FRAME-OPTIONS: SAMEORIGIN' );
}

add_action( 'send_headers', 'block_frames', 10 );

/*
//////////////////////////////////////////////////////////
////  Blocks User Enumeration
//////////////////////////////////////////////////////////
*/

// block WP enum scans
// https://m0n.co/enum

if (!is_admin()) {
	// default URL format
	if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
	add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}

function shapeSpace_check_enum($redirect, $request) {
	// permalink URL format
	if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
	else return $redirect;
}
