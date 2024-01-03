<?php

  /**
  *
  *   Feature Image
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "feature-image";
  $block_classes = "{$block_name} block block--{$block_name}";
  $block_data = $block["data"] ?? [];
  $block_id = isset($block["id"]) && !empty($block["id"]) ? "{$block_name}--{$block["id"]}" : $block_name;

  // ---------------------------------------- AOS
  $aos_id = $block_id;
  $aos_delay = 250;
  $aos_increment = 250;

  // ---------------------------------------- Content (ACF)
  $background_colour = get_field("background_colour") ?: "";
  $cols = get_field("cols") ?: "col-12";
  $container = get_field("container") ?: "container";
  $enable = get_field("enable") ?: false;
  $heading = get_field("heading") ?: "";
  $image = get_field("image") ?: [];
  $image_lazy = $THEME->render_lazyload_image([ "image" => $image ]);
  $image_ratio_width = get_field("image_ratio_width") ?: "";
  $image_ratio_height = get_field("image_ratio_height") ?: "";
  $image_position = get_field("image_position") ?: "static";
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $text_colour = get_field("text_colour") ?: "";

  // ---------------------------------------- Conditionals
  $block_classes .= " image-position-{$image_position}";
  $block_classes .= $image_ratio_width && $image_ratio_height ? " with-image-aspect-ratio" : "";

?>

<?php if ( $enable ) : ?>
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
    <?php if ( $image_ratio_width && $image_ratio_height ) : ?>
      #<?= $block_id; ?> .<?= $block_name; ?>__image {
        aspect-ratio: <?= "{$image_ratio_width}/{$image_ratio_height}"; ?>;
      }
    <?php endif; ?>
  </style>

  <section class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>" data-scroll-to-target="#<?= $block_name; ?>">
    <div class="block__anchor" id="<?= $block_name; ?>-anchor"></div>

    <?php if ( $image_lazy ) : ?>
      <div class="<?= $block_name; ?>__image"><?= $image_lazy; ?></div>
    <?php endif; ?>

    <?php if ( $heading ) : ?>
      <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); ?>
      <div class="<?= $block_name; ?>__content">
        <?= $THEME->render_bs_container( "open", $cols, $container ); ?>
          <strong class="<?= $block_name; ?>__heading heading--1" <?= $aos_attrs; ?>><?= $heading; ?></strong>
        <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
      </div>
    <?php endif; ?>

  </section>
<?php endif; ?>
