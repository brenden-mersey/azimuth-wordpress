<?php

$dashboard_customizer = new DashboardCustomizer();
add_action( 'customize_register', [ $dashboard_customizer, 'init' ] );

class DashboardCustomizer {

  /*
  *
  *    Info:
  *    Adds custom sections and settings to the theme's Customizer
  *
  *    Getters:
  *    To retrieve customization, call get_theme_mod( $conrol_id )
  *
  *    Deleters:
  *    To delete customization, call remove_theme_mod( $conrol_id );
  *
  *    #TODO
  *      • Add conditional add_control function for images
  *      • Add theme name function to certain fields
  *      • Add better, more universal sanitization function
  *
  */

  /*
  //////////////////////////////////////////////////////////
  ////  Properties
  //////////////////////////////////////////////////////////
  */

  private $name = 'Dashboard Customizer';
  private $enable = false;
  private $version = '1.0';

  /*
  //////////////////////////////////////////////////////////
  ////  Methods | Instance
  //////////////////////////////////////////////////////////
  */

  public function init( $wp_customize ) {
    if ( $this->enable ) {
      $this->sections( $wp_customize );
    }
  }

  // to see custom SQL data run this search
  // SELECT * FROM wp_options WHERE option_name LIKE '%theme_mods_%'

  public function theme_name() {
    return 'verypolite';
  }

  private function sections( $wp_customize ) {

    $_this = $this;

    $sections = [
      'alakazam' => [
        'title' => 'Alakazam Section',
        'desc' => 'Alakazam Section Description',
        'priority' => 2,
        'fields' => [
          'text' => [
            'label' => 'Text',
            'type' => 'text',
            'default' => '',
            'priority' => 2,
            'sanitize_callback' => 'sanitize_custom_text',
          ],
          'textarea' => [
            'label' => 'Text Secondary',
            'type' => 'textarea',
            'default' => '',
            'priority' => 2,
            'sanitize_callback' => 'sanitize_custom_text',
          ],
          'text_secondary' => [
            'label' => 'Text Secondary',
            'type' => 'text',
            'default' => '',
            'priority' => 2,
            'sanitize_callback' => 'sanitize_custom_text',
          ],
        ]
      ]
    ];

    foreach ( $sections as $sid => $section ) {

      // $sid = 'sample'
      $section_id = $sid . '_section';

      $wp_customize->add_section( $section_id, [
        'title' => $section['title'],
        'description' => $section['desc'],
        'priority' => $section['priority'],
      ]);

      $fields = $section['fields'];

      foreach ( $fields as $fid => $field ) {

        $setting_id = $section_id . '_setting_' . $fid;
        $control_id = $section_id . '_' . $fid;

        $wp_customize->add_setting( $setting_id, [
          'default' => $field['default'],
          'sanitize_callback' => [ $_this, $field['sanitize_callback'] ],
        ]);

        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $control_id, [
          'section' => $section_id,
          'settings' => $setting_id,
          'label' => $field['label'],
          'type' => $field['type'],
          'priority' => $field['priority'],
        ]));

      } // foreach

    } // foreach

  }

  public function sanitize_custom_option( $input ) {
    return ( $input === 'No' ? 'No' : 'Yes' );
  }

  public function sanitize_custom_text( $input ) {
    return filter_var( $input, FILTER_SANITIZE_STRING );
  }

  public function sanitize_custom_image_url( $input ) {
    return filter_var( $input, FILTER_SANITIZE_URL );
  }

  /*
  //////////////////////////////////////////////////////////
  ////  Constructor
  //////////////////////////////////////////////////////////
  */

  public function __construct() {}

}
