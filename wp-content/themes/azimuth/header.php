<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->

<head>

   <title><?php wp_title(''); ?></title>

  <?php

    $THEME = $THEME ?? new CustomTheme();
    $assets_dir = $THEME->get_theme_directory('assets');
    $theme_dir = $THEME->get_theme_directory();
    $theme_classes = $THEME->get_theme_classes();
    $object_id = $THEME->get_theme_info('object_ID');
    $post_id = $THEME->get_theme_info('post_ID');

    echo $THEME->render_preconnect_resources([
      'https://cdn.jsdelivr.net',
      'https://www.google-analytics.com',
      'https://fonts.gstatic.com',
      'https://fonts.googleapis.com'
    ]);

  ?>

  <link rel="apple-touch-icon" href="<?= $theme_dir; ?>/apple-touch-icon.png?v=<?= filemtime( get_template_directory() . '/apple-touch-icon.png' ); ?>">
  <link rel="shortcut icon" href="<?= $theme_dir; ?>/favicon.ico?v=<?= filemtime( get_template_directory() . '/favicon.ico' ); ?>">

  <meta charset="<?= get_bloginfo('charset'); ?>">
  <meta http-equiv="Expires" content="7" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Very Polite Agency">

  <link rel="preload" href="<?= $assets_dir; ?>/main.min.js?ver=<?= filemtime( get_template_directory() . '/assets/main.min.js' ); ?>" as="script">

  <?php

    // ---------------------------------------- Preload Fonts
    echo $THEME->render_preload_fonts();

    // ---------------------------------------- Search Engine Optimization (SEO)
    echo $THEME->render_seo();

    // ---------------------------------------- Inline CSS
		echo '<style>';
      include( locate_template( './assets/main.css' ) );
    echo '</style>';

    // ---------------------------------------- External JavaScript
    get_template_part( "snippets/supporting-javascript" );

    // ---------------------------------------- WP Head Hook
    wp_head();

  ?>

</head>

<body class='<?php echo $theme_classes; ?> sticky-footer' data-object-id='<?php echo $object_id; ?>' data-post-id='<?php echo $post_id; ?>'>

  <?php

    // ---------------------------------------- Site Vitals
    echo ( is_user_logged_in() || is_admin() ) ? $THEME->get_theme_info( 'vitals' ) : '';

    // ---------------------------------------- Page Background
    echo $THEME->render_page_background([ 'id' => $post_id ]);

    // ---------------------------------------- Mobile Memu
    get_template_part( "snippets/mobile-menu" );

    // ---------------------------------------- Header
    get_template_part( "snippets/header" )

  ?>

  <main class="<?php echo $theme_classes; ?>" role="main">
