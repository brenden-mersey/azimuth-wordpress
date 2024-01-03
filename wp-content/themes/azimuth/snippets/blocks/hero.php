<?php

  /**
  *
  *   Hero
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "hero";
  $block_classes = "{$block_name} block block--{$block_name}";
  $block_data = $block["data"] ?? [];
  $block_id = isset($block["id"]) && !empty($block["id"]) ? "{$block_name}--{$block["id"]}" : $block_name;

  // ---------------------------------------- AOS
  $aos_id = $block_id;
  $aos_delay = 250;
  $aos_increment = 250;
  $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]);

  // ---------------------------------------- Content (ACF)
  $background_colour = get_field("background_colour") ?: "";
  $cols = get_field("cols") ?: "col-12";
  $container = get_field("container") ?: "container";
  $enable = get_field("enable") ?: false;
  $heading = get_field("heading") ?: "";
  $heading_as_title = get_field("heading_as_title") ?: false;
  $message = get_field("message") ?: "";
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $text_colour = get_field("text_colour") ?: "";
  $vertical_rule = get_field("vertical_rule") ?: false;

  // ---------------------------------------- Conditionals
  $block_classes .= $vertical_rule ? " with-vr" : "";
  $heading = $heading_as_title ? get_the_title($id) : $heading;

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
  </style>

  <section class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>">
    <div class="block__anchor" id="<?= $block_name; ?>-anchor"></div>
    <?= $THEME->render_bs_container( "open", $cols, $container ); ?>
      <div class="<?= $block_name; ?>__main">

        <?php if ( $heading ) : ?>
          <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); $aos_delay += $aos_increment; ?>
          <h1 class="<?= $block_name; ?>__heading heading--1" <?= $aos_attrs; ?>><?= $heading; ?></h1>
        <?php endif; ?>


        <?php if ( $message ) : ?>
          <div class="<?= $block_name; ?>__extra">
            <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); $aos_delay += $aos_increment; ?>
            <div class="<?= $block_name; ?>__message body-copy--primary" <?= $aos_attrs; ?>><?= $message; ?></div>
            <?php if ( $vertical_rule ) : ?>
              <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-down" ]); $aos_delay += $aos_increment; ?>
              <div class="<?= $block_name; ?>__vr" <?= $aos_attrs; ?>>
                <span class="<?= $block_name; ?>__vr-icon"><?= $THEME->render_svg([ "type" => "icon.circle" ]); ?></span>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

      </div>
    <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
  </section>

<?php endif; ?>
