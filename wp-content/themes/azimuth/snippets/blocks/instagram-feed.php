<?php

  /**
  *
  *   Instagram Feed
  *
  */

  // ---------------------------------------- Theme
  $THEME = $THEME ?? new CustomTheme();
  $id = get_queried_object_id() ?: 0;

  // ---------------------------------------- Block
  $block_name = "instagram-feed";
  $block_classes = "{$block_name} block block--{$block_name}";
  $block_data = $block["data"] ?? [];
  $block_id = isset($block["id"]) && !empty($block["id"]) ? "{$block_name}--{$block["id"]}" : $block_name;

  // ---------------------------------------- AOS
  $aos_id = $block_id;
  $aos_delay = 150;
  $aos_increment = 150;
  $aos_attrs = $THEME->render_aos_attrs([ "anchor" => $aos_id, "delay" => $aos_delay, "transition" => "fade-left" ]);

  // ---------------------------------------- Content (ACF)
  $background_colour = get_field("background_colour") ?: "";
  $cols = get_field("cols") ?: "col-12";
  $container = get_field("container") ?: "container";
  $enable = get_field("enable") ?: false;
  $instagram_account = get_field("instagram_account") ?: "instagram-account-not-set";
  $instagram_account_glider_id = $THEME->get_unique_id("{$block_name}-glider--");
  $instagram_limit = get_field("instagram_limit") ?: 5;
  $padding_bottom = get_field("padding_bottom") ?: 0;
  $padding_top = get_field("padding_top") ?: 0;
  $text_colour = get_field("text_colour") ?: "";

?>

<?php if ( $enable && $instagram_account ) : ?>

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

    <h2 class="<?= $block_name; ?>__heading heading--2">Engage with us <a href="https://www.instagram.com/<?= $instagram_account; ?>" target="_blank" title="Follow us on Instagram">@<?= $instagram_account; ?></a></h2>

    <div
      class="<?= $block_name; ?>__main js--instagram-feed"
      data-instagram-feed-account="<?= $instagram_account; ?>"
      data-instagram-feed-limit="<?= $instagram_limit; ?>"
    >
      <div class="glide js--glide" id="<?= $instagram_account_glider_id; ?>" data-glide-style="<?= $block_name; ?>">
        <div class="glide__track" data-glide-el="track">
          <ul class="glide__slides">
            <?php
              for ( $i = 0; $i < $instagram_limit; $i++ ) {
                echo "
                  <li class='glide__slide'>
                    <div class='{$block_name}__item' data-index='{$i}'>
                      <a class='{$block_name}__link link' href='' target='_blank' title='Follow {$instagram_account} on Instagram'>
                        <div class='{$block_name}__image lazyload-item lazyload-item--image'
                          data-transition-delay='0'
                          data-transition-duration='300'
                          data-bg=''
                        /></div>
                      </a>
                    </div>
                  </li>
                ";
              }
            ?>
          </ul>
        </div>
      </div>
    </div>

  </section>
<?php endif; ?>
