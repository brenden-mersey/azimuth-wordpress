<?php

$tools = new Tools();
add_action( 'init', [ $tools, 'init' ] );

class Tools {

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'Tools';
  private $version = '1.0';

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  // ---------------------------------------- Register Menus
  public function register_menus(){
  	register_nav_menus(
		  array(
			  'main-menu' => __( 'Main Menu' ),
			  'push-menu--left' => __( 'Push Menu [ Left ]' ),
			  'push-menu--right' => __( 'Push Menu [ Right ]' )
		  )
	  );
  }

  // ---------------------------------------- Is Localhost
  public function is_localhost() {
  	$whitelist = [ '127.0.0.1', '::1' ];
  	if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) ) {
  		return true;
  	}
  }

  // ---------------------------------------- Initialize
  public function init() {

    $this->register_menus();

    if ( $this->is_localhost() ) {
      add_filter( 'http_request_args', function ( $r ) {
      	$r['sslverify'] = false;
      	return $r;
      });
    }

  }

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Static
  //////////////////////////////////////////////////////////
  */

  // ---------------------------------------- Debug This
  public static function debug_this( $object = null, $object_name = '', $print = true ) {

    $html = '<div class="php-debugger" style="background: black; width: 90%; display: block; margin: 65px auto; padding: 40px 25px; position: relative; z-index: 10;">';
  	  $html .= '<pre style="color: #fff;">';
        $html .= $object_name ? '<h3 style="margin-bottom: 20px;">Name: ' . $object_name . '</h3>' : '';
  		  $html .= '<h3 style="margin-bottom: 20px;">Type:</h3>';
  		  $html .= gettype ( $object );
  		  $html .= '<h3 style="margin-bottom: 20px;">Contents:</h3>';
  		  $html .= print_r( $object, true );
  	  $html .= '</pre>';
	  $html .= '</div>';

    if ( $print ) {
      echo $html;
    } else {
      return $html;
    }

  }

  // ---------------------------------------- Handleize
  public static function handleize( $string = '' ) {

    // removes html
  	$string = strip_tags( $string );

    //Lower case everything
    $string = strtolower( $string );

    //Make alphanumeric (removes all other characters)
    $string = preg_replace( "/[^a-z0-9_\s-]/", "", $string );

    //Clean up multiple dashes or whitespaces
    $string = preg_replace( "/[\s-]+/", " ", $string );

    //Convert whitespaces and underscore to dash
    $string = preg_replace( "/[\s_]/", "-", $string );

    return $string;

  }

  // ---------------------------------------- Is Child
  public static function is_child() {

    global $post;

	  if ( is_page() && $post->post_parent ) {
		  return true;
	  }

    return false;

  }

  // ---------------------------------------- Number to Roman Numeral
  public static function number_to_roman_numeral( $number = 0, $upper = false ) {

    $converted = '';
    $map = [
      'm' => 1000,
      'cm' => 900,
      'd' => 500,
      'cd' => 400,
      'c' => 100,
      'xc' => 90,
      'l' => 50,
      'xl' => 40,
      'x' => 10,
      'ix' => 9,
      'v' => 5,
      'iv' => 4,
      'i' => 1
    ];

    while ( $number > 0 ) {
      foreach ( $map as $roman => $int ) {
        if ( $number >= $int ) {
          $number -= $int;
          $converted .= $roman;
          break;
        }
      }
    }

    $converted = $upper ? strtoupper( $converted ) : $converted;

    return trim( $converted );

  }

  /*
  //////////////////////////////////////////////////////////
  ////  Constructor
  //////////////////////////////////////////////////////////
  */

  public function __construct() {}

}
