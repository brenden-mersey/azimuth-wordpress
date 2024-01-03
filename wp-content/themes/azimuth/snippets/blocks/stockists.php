<?php

  /**
  *
  *   Stockists
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "stockists";
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
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $stockist_regions = get_field("stockist_regions") ?: [];
  $text_colour = get_field("text_colour") ?: "";

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

      <?php if ( !empty($stockist_regions) ) : ?>
        <div class="<?= $block_name; ?>__listing">
          <?php foreach ( $stockist_regions as $region ) : ?>

            <?php
              $region_name = $region["name"] ?? "";
              $region_locations = $region["locations"] ?? [];
            ?>

            <?php if ( $region_name || !empty($region_locations) ) : ?>
              <?php $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-up" ]); $aos_delay += $aos_increment; ?>
              <div class="<?= $block_name; ?>__region" <?= $aos_attrs; ?>>
                <?php if ( $region_name ) : ?>
                  <h2 class="<?= $block_name; ?>__region-name heading heading--1"><?= $region_name; ?></h2>
                <?php endif; ?>
                <?php if ( !empty($region_locations) ) : ?>
                  <div class="<?= $block_name; ?>__region-locations">

                    <?php
                      foreach ( $region_locations as $location ) {
                        $name = trim($location["name"] ?? "");
                        $full_address = trim($location["full_address"] ?? "");
                        $phone = trim($location["phone"] ?? "");
                        if ( $full_address ) {
                          echo "
                            <div class='{$block_name}__location'>
                              " . ( $name ? "<strong class='{$block_name}__location-name'>{$name}</strong>" : "" ) . "
                              <div class='{$block_name}__location-details body-copy--primary'>
                                <p>{$full_address}</p>
                                " . ( $phone ? "<span class='{$block_name}__location-phone-number'><a href='tel:{$phone}' title='{$name} Phone Number'>{$phone}</a></span>" : "" ) . "
                              </div>
                            </div>
                          ";
                        }
                      }
                    ?>

                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?= $THEME->render_bs_container( "closed", $cols, $container ); ?>
  </section>

<?php endif; ?>
