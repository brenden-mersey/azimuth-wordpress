<?php

/*
//////////////////////////////////////////////////////////
////  Allow SVG Uploads
//////////////////////////////////////////////////////////
*/

function cc_mime_types($mimes) {

  $mimes['svg'] = 'image/svg+xml';
  return $mimes;

}

add_filter( 'upload_mimes', 'cc_mime_types' );

/*
//////////////////////////////////////////////////////////
////  Add .webm, .mp4 and .ogg to mime types
//////////////////////////////////////////////////////////
*/

function aco_extend_mime_types( $existing_mimes ) {

  // Add webm, mp4 and OGG to the list of mime types
  $existing_mimes['webm'] = 'video/webm';
  $existing_mimes['mp4']  = 'video/mp4';
  $existing_mimes['ogg']  = 'video/ogg';

  // Return an array now including our added mime types
  return $existing_mimes;

}

add_filter( 'mime_types', 'aco_extend_mime_types' );

/*
//////////////////////////////////////////////////////////
////  Get Current Url
//////////////////////////////////////////////////////////
*/

function get_current_url() {

	$link = '';

	if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
		$link .= "https";
	} else {
		$link .= "http";
	}

	$link .= "://";

	// Append the host(domain name, ip) to the URL.
	$link .= $_SERVER['HTTP_HOST'];

	// Append the requested resource location to the URL
	$link .= $_SERVER['REQUEST_URI'];

	// Print the link
	return $link;

}

/*
//////////////////////////////////////////////////////////
//// Trim String
//////////////////////////////////////////////////////////
*/

function trim_string( $text, $maxchar, $end='...' ) {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            }
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    }
    else {
        $output = $text;
    }
    return $output;
}

/*
//////////////////////////////////////////////////////////
////  Encode URI Components
//////////////////////////////////////////////////////////
*/

function encode_URI( $string ) {

    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');

    return strtr( rawurlencode( $string ), $revert );

}

/*
//////////////////////////////////////////////////////////
////  Covert Object to Array
//////////////////////////////////////////////////////////
*/

function object_to_array( $d ) {

	if ( is_object( $d ) ) {
		// Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars( $d );
    }

    if ( is_array( $d ) ) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

/*
//////////////////////////////////////////////////////////
////  Return CURL Request
//////////////////////////////////////////////////////////
*/

function get_url_with_curl( $url ) {

	$output = false;

	if ( !empty( $url ) ) {

		if ( !function_exists( 'curl_init' ) ) {
        	die( 'CURL is not installed!' );
    	}

	    $ch = curl_init();

	    curl_setopt( $ch, CURLOPT_URL, $url );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	    if ( curl_error( $ch ) ) {
		    // curl error
		} else {
		 	$output = curl_exec($ch);
		}

	    curl_close($ch);

	}

	// echo '<h1 style="background: red; padding: 20px; font-size: 20px;">get_url_curl_() Fired!</h1>';

	return $output;

}
