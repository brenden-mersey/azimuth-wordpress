<?php

  /**
  *
  *   Header
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Template Data
  $block_name = "header";
  $block_classes = $block_name;
  $block_id = $block_name;

  // ---------------------------------------- Content (ACF)
  $cols = get_field("cols") ?: "col-12";
  $container = get_field("container") ?: "container-fluid";
  $header = get_field("header","options") ?: [];
  $header_navigation = $header["navigation"] ?: [];

?>

<header class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>">
  <?= $THEME->render_bs_container( "open", $cols, $container ); ?>
    <div class="<?= $block_name; ?>__main">

      <div class="<?= $block_name; ?>__brand">
        <a class="<?= $block_name; ?>__brand-link link" href="<?= $THEME->get_theme_directory('home'); ?>" target="_self" title="Home">
          <?= $THEME->render_svg([ 'type' => 'brand.monogram' ]); ?>
        </a>
      </div>

      <?php if ( $header_navigation ) : ?>
        <nav class="<?= $block_name; ?>__navigation">
          <?php
            foreach ( $header_navigation as $item ) {
              $item["block_name"] = $block_name;
              $item["current_id"] = $id;
              echo $THEME->render_navigation_item($item);
            }
          ?>
        </nav>
      <?php endif; ?>

      <button type="button" class="<?= $block_name; ?>__hamburger hamburger d-inline-flex d-lg-none js--mobile-menu-toggle-trigger">
        <span class="hamburger__inner top"></span>
        <span class="hamburger__inner middle"></span>
        <span class="hamburger__inner bottom"></span>
      </button>

    </div>
  <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
</header>
