<?php

/*
*
*	Filename: functions.php
*
*/

$includes = [
  "tools",
  "advanced-custom-fields",
  "dashboard-customizer",
  "image-sizes",
  "optimization",
  "custom-post-types",
  "custom-theme-base",
  "custom-theme-templates",
  "custom-theme",
  "pre-get-posts",
  "security",
  "utilities",
  "wysiwyg-formats",
  "enqueue-scripts",
  "enqueue-styles",
];

foreach( $includes as $include ) {
  get_template_part( "functions/{$include}" );
}
