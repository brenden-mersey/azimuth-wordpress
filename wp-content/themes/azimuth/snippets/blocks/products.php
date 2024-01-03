<?php

  /**
  *
  *   Products
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "products";
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
  $cta = get_field("cta") ?: [];
  $cta_link = $THEME->get_link($cta);
  $cta_title = $cta["title"] ?? "";
  $enable = get_field("enable") ?: false;
  $heading = get_field("heading") ?: "";
  $message = get_field("message") ?: "";
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $products_listing = get_field("products_listing") ?: [];
  $products_listing_glider = count($products_listing) > 1 ? true : false;
  $products_listing_glider_id = $THEME->get_unique_id("{$block_name}-glider--");
  $text_colour = get_field("text_colour") ?: "";

  // ---------------------------------------- Conditionals
  $has_cta = $cta_link && $cta_title ? true : false;

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

  <section class="<?= esc_attr( $block_classes ); ?>" id="<?= esc_attr( $block_id ); ?>" data-scroll-to-target="#<?= $block_name; ?>">
   <div class="block__anchor" id="<?= $block_name; ?>-anchor"></div>

    <?= $THEME->render_bs_container( "open", $cols, $container ); ?>

      <div class="<?= $block_name; ?>__main">

        <?php if ( $heading || $message ) : ?>
          <div class="<?= $block_name; ?>__aside">
            <?php if ( $heading ) : ?>
              <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); $aos_delay += $aos_increment; ?>
              <h2 class="<?= $block_name; ?>__heading heading--2" <?= $aos_attrs; ?>><?= $heading;?></h2>
            <?php endif; ?>
            <?php if ( $message ) : ?>
              <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]); $aos_delay += $aos_increment; ?>
              <div class="<?= $block_name; ?>__message body-copy--primary" <?= $aos_attrs; ?>><?= $message; ?></div>
            <?php endif; ?>
            <?php if ( $has_cta ) : ?>
              <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-up" ]); $aos_delay += $aos_increment; ?>
              <div class="<?= $block_name; ?>__cta d-none d-lg-flex" <?= $aos_attrs; ?>>
                <?=
                  $THEME->render_link([
                    "block_name" => "button",
                    "title" => $cta_title,
                    "url" => $cta_link,
                  ]);
                ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if ( !empty($products_listing) ) : ?>
          <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-up" ]); $aos_delay += $aos_increment; ?>
          <div class="<?= $block_name; ?>__listing glide js--glide" id="<?= $products_listing_glider_id; ?>" data-glide-style="<?= $block_name; ?>" <?= $aos_attrs; ?>>
            <div class="glide__track" data-glide-el="track">
              <ul class="glide__slides">
                <?php
                  foreach ( $products_listing as $product ) {
                    echo "<li class='glide__slide'>";
                      echo $THEME->render_card_product($product);
                    echo "</li>";
                  }
                ?>
              </ul>
            </div>
            <div class="glide__controls">
              <?php $button_icon = $THEME->render_svg_icon("arrow"); ?>
              <button class="glide__button button prev" data-target="#<?= $products_listing_glider_id; ?>">
                <span class="button__icon"><?= $button_icon; ?></span>
              </button>
              <button class="glide__button button next" data-target="#<?= $products_listing_glider_id; ?>">
                <span class="button__icon"><?= $button_icon; ?></span>
              </button>
            </div>
          </div>
        <?php endif; ?>

      </div>

    <?php if ( $has_cta ) : ?>
     <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-up" ]); ?>
      <div class="<?= $block_name; ?>__cta d-flex d-lg-none" <?= $aos_attrs; ?>>
        <?=
          $THEME->render_link([
            "block_name" => "button",
            "title" => $cta_title,
            "url" => $cta_link,
          ]);
        ?>
      </div>
    <?php endif; ?>

    <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
  </section>
<?php endif; ?>
