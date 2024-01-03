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
  $block_name = "mobile-menu";
  $block_classes = $block_name;
  $block_id = $block_name;

  // ---------------------------------------- Content (ACF)
  $header = get_field("header","options") ?: [];
  $header_navigation = $header["navigation"] ?: [];

?>

<div class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>">
  <div class="<?= $block_name; ?>__main">

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

    <div class="<?= $block_name; ?>__footer">
      <strong class="<?= $block_name; ?>__copyright">C/O <?= date('Y') . ' ' . get_bloginfo('name'); ?></strong>
    </div>

  </div>
</div>
