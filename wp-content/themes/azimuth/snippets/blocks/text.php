<?php

  /**
  *
  *   Text
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "text";
  $block_classes = "{$block_name} block block--{$block_name}";
  $block_data = $block["data"] ?? [];
  $block_id = isset($block["id"]) && !empty($block["id"]) ? "{$block_name}--{$block["id"]}" : $block_name;

  // ---------------------------------------- AOS
  $aos_id = $block_id;
  $aos_delay = 250;
  $aos_increment = 250;

  // ---------------------------------------- Content (ACF)
  $background_colour = get_field("background_colour") ?: "";
  $cols = get_field("cols") ?: "col-12 col-lg-10 offset-lg-1";
  $container = get_field("container") ?: "container";
  $enable = get_field("enable") ?: false;
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $text_alignment = get_field("text_alignment") ?: "left";
  $text_colour = get_field("text_colour") ?: "";
  $text_content = get_field("text_content") ?: "";
  $text_width = get_field("text_width") ?: "standard";

  // ---------------------------------------- Conditionals
  switch ( $text_width ) {
    case "standard": {
      $cols = "col-12";
      break;
    }
    case "narrow": {
      $cols = "col-12 col-lg-10 offset-lg-1";
      break;
    }
    case "compact": {
      $cols = "col-12 col-lg-8 offset-lg-2";
      break;
    }
  }

?>

<?php if ( $enable && $text_content ) : ?>

  <style data-block-id="<?= $block_name; ?>">
    <?=
      $THEME->render_element_styles([
        "background_colour" => $background_colour,
        "id" => $block_id,
        "padding_bottom" => $padding_bottom,
        "padding_top" => $padding_top,
        "text_colour" => $text_colour,
      ]);
    ?>
    #<?= $block_id; ?> .<?= $block_name; ?>__content {
      text-align: <?= $text_alignment; ?>;
    }
  </style>

  <section class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>">
    <div class="block__anchor" id="<?= $block_name; ?>-anchor"></div>
    <?= $THEME->render_bs_container( "open", $cols, $container ); ?>
      <div class="<?= $block_name; ?>__content body-copy--primary"><?= $text_content; ?></div>
    <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
  </section>

<?php endif; ?>
